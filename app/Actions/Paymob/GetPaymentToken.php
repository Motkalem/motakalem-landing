<?php

namespace App\Actions\Paymob;

use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPaymentToken
{
    use AsAction;

    public function handle($localOrder ,$order,  $token)
    {

        $order_id = $order->id ;

        $amount =  1;
        $amount =  1;
        $billingData = [
            "apartment" => "803",
            "email" => "claudette09@exa.com",
            "floor" => "42",
            "first_name" => "test",
            "street" => "Ethan Land",
            "building" => "8028",
            "phone_number" => "+86(8)9135210487",
            "shipping_method" => "PKG",
            "postal_code" => "01898",
            "city" => "SAU",
            "country" => "SAU",
            "last_name" => "user",
            "state" => "SAU",
        ];

        $data = [
            "auth_token" => $token,
            "amount_cents" => ($amount * 100) ,
            "expiration" => 3600,
            "order_id" => $order_id,
            "billing_data" => $billingData,
            "currency" => "SAR",
            "integration_id" => env('PAYMOB_INTEGRATION_ID'),
        ];


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://ksa.paymob.com/v1/api/acceptance/payment_keys');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept-Encoding: gzip, deflate, br',
            'Connection: keep-alive',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        $result = json_decode($response);

        $token = $result->token;
        return $token;
    }
}
