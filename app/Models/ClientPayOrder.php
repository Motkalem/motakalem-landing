<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
 use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class ClientPayOrder extends Model
{
    use CrudTrait, Notifiable;
   protected $fillable = ['name', 'email','age', 'is_paid','phone', 'city'];


    /**
     * @return HasMany
     */
   public function transations()
   {
       return $this->hasMany(Transaction::class);
   }
}
