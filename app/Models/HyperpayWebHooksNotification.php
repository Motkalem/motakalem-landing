<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HyperpayWebHooksNotification extends Model
{
    use HasFactory;

    protected $fillable = ['installment_payment_id', 'type', 'action', 'payload'];

    protected $casts = [
        'action' => 'array',
        'payload' => 'array',
    ];

    public function installmentPayment()
    {

        return $this->belongsTo(InstallmentPayment::class);
    }
}
