<?php

namespace App\Actions\HyperPay;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreRecurringPaymentData
{
    use AsAction;

    public function handle($package, $payment, $student, $data)
    {

        try{
            $url = env('HYPERPAY_URL')."/payments";
            $data = "entityId=".env('ENITY_ID').
                "&amount=".(int)$package->installment_value.
                "&merchantTransactionId=".$payment->id .
                "&paymentBrand=MADA" .
                 "&paymentType=DB" .
                "&currency=SAR".
                "&standingInstruction.expiry=2030-08-11" .
                "&customer.language=AR" .
                "&standingInstruction.source=CIT" .
                "&testMode=EXTERNAL" .
                "&customParameters[3DS2_flow]=challenge" .
                "&standingInstruction.mode=REPEATED" .
                "&standingInstruction.type=UNSCHEDULED" .
                "&customer.ip=192.168.0.0" .
                "&standingInstruction.recurringType=SUBSCRIPTION".
                "&createRegistration=true".
                "&shopperResultUrl=".url('/').
                "&card.number=".data_get(data_get($data,'card'), 'number')  .
                "&card.holder=".data_get(data_get($data,'card'), 'holder') .
                "&card.expiryMonth=".data_get(data_get($data,'card'), 'expiryMonth').
                "&card.expiryYear=".data_get(data_get($data,'card'), 'expiryYear') .
                "&card.cvv=".data_get(data_get($data,'card'), 'cvv').

                "&customer.email=".$student?->email .
                "&customer.givenName=".$student?->name??'' .
                "&customer.surname=".$student?->name??'' .

                "&billing.city=".data_get(data_get($data,'billing'), 'city') .
                "&billing.postcode=".data_get(data_get($data,'billing'), 'postcode')  .
                "&billing.state=".data_get(data_get($data,'billing'), 'state') .
                "&billing.street1=".data_get(data_get($data,'billing'), 'street1') .
                "&billing.country=SA"


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
                'Authorization:Bearer '.env('AUTH_TOKEN')
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
        }   catch(Exception $e)    {

        }


    }
}
