<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{

    protected $fillable = ['installment_payment_id', 'installment_amount',
        'installment_date','paid_at','admin_ip', 'is_paid'];

    /**
     * @return BelongsTo
     */
    public function installmentPayment(): BelongsTo
    {

        return $this->belongsTo(InstallmentPayment::class);
    }

}
