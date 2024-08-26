<?php

namespace App\Http\Controllers\Api;

use App\Classes\HyperpayNotificationProcessor;
use App\Http\Controllers\Controller;
use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HyperPayWebHooksController extends Controller
{
    public function store(Request $request)
    {
        $rawData = file_get_contents('php://input');
        $encryptedPayload = hex2bin($rawData);
        $iv = hex2bin($request->header('X-Initialization-Vector'));
        $authTag = hex2bin($request->header('X-Authentication-Tag'));
        $decryptedPayload = $this->decryptPayload($encryptedPayload, $iv, $authTag);

        $data = json_decode($decryptedPayload, true);

        $installmentPayment = InstallmentPayment::where('id', data_get(data_get($data, 'payload'), 'merchantTransactionId'))
        ->orWhereHas('student', function ($query) use ($data) {
            $query->where('email', data_get(data_get($data, 'payload.customer'), 'email'));
        }) ->first();

        $HyperpayNotificationProcessor = new HyperpayNotificationProcessor($decryptedPayload);
        $title = $HyperpayNotificationProcessor->processNotification();

        HyperpayWebHooksNotification::create([
            'title' => $title,
            'installment_payment_id' => $installmentPayment?->id,
            'type' => data_get($data, 'type'),
            'action' => data_get($data, 'action'),
            'payload' => data_get($data, 'payload'),
            'log' => $data,
        ]);

        return response()->json([
            "message" => "saved Successfully"
        ], 200);
    }

    private function decryptPayload($encryptedPayload, $iv, $authTag)
    {
        $key = hex2bin(env('DECRYPT_KEY'));

        return openssl_decrypt(
            $encryptedPayload,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $authTag
        );
    }
}
