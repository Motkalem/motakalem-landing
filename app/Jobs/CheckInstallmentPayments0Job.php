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

class CheckInstallmentPayments0Job implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    /**
     * @return void
     */
    public function handle(): void
    {
        $installmentPayments = InstallmentPayment::with('package')
            ->where('canceled', false)
            ->get();

        foreach ($installmentPayments as $installment) {

            $firstInstallmentDate = Carbon::parse($installment->first_installment_date);

            $currentDate = Carbon::now();

            $monthsPassed = $firstInstallmentDate->diffInMonths($currentDate);

            $nextInstallmentDate = $firstInstallmentDate->addMonths($monthsPassed);

            $paidThisMonth = HyperpayWebHooksNotification::query()
                ->where('installment_payment_id', $installment->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('payload->result->code', '000.000.000')
                ->exists();

            Log::info('installment payment', [json_encode($paidThisMonth)] );

            if ($installment->package && $currentDate->greaterThanOrEqualTo($nextInstallmentDate) && !$paidThisMonth) {

                 $response = ExecuteRecurringPayment::make()->handle($installment->registration_id, $installment);

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

    /**
     * @param $installment_payment
     * @return bool
     */
    public function hasSuccessfulWebhookNotificationThisMonth($installment_payment): bool
    {

        return $installment_payment->hyperpayWebHooksNotifications()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereIn('payload->result->code', ['000.000.000', '000.100.110']) // Check if result.code matches the success code
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

        if (($resultCode !== '000.000.000') || (($resultCode !== '000.100.110'))) {

            Log::warning("Recurring payment failed, will retry tomorrow for installment ID:
                 {$notification->installment_payment_id}");

            $this->release(86400); // 86400 seconds = 24 hours

        } else {}
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
                    ->notify(new  HyperPayNotification(
                        $notification
                    ));
            }

        } catch (\Exception $e) {

            Log::error($e->getMessage());
        }
    }

    #TODO notify the client if the transaction failed
}
