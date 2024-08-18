<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HyperpayWebHooksNotification;
use Illuminate\Http\Request;

class HyperPayWebHooksController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();

        HyperpayWebHooksNotification::create([
            'installment_payment_id' =>  null,
            'type' => $data['type'],
            'action' => $data['action'],
            'payload' =>  $data['payload'],
        ]);
        return response()->json([
            "message" => "saved Successfully"
        ], 200);
    }


}
