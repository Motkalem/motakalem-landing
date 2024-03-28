<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['transaction_id', 'success', 'amount', 'status', 'data'];
    protected $casts = ['data'=> 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transations()
    {
        return $this->belongsTo(ClientPayOrder::class);
    }
}
