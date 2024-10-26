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
        #active recurring payments
        $installmentPayments = InstallmentPayment::with('package')->where('canceled', false)->get();

        foreach ($installmentPayments as $installment) {

            $minutesData = $this->getNumberOfTwoMinutesPassed($installment);
            Log::info("registration id $installment->registration_id");
            // Check if we are at a multiple of 2-minute intervals
            if ($minutesData['numberOfIntervals'] % 2 == 0) {

                $installmentNotifications = HyperpayWebHooksNotification::query()
                    ->where('installment_payment_id', $installment->id)
                    ->get();

                $successfulNotifications = $installmentNotifications->filter(function ($notification) {

                    return $this->isSuccessfulNotification($notification);
                });

                $successInstallments = $successfulNotifications->count(); #number of succeed paid installments

                if ($successInstallments < $installment->package->number_of_months) {

                    $response = ExecuteRecurringPayment::make()->handle($installment->registration_id);

                    # store notification
                    $notification = HyperpayWebHooksNotification::query()->create([
                        'title' => data_get($response, 'result.description'),
                        'installment_payment_id' => $installment->id,
                        'type' => 'execute recurring payment',
                        'payload' => $response,
                        'log' => $response,]);

                    $this->checkResult($notification?->load('installmentPayment.student'));
                    $this->notifyAdmin($notification);
                    $this->notifyStudent($notification, $installment->student?->email);
                }
            }
        }
    }

    /**
     * @param $installment
     * @return array
     */
    public function getNumberOfTwoMinutesPassed($installment): array
    {
        $firstInstallmentDate = Carbon::parse($installment->created_at);
        $currentDate = Carbon::now();

        # Calculate 2-minute intervals passed since the first installment date
        $minutesPassed = $firstInstallmentDate->diffInMinutes($currentDate);
        return ['minutesPassed' => $minutesPassed, 'numberOfIntervals' => intdiv($minutesPassed, 2)];

    }

    /**
     * @param $notification
     * @return bool
     */
    protected function isSuccessfulNotification($notification): bool
    {
        $resultCode = data_get($notification->payload, 'result.code');
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

        return preg_match($successPattern, $resultCode) === 1;
    }

    /**
     * @param $notification
     * @return void
     */
    protected function checkResult($notification): void
    {
        // Handle failed recurring payments
        if (!$this->isSuccessfulNotification($notification)) {

            $this->release(120); // Retry after 5 minutes
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

    /**
     * @param $notification
     * @return void
     */
    protected function notifyStudent($notification, $email): void
    {
        try {
            Notification::route('mail', $email)->notify(new HyperPayNotification($notification));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
