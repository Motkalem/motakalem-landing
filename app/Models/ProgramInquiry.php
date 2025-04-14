<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class ProgramInquiry extends Model
{

    protected $fillable = ['source','name',
        'mobile_number','age','message','transaction_data','is_paid'];



}
