<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendContractNotification extends Notification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public $data) {}

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
        $adminEmails = explode(',', env('ADMIN_EMAILS', 'default@example.com'));

        return (new MailMessage)
            ->subject('عقد انضمام برنامج متكلم للتأتأه')
//            ->cc($adminEmails)
            ->view(
                'emails.contract',
                [
                    'data' => $this->data,
                ]
            );
    }
}
