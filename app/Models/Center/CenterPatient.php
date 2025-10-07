<?php

namespace App\Models\Center;

use App\Models\CenterPayment;
use Illuminate\Database\Eloquent\Model;

class CenterPatient extends Model
{
    protected $fillable = ['source','name','mobile_number','email',
        'id_number','id_end_date','age','city','center_package_id'];

    const DASHBOARD='dashboard';

    public function centerInstallmentPayment()
    {

        return $this->hasOne(CenterInstallmentPayment::class,'patient_id');
    }

    public function centerPayment()
    {
        return $this->hasOne(CenterPayment::class, 'center_patient_id');
    }
}
