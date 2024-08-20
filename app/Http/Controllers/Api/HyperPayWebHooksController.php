<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HyperpayWebHooksNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HyperPayWebHooksController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();

        Log::info('data', $data);

        HyperpayWebHooksNotification::create([
            'installment_payment_id' =>  null,
            'type' => data_get($data, 'type'),
            'action' => data_get($data, 'action'),
            'payload' => data_get($data, 'payload'),
            'log' => $data,
        ]);
        return response()->json([
            "message" => "saved Successfully"
        ], 200);
    }
}
