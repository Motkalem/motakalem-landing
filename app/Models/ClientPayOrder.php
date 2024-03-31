<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
 use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientPayOrder extends Model
{
    use CrudTrait;
   protected $fillable = ['name', 'age', 'phone', 'city'];


    /**
     * @return HasMany
     */
   public function transations()
   {
       return $this->hasMany(Transaction::class);
   }
}
