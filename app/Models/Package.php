<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    protected $fillable = [
        'name','is_active',
        'number_of_months','payment_type',
        'first_inst', 'second_inst', 'third_inst', 'fourth_inst', 'fifth_inst',
        'total', 'starts_date', 'ends_date',];

    protected $casts = [
         'is_active'=>'boolean'
    ];

      # payment types
   const ONE_TIME = 'one time';
   const INSTALLMENTS = 'installments';
   const TABBY = 'tabby';


    public function payments()
    {

        return $this->hasMany(Payment::class);
    }



    public function installmentPayments()
    {

        return $this->hasMany(InstallmentPayment::class);
    }


}
