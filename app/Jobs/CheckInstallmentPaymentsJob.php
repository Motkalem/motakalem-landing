<?php

namespace App\Jobs;

use App\Actions\HyperPay\ExecuteRecurringPayment;
use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use App\Notifications\Admin\HyperPayNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckInstallmentPaymentsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public $tries = 0; # Unlimited retries
    public $maxExceptions = 30; # Retry for up to 30 days

    /**
     * @return void
     */
    public function handle(): void
    {
        // Retrieve all uncanceled installment payments
        $installmentPayments = InstallmentPayment::with('package')
            ->where('canceled', false)
            ->get();

        foreach ($installmentPayments as $installment) {
            if ($installment->registration_id) {

                $installmentNotifications = HyperpayWebHooksNotification::query()
                    ->where('installment_payment_id', $installment->id)
                    ->get();
                # Get successful installments count
                $successfulNotifications = $this->getSuccessfulNotifications($installmentNotifications);
                $successInstallments = $successfulNotifications->count();

                # If all installments are complete, skip
                if ($successInstallments >= $installment->package->number_of_months) {
                    continue;
                }

                $this->attemptPaymentDeduction($installment);
            }
        }
    }

    /**
     * Attempt to deduct payment and handle success or failure.
     *
     * @param InstallmentPayment $installment
     */
    protected function attemptPaymentDeduction(InstallmentPayment $installment): void
    {
        # Attempt to deduct the payment
        $response = ExecuteRecurringPayment::make()->handle($installment->registration_id);
        $notification = $this->storeNotification($response, $installment);

        // Check the result and act accordingly
        if ($this->isSuccessfulNotification($notification)) {

            $this->notifyStudent($notification, $installment->student?->email);
            $this->notifyAdmin($notification);
        } else {

            // Retry after 24 hours if it fails
//            $this->release(86400); // Retry after 24 hours (1 day)
            $this->release(300);

            $this->notifyStudent($notification, $installment->student?->email);
            $this->notifyAdmin($notification);
        }
    }

    /**
     * Filter for successful notifications.
     *
     * @param $installmentNotifications
     * @return mixed
     */
    public function getSuccessfulNotifications($installmentNotifications): mixed
    {
        return $installmentNotifications->filter(function ($notification) {
            return $this->isSuccessfulNotification($notification);
        });
    }

    /**
     * Determine if the notification was successful.
     *
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
     * Store the notification.
     *
     * @param $response
     * @param $installment
     * @return Builder|Model
     */
    public function storeNotification($response, $installment): Model|Builder
    {
        return HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installment->id,
            'type' => 'execute recurring payment',
            'payload' => $response,
            'log' => $response,
        ]);
    }

    /**
     * Notify the admin via email.
     *
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
     * Notify the student via email.
     *
     * @param $notification
     * @param $email
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
