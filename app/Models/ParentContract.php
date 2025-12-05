<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentContract extends Model
{
    use HasFactory;

    protected $fillable =['name', 'age', 'phone' , 'city', 'email', 'id_number', 'id_end', 'accept_terms'];


}
