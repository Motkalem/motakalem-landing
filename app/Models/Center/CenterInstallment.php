<?php

namespace App\Models\Center;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CenterInstallment extends Model
{

    protected $fillable = ['center_installment_payment_id', 'installment_amount',
        'installment_date','paid_at','admin_ip', 'is_paid'];

    /**
     * @return BelongsTo
     */
    public function centerInstallmentPayment(): BelongsTo
    {

        return $this->belongsTo(CenterInstallmentPayment::class);
    }

}
