<?php

namespace App\Models\Center;

use App\Models\HyperpayWebHooksNotification;
use App\Models\Installment;
use App\Models\MedicalInquiry;
use App\Models\Package;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CenterInstallmentPayment extends Model
{
    protected $fillable = [
        'patient_id',
        'center_package_id',
        'registration_id',
        'payment_id',
        'canceled',
        'is_completed',
    ];

    protected $appends =['successful_notifications'];

    public function Patient()
    {

        return $this->belongsTo(MedicalInquiry::class,'patient_id');
    }

    public function centerPackage()
    {
        return $this->belongsTo(CenterPackage::class);
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
