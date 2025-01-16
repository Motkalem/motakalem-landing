<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HyperpayWebHooksNotification extends Model
{
    use HasFactory;

    protected $fillable = ['student_notified','admin_notified','title', 'installment_payment_id', 'installment_id',
        'type', 'log', 'action', 'payload'];

    protected $casts = [
        'action' => 'array',
        'payload' => 'array',
        'log' => 'array',
    ];

    public function installmentPayment()
    {
        return $this->belongsTo(InstallmentPayment::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
