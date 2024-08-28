<?php

namespace App\Actions\HyperPay;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreRecurringPaymentData
{
    use AsAction;

    public function handle($package, $payment, $student, $data)
    {

        // try{
            $url = env('HYPERPAY_URL')."/payments";
            $data = "entityId=".env('RECURRING_ENTITY_ID').
                "&amount=".(int)$package->installment_value.
                "&merchantTransactionId=".$payment->id .
                "&paymentBrand=".strtoupper(data_get($data, 'payment_brand')) .
                "&paymentType=DB" .
                "&currency=SAR".
                "&standingInstruction.expiry=2030-08-11" .
                "&customer.language=AR" .
                "&standingInstruction.source=CIT" .
                "&testMode=EXTERNAL" .
                "&customParameters[3DS2_flow]=challenge" .
                "&standingInstruction.mode=REPEATED" .
                "&standingInstruction.type=UNSCHEDULED" .
                "&customer.ip=".request()->ip() .
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
                "&billing.country=SA" ;


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
        // }   catch(Exception $e)    {

        // }


    }
}
