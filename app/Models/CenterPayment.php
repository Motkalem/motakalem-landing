<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CenterPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'center_patient_id',
        'center_package_id',
        'amount',
        'payment_type',
        'status',
        'is_finished',
        'payment_data',
        'paid_at',
    ];

    protected $casts = [
        'payment_data' => 'array',
        'is_finished' => 'boolean',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Payment types
    const PAYMENT_TYPE_ONE_TIME = 'onetime';
    const PAYMENT_TYPE_INSTALLMENT = 'installment';

    // Payment statuses
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the center patient that owns the payment
     */
    public function centerPatient(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Center\CenterPatient::class);
    }


    public function centerPaymentTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\CenterPaymentTransaction::class, 'center_payment_id');
    }
    /**
     * Get the center package for this payment
     */
    public function centerPackage(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Center\CenterPackage::class);
    }

    /**
     * Get the transactions for this payment
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(CenterPaymentTransaction::class);
    }

    /**
     * Scope for one-time payments
     */
    public function scopeOneTime($query)
    {
        return $query->where('payment_type', self::PAYMENT_TYPE_ONE_TIME);
    }

    /**
     * Scope for installment payments
     */
    public function scopeInstallment($query)
    {
        return $query->where('payment_type', self::PAYMENT_TYPE_INSTALLMENT);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}
