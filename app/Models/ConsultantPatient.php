<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantPatient extends Model
{
    use HasFactory;


    protected $fillable = ['consultation_type_id', 'name', 'age', 'gender', 'mobile', 'city'];


    public function consultationType(){

        return $this->belongsTo(ConsultantType::class);
    }
}
