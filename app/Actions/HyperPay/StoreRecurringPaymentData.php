<?php

namespace App\Actions\HyperPay;

 use Lorisleiva\Actions\Concerns\AsAction;

class StoreRecurringPaymentData
{
    use AsAction;

    public function handle($orderId ,string $token)
    {
        $url = "https://eu-test.oppwa.com/v1/payments";
        $data = "entityId=8ac7a4c790e4d8720190e56cfc7f014f" .
            "&amount=23".
            "&paymentType=DB" .
            "&createRegistration=true" .
            "&merchantTransactionId=31222" .
            "&currency=SAR" .
            "&testMode=EXTERNAL" .
            "&paymentBrand=MADA" .
            "&card.number=4464040000000007" .
            "&card.holder=John Smith" .
            "&card.expiryMonth=12".
            "&card.expiryYear=2024" .
            "&card.cvv=100" .
            "&customer.email=john.smith@gmail.com" .
            "&customer.givenName=Amin" .
            "&customer.ip=192.168.0.0" .
            "&customer.surname=John" .
            "&customer.language=AR" .
            "&billing.city=MyCity" .
            "&billing.country=SA" .
            "&billing.postcode=11564" .
            "&billing.state=jeda" .
            "&billing.street1=MyStreet" .
            "&standingInstruction.expiry=2030-08-11" .
            "&customParameters[3DS2_flow]=challenge" .
            "&standingInstruction.mode=REPEATED" .
            "&standingInstruction.type=UNSCHEDULED" .
            "&standingInstruction.source=CIT" .
            "&standingInstruction.recurringType=SUBSCRIPTION"
            // "&customParameters[3DS2_enrolled]=true".
            // "&threeDSecure.eci=05" .
            // "&threeDSecure.authenticationStatus=Y" .
            // "&threeDSecure.version=2.2.0"
            // "&threeDSecure.dsTransactionId=c75f23af-9454-43f6-ba17-130ed529507e" .
            // "&threeDSecure.acsTransactionId=2c42c553-176f-4f08-af6c-f9364ecbd0e8" .
            // "&threeDSecure.verificationId=MTIzNDU2Nzg5MDEyMzQ1Njc4OTA=" .
            // "&threeDSecure.amount=23" .
            // "&threeDSecure.currency=SAR" .
            // "&threeDSecure.flow=challenge"
            ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Yzc5MGU0ZDg3MjAxOTBlNTZiYjRiZDAxNDZ8VGczeUNDY0RENmJlRldaOQ=='
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;



    }
}
