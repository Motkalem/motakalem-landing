<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name','is_active','number_of_months', 'installment_value'];

    protected $casts = [

    'is_active'=>'boolean'
    ];
}
