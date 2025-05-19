<?php

namespace App\Models\Center;

use Illuminate\Database\Eloquent\Model;

class CenterPatient extends Model
{
    protected $table = 'medical_inquiries';
    protected $fillable = ['source','name','mobile_number','email', 'id_number','id_end_date','age','message'];


}
