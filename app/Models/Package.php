<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    protected $fillable = ['name','is_active',
                            'number_of_months','payment_type',
                            'installment_value', 'total'];

    protected $casts = [
         'is_active'=>'boolean'
    ];

      # payment types
   const ONE_TIME = 'one time';
   const INSTALLMENTS = 'installments';


    public function payments()
    {

        return $this->hasMany(Payment::class);
    }


}
