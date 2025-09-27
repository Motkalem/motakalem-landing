<?php

namespace App\Notifications\Admin;

use App\Models\Center\CenterPatient;
use App\Models\CenterPayment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCenterSubscriptionNotification extends Notification
{
    protected CenterPatient $centerPatient;
    protected CenterPayment $centerPayment;

    /**
     * Create a new notification instance.
     *
     * @param CenterPatient $centerPatient
     * @param CenterPayment $centerPayment
     * @return void
     */
    public function __construct(CenterPatient $centerPatient, CenterPayment $centerPayment)
    {
        $this->centerPatient = $centerPatient;
        $this->centerPayment = $centerPayment;
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
        $packageName = $this->centerPatient->centerPackage?->name ?? 'غير محدد';
        $paymentType = $this->centerPatient->payment_type_label;

        return (new MailMessage)
            ->subject('تم تسجيل اشتراك جديد في المركز')
            ->view('emails.admin.new-centersubscription-email', [
                'centerPatient' => $this->centerPatient,
                'centerPayment' => $this->centerPayment,
                'packageName' => $packageName,
                'paymentType' => $paymentType,
                'patientName' => $this->centerPatient->name,
                'mobile' => $this->centerPatient->formatted_mobile,
                'email' => $this->centerPatient->email,
                'city' => $this->centerPatient->city,
                'age' => $this->centerPatient->age,
                'amount' => number_format($this->centerPayment->amount, 2),
            ]);
    }

}
