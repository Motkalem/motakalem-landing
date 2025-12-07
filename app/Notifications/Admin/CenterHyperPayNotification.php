<?php

namespace App\Notifications\Admin;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CenterHyperPayNotification extends Notification implements ShouldQueue
{
    use Queueable;
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
            ->subject(  'مركز متكلم: إشعار بعملية دفع قسط مركز متكلم')
            ->view('emails.admin.center-hyperpay-notification-email',
                [
                    'notification'=> $this->notification,
                    'result'=> $this->result,
                ]);
    }
}
