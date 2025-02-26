<?php

namespace App\Notifications\Admin;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriptionNotification extends Notification
{
    protected   $student;
    protected   $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student, $transaction)
    {

        $this->student = $student;
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {

        return (new MailMessage)
            ->subject(  'تم تسجيل اشتراك جديد')
            ->view('emails.admin.subscription-updates-email',
                [
                    'student'=> $this->student,
                    'transaction'=> $this->transaction,
                ]);
    }
}
