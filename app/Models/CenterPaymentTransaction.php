<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CenterPaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'center_payment_transactions';

    protected $fillable = [
        'center_payment_id',
        'center_patient_id',
        'transaction_id',
        'title',
        'amount',
        'success',
        'payment_method',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the center payment that owns the transaction
     */
    public function centerPayment(): BelongsTo
    {
        return $this->belongsTo(CenterPayment::class);
    }

    /**
     * Get the center patient for this transaction
     */
    public function centerPatient(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Center\CenterPatient::class);
    }

    /**
     * Scope for successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', 'true');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('success', 'false');
    }
}
