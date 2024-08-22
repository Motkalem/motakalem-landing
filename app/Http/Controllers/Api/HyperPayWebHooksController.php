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
    // Retrieve the raw input (hexadecimal format)
    $rawData = file_get_contents('php://input');

    // Convert the hexadecimal payload to binary
    $encryptedPayload = hex2bin($rawData);

    // Retrieve the initialization vector and authentication tag from headers
    $iv = hex2bin($request->header('X-Initialization-Vector'));
    $authTag = hex2bin($request->header('X-Authentication-Tag'));

    // Decrypt the payload
    $decryptedPayload = $this->decryptPayload($encryptedPayload, $iv, $authTag);

    // Decode the decrypted JSON payload
    $data = json_decode($decryptedPayload, true);

    // Log the full request details
    Log::info('Full Request:', [
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'body' => $data,
    ]);

    // Create a new HyperpayWebHooksNotification record
    HyperpayWebHooksNotification::create([
        'installment_payment_id' => null,
        'type' => data_get($data, 'type'),
        'action' => data_get($data, 'action'),
        'payload' => data_get($data, 'payload'),
        'log' => $data,
    ]);

    // Return a JSON response indicating success
    return response()->json([
        "message" => "saved Successfully"
    ], 200);
}

/**
 * Decrypt the payload using AES-256-GCM.
 *
 * @param string $encryptedPayload
 * @param string $iv
 * @param string $authTag
 * @return string
 */
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
