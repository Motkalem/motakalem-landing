<?php

namespace App\Actions\Paymob;

use App\Models\Student;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPaymentToken
{
    use AsAction;

    public function handle($localOrder ,$order,  $token, $clientOrderPay)
    {

        $order_id = $order->id ;

        $name =$clientOrderPay->name;

        $amount = env('SUBSCRIPTION_AMOUNT')??12000;

        $billingData = [
            "apartment" => "803",
            "email" => "claudette09@exa2.com",
            "floor" => "42",
            "first_name" =>$name ,
            "street" => "Riyad",
            "building" => "8028",
            "phone_number" => "+966".$clientOrderPay->phone ,
            "shipping_method" => "PKG",
            "postal_code" => "01898",
            "city" =>$clientOrderPay->city,
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

        if(request()->payment_type == Student::INSTALLMENTS){

            $data =  array_merge($data, [
                "integration_id" => env('TABBY_PAYMOB_INTEGRATION_ID'),
            ]);
        }


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
