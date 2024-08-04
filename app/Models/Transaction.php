<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
 use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use CrudTrait;
    protected $fillable = ['transaction_id', 'client_pay_order_id','success', 'amount', 'status', 'data'];
    protected $casts = ['data'=> 'array'];

    protected $hidden =['data'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transations()
    {
        return $this->belongsTo(Student::class);
    }
}
