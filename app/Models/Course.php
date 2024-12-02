<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'starts_at', 'active', 'ends_at', 'price'
    ];

    protected $casts = [

        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    /**
     * @return HasMany
     */

    public function contracts(): HasMany
    {
        return $this->hasMany(ParentContract::class);
    }
}
