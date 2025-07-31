<?php

namespace App\Jobs;

use App\Actions\HyperPay\ExecuteRecurringPayment;
use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use App\Notifications\Admin\CenterHyperPayNotification;
use App\Notifications\Admin\HyperPayNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendTransactionsNotificationsJob   implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    /**
     * @return void
     */
    public function handle()
    {
        //Log::notice('Running == SendTransactionsNotificationsJob');

        $installmentNotifications = HyperpayWebHooksNotification::query()
            ->select([
                "id",
                "title",
                "center_installment_payment_id",
                "installment_payment_id",
                "admin_notified",
                "student_notified",
                "payload->amount as amount",
                "payload->result->code as code"
            ])
            ->with('installmentPayment.student:id,name,email,phone')
            ->with('centerInstallmentPayment.patient')
            ->where(function ($query) {
                $query->where('admin_notified', 0)
                    ->orWhere('student_notified', 0);
            })->get();


        foreach ($installmentNotifications as $notification) {

            if ( $notification->amount != 0 ) {

                if ($notification->installment_payment_id){

                    $this->notifyStudent($notification, $notification->installmentPayment?->student?->email);
                    $this->notifyAdmin($notification);
                }


                if($notification->center_installment_payment_id){


                    $this->notifyCenterPatient($notification, $notification->centerInstallmentPayment?->patient?->email);
                    $this->notifyCenterAdmin($notification);
                }
            }

        }
    }

    /**
     * Determine if the notification was successful.
     *
     * @param $notification
     * @return bool
     */
    protected function isSuccessfulNotification($notification): bool
    {
        $resultCode =  $notification->code;
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

        return preg_match($successPattern, $resultCode) === 1;
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

                $result = $this->isSuccessfulNotification($notification) ? "تمت المعاملة بنجاح !" : "فشلت العملية !";

                Notification::route('mail', $adminEmail)->notify(new HyperPayNotification($notification, $result));

                $notification->update(['admin_notified'=> 1]);
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
            $result = $this->isSuccessfulNotification($notification) ? "تمت المعاملة بنجاح !" : "فشلت العملية !";

            Notification::route('mail', $email)->notify(new HyperPayNotification($notification, $result));
            $notification->update(['student_notified'=> 1]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }



    protected function notifyCenterPatient($notification, $email): void
    {
        try {

            $result = $this->isSuccessfulNotification($notification) ? "تمت المعاملة بنجاح !" : "فشلت العملية !";

            Notification::route('mail', $email)->notify(new CenterHyperPayNotification($notification, $result));
            $notification->update(['student_notified'=> 1]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Notify the admin via email.
     *
     * @param $notification
     * @return void
     */
    protected function notifyCenterAdmin($notification): void
    {
        try {
            $adminEmails = explode(',', env('ADMIN_EMAILS'));
            foreach ($adminEmails as $adminEmail) {

                $result = $this->isSuccessfulNotification($notification) ? "تمت المعاملة بنجاح !" : "فشلت العملية !";

                Notification::route('mail', $adminEmail)->notify(new CenterHyperPayNotification($notification, $result));

                $notification->update(['admin_notified'=> 1]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


}
