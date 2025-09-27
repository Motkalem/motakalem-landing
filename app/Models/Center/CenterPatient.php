<?php

namespace App\Models\Center;

use Illuminate\Database\Eloquent\Model;

class CenterPatient extends Model
{
    protected $fillable = ['source','name','mobile_number','email',
        'id_number','id_end_date','age','city','center_package_id','payment_type'];

    const DASHBOARD='dashboard';

    public function centerInstallmentPayment()
    {

        return $this->hasOne(CenterInstallmentPayment::class,'patient_id');
    }

    public function centerPackage()
    {
        return $this->belongsTo(CenterPackage::class);
    }

    public function centerPayment()
    {
        return $this->hasOne(\App\Models\CenterPayment::class);
    }


}
