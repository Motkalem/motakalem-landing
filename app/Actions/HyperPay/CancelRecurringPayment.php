<?php

namespace App\Actions\HyperPay;

 use Lorisleiva\Actions\Concerns\AsAction;

class CancelRecurringPayment
{
    use AsAction;

    public function handle($orderId ,string $token)
    {
        $url = "https://eu-test.oppwa.com/scheduling/v1/schedules/8ac7a4a0915b424c0191616d54805567";
        $url .= "?entityId=8ac7a4c790e4d8720190e56cfc7f014f";
        $url .=	"&testMode=EXTERNAL";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer OGFjN2E0Yzc5MGU0ZDg3MjAxOTBlNTZiYjRiZDAxNDZ8VGczeUNDY0RENmJlRldaOQ=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }
}
