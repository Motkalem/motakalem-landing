<?php

namespace App\Notifications\Admin;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HyperPayNotification extends Notification
{
    protected   $notification;
    protected   $result;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification, $result='')
    {

        $this->notification = $notification;
        $this->result = $result;
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
            ->subject(  'متكلم: إشعار بعملية دفع جديدة عبر هايبر باي')
            ->view('emails.admin.hyperpay-notification-email',
                [
                    'notification'=> $this->notification,
                    'result'=> $this->result,
                ]);
    }
}
