<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SentPaymentUrlNotification extends Notification
{
    protected $student;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student, $url)
    {
        $this->student = $student;
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
            ->view('emails.payment-url-email',
                [
                    'student'=> $this->student,
                    'payment_url'=> $this->url,
                ]);
    }
}
