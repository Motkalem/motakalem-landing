<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultantType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'message', 'is_active'];

    /**
     * @return HasMany
     */
    public function consultantPatients(): HasMany
    {
        return $this->hasMany(ConsultantPatient::class, 'consultation_type_id');
    }
}
