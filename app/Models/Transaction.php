<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['title','transaction_id', 'student_id','payment_id','success',
    'amount', 'status', 'data'];
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
