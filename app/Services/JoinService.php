<?php

namespace App\Services;

use App\Models\Join;
use Illuminate\Database\Eloquent\Model;

class JoinService
{


    /**
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        return Join::create($data);
    }
}
