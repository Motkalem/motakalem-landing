<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class ClientPayOrder extends Model
{
   protected $fillable = ['name', 'age', 'phone', 'city'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
   public function transations()
   {
       return $this->hasMany(Transaction::class);
   }
}
