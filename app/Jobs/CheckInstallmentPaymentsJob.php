<?php

namespace App\Jobs;

use App\Actions\HyperPay\ExecuteRecurringPayment;
use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use App\Notifications\Admin\HyperPayNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckInstallmentPaymentsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    /**
     * @return void
     */
    public function handle(): void
    {
        // Fetch all non-canceled installment payments
        $installmentPayments = InstallmentPayment::with('package')
            ->where('canceled', false)
            ->get();

        foreach ($installmentPayments as $installment) {
            $firstInstallmentDate = Carbon::parse($installment->first_installment_date);
            $currentDate = Carbon::now();

            $minutesPassed = $firstInstallmentDate->diffInMinutes($currentDate);

            if ($minutesPassed % 5 == 0) {
                $paidThisPeriod = HyperpayWebHooksNotification::query()
                    ->where('installment_payment_id', $installment->id)
                    ->where('created_at', '>=', Carbon::now()->subMinutes(5))
                    ->where('payload->result->code', '000.000.000')
                    ->exists();

                Log::info('installment payment', [json_encode($paidThisPeriod)]);

                if (!$paidThisPeriod) {
                    $response = ExecuteRecurringPayment::make()->handle($installment->registration_id);

                    $notification = HyperpayWebHooksNotification::query()->create([
                        'title' => data_get($response, 'result.description'),
                        'installment_payment_id' => $installment->id,
                        'type' => 'execute recurring payment',
                        'payload' => $response,
                        'log' => $response,
                    ]);

                    $this->checkResult($notification?->load('installmentPayment.student'));
                    $this->notifyAdmin($notification);
                }
            }
        }
    }

    /**
     * Check if the webhook has a successful notification for this period (5 minutes window).
     *
     * @param $installment_payment
     * @return bool
     */
    public function hasSuccessfulWebhookNotificationThisPeriod($installment_payment): bool
    {
        return $installment_payment->hyperpayWebHooksNotifications()
            ->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->whereIn('payload->result->code', ['000.000.000', '000.100.110'])
            ->exists();
    }

    /**
     * @param $notification
     * @return void
     */
    protected function checkResult($notification): void
    {
        $payload = $notification->payload;
        $resultCode = data_get($payload, 'result.code');

        // Handle failed recurring payments
        if (!in_array($resultCode, ['000.000.000', '000.100.110'])) {
            Log::warning("Recurring payment failed, will retry in 5 minutes for installment ID:
                 {$notification->installment_payment_id}");

            $this->release(300); // Retry after 5 minutes
        }
    }

    /**
     * @param $notification
     * @return void
     */
    protected function notifyAdmin($notification): void
    {
        try {
            $adminEmails = explode(',', env('ADMIN_EMAILS'));
            foreach ($adminEmails as $adminEmail) {
                Notification::route('mail', $adminEmail)
                    ->notify(new HyperPayNotification($notification));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    #TODO notify the client if the transaction failed
}
