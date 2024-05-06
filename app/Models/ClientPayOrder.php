<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
 use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class ClientPayOrder extends Model
{
    use CrudTrait, Notifiable;
   protected $fillable = ['name', 'email','payment_type','total_payment_amount','age', 'is_paid','phone', 'city'];

   # payment types
   const ONE_TIME = 'one_time';
   const INSTALLMENTS = 'installments';

   public static array $paymentTypes = [
       self::ONE_TIME,
       self::INSTALLMENTS
   ];

    /**
     * @return HasMany
     */
   public function transations()
   {
       return $this->hasMany(Transaction::class);
   }
}
