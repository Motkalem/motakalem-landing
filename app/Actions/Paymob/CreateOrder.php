<?php

namespace App\Actions\Paymob;

 use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOrder
{
    use AsAction;

    public function handle($orderId ,string $token)
    {
        $amount = env('SUBSCRIPTION_AMOUNT')??12000;
        $data = [

            "auth_token" => $token,
            "delivery_needed" => "false",
            "amount_cents" =>  $amount * 100,
            "currency" => "SAR",
            "merchant_order_id"=>  $orderId.'-'.Carbon::now()->timestamp,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://ksa.paymob.com/v1/api/ecommerce/orders');
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

        return $result;
    }
}
