<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{

   protected $fillable = ['student_id', 'package_id',
   'hyper_payment_id', 'registration_id',
    'installment_amount', 'card', 'billing',
     'initiat_status', 'schedule_result', 'schedule_job'];

    protected $casts = [
        'card'=>'array',
        'billing'=>'array',
        'schedule_result'=>'array',
        'schedule_job'=>'array',
        'cancel_result'=>'array',
    ];
}
