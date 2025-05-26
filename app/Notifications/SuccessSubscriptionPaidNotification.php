<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessSubscriptionPaidNotification extends Notification
{
    protected   $user;
    protected   $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $transaction)
    {

        $this->user = $user;
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
            ->subject(  'شكــرا تم الدفـــع بنجــاح')
            ->view('emails.success-subscription-paid-email',
                [
                    'user'=> $this->user,
                    'transaction'=> $this->transaction,
                ]);
    }
}
