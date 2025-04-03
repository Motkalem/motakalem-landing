<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalInquiry extends Model
{
    protected $fillable = ['source','name','mobile_number','age','message'];


}
