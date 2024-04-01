<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessSubscriptionPaidNotification extends Notification
{
    protected int $clientID;
    protected string $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($clientID,$name)
    {

        $this->clientID = $clientID;
        $this->name = $name;
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
            ->subject(  'تم دفع قيمة الإشتراك بنجاح')
            ->view('emails.success-subscription-paid-email',
                [
                    'clientID'=> $this->clientID,
                    'name'=> $this->name,
                ]);
    }
}
