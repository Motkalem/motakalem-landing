<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name','is_active',
                            'number_of_months','payment_type',
                            'installment_value', 'total'];

    protected $casts = [
         'is_active'=>'boolean'
    ];

      # payment types
   const ONE_TIME = 'one_time';
   const INSTALLMENTS = 'installments';
}
