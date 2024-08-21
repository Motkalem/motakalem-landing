<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{

    protected $fillable = [
        'student_id',
        'package_id',
        'registration_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
