<?php

namespace App\Models\Center;

use Illuminate\Database\Eloquent\Model;

class CenterPatient extends Model
{
    protected $fillable = ['source','name','mobile_number','email',
        'id_number','id_end_date','age'];

    const DASHBOARD='dashboard';

    public function centerInstallmentPayment()
    {

        return $this->hasOne(CenterInstallmentPayment::class,'patient_id');
    }
}
