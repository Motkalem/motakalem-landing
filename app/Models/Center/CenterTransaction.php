<?php

namespace App\Models\Center;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CenterTransaction extends Model
{
    protected $fillable = [
        'title',
        'center_installment_payment_id',
        'success',
        'amount',
        'status',
        'data'];

    protected $casts = ['data'=> 'array'];

    protected $hidden =['data'];

    /**
     * @return BelongsTo
     */
    public function centerPatient()
    {
        return $this->belongsTo(CenterPatient::class);
    }
}
