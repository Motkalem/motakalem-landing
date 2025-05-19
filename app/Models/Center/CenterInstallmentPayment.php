<?php

namespace App\Models\Center;

use App\Models\HyperpayWebHooksNotification;
use App\Models\Installment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CenterInstallmentPayment extends Model
{
    protected $fillable = [

        'patient_id',
        'center_package_id',
        'registration_id',
//        'payment_id',
        'canceled',
        'is_completed',
    ];


    public function patient()
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
    public function centerInstallments(): HasMany
    {
        return $this->hasMany(CenterInstallment::class);
    }

    /**
     * @return HasMany
     */
    public function centerTransaction(): HasMany
    {
        return $this->hasMany(CenterTransaction::class);
    }


    public function hyperpayWebHooksNotifications()
    {
        return $this->hasMany(HyperpayWebHooksNotification::class);
    }

    protected function isSuccessfulNotifications($notification)
    {
        $resultCode = data_get($notification->payload, 'result.code');
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

        return  preg_match($successPattern, $resultCode) === 1;
    }

}
