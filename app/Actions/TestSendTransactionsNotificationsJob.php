<?php

namespace App\Actions;

use App\Models\HyperpayWebHooksNotification;
use App\Notifications\Admin\HyperPayNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class TestSendTransactionsNotificationsJob
{
    use AsAction;


    /**
     * @return void
     */
    public function handle()
    {
        $installmentNotifications = HyperpayWebHooksNotification::query()
            ->select([
                "id",
                "title",
                "installment_payment_id",
                "admin_notified",
                "student_notified",
                "payload->amount as amount",
                "payload->result->code as code"
            ])
            ->with('installmentPayment.student:id,name,email,phone')
            ->whereNotNull('installment_payment_id')
            ->where(function ($query) {
                $query->where('admin_notified', 0)
                    ->orWhere('student_notified', 0);
            })
            ->get();


         foreach ($installmentNotifications as $notification) {

            if ( $notification->amount != 0 ) {

                $this->notifyStudent($notification, $notification->installmentPayment?->student?->email);
                $this->notifyAdmin($notification);
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
}
