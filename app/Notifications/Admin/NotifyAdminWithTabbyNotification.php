<?php

namespace App\Notifications\Admin;


use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminWithTabbyNotification extends Notification
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
            ->subject('إشعار بتسجيل طالب جديد في برنامج متكلم باستخدام تاببي للدفع')
            ->view('emails.admin.notify-admin-with-tabby-user',
                [
                    'student'=> $this->student,
                    'payment_url'=> $this->url,
                ]);
    }
}
