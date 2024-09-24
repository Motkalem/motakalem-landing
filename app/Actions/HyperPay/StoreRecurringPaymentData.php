<?php

namespace App\Actions\HyperPay;

use Lorisleiva\Actions\Concerns\AsAction;

class StoreRecurringPaymentData
{
    use AsAction;

    public function handle($package, $payment, $student, $data)
    {

        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=5.00" .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&createRegistration=true" .
            "&standingInstruction.type=UNSCHEDULED" .
            "&standingInstruction.mode=INITIAL" .
            "&standingInstruction.source=CIT" .
            "&merchantTransactionId=" . $payment->id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . env('AUTH_TOKEN')
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        return $responseData;
    }
}
