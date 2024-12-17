<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultantPatient extends Model
{
    use HasFactory;


    protected $fillable = ['consultation_type_id', 'name', 'age', 'gender',
        'mobile', 'city', 'transaction_data','is_paid'];

    protected $casts = [
        'transaction_data'=>'array'
    ];

    /**
     * @return BelongsTo
     */
    public function consultationType()
    {

        return $this->belongsTo(ConsultantType::class);
    }
}
