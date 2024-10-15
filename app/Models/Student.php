<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use  Notifiable;
    protected $fillable = ['package_id','name', 'email', 'payment_type', 'total_payment_amount', 'age', 'is_paid', 'phone', 'city'];

    # payment types
    const ONE_TIME = 'one time';
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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function installmentPayment()
    {
        return $this->hasOne(InstallmentPayment::class);
    }

}
