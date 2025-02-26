<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallmentPayment extends Model
{

    protected $fillable = [
        'student_id',
        'package_id',
        'registration_id',
        'payment_id',
        'canceled',
        'is_completed',
        'first_installment_date',
    ];

    protected $appends =['successful_notifications'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * @return HasMany
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }


    public function hyperpayWebHooksNotifications()
    {
        return $this->hasMany(HyperpayWebHooksNotification::class);
    }



    public function getSuccessfulNotificationsAttribute()
    {

        $successNotifications = $this->hyperpayWebHooksNotifications()
            ->get()
            ->filter(function ($notification) {

                return $this->isSuccessfulNotifications($notification)  ;
            });

        return $successNotifications->count();
    }

    protected function isSuccessfulNotifications($notification)
    {
        $resultCode = data_get($notification->payload, 'result.code');
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

        return  preg_match($successPattern, $resultCode) === 1;
    }

}
