<?php

namespace App\Notifications\Admin;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CenterPaymentUrlNotification extends Notification
{
    protected $patient;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($patient, $url)
    {
        $this->patient = $patient;
        $this->url = $url;
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
            ->subject(  ' رابط الدفع لإشتراكم في برنامج متكلم' )
            ->view('emails.admin.center-payment-url-email',
                [
                    'patient'=> $this->patient,
                    'payment_url'=> $this->url,
                ]);
    }
}
