<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    use CrudTrait;

    protected $table = "contact_us";
    protected $fillable = [
        "name",
        "phone",
        "email",
        "message"
    ];
}
