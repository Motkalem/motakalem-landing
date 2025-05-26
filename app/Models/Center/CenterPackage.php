<?php

namespace App\Models\Center;

use App\Models\InstallmentPayment;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class CenterPackage extends Model
{
    protected $fillable = [
        'name','is_active',
        'number_of_months',
        'first_inst', 'second_inst',
        'third_inst', 'fourth_inst',
        'fifth_inst', 'total', 'starts_date',
        'ends_date',
        ];

    protected $casts = [
        'is_active'=>'boolean'
    ];

    const INSTALLMENTS = 'installments';


    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class);
    }


}
