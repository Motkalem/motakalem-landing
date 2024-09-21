<?php

namespace App\Actions\HyperPay;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;


class StoreRecurringPaymentData
{
    use AsAction;



    public function handle($package, $payment, $student, $data)
    {


        // try{
        $url = env('HYPERPAY_URL') . "/payments";
        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=" . $package->installment_value .
            "&paymentType=DB" .
            "&shopperResultUrl=" . url('/') .
            "&createRegistration=true" .
            "&currency=SAR" .
            // "&testMode=EXTERNAL" .
            "&paymentBrand=" . strtoupper(data_get($data, 'payment_brand')) .
            "&card.number=" . data_get(data_get($data, 'card'), 'number')  .
            "&card.holder=" . data_get(data_get($data, 'card'), 'holder') .
            "&card.expiryMonth=" . data_get(data_get($data, 'card'), 'expiryMonth') .
            "&card.expiryYear=" . data_get(data_get($data, 'card'), 'expiryYear') .
            "&card.cvv=" . data_get(data_get($data, 'card'), 'cvv') .

            "&customer.email=" . $student?->email .
            "&customer.givenName=" . $student?->name ?? '' .
            "&customer.ip=" . request()->ip() .
            "&customer.surname=" . $student?->name ?? '' .
            "&customer.language=AR" .
            "&customer.mobile=" . $student?->phone ?? '' .

            "&billing.city=" . data_get(data_get($data, 'billing'), 'city') .
            "&billing.country=SA" .
            "&billing.postcode=" . data_get(data_get($data, 'billing'), 'postcode')  .
            "&billing.state=" . data_get(data_get($data, 'billing'), 'state') .
            "&billing.street1=" . data_get(data_get($data, 'billing'), 'street1') .
            "&standingInstruction.type=UNSCHEDULED" .
            "&standingInstruction.mode=REPEATED" .
            "&standingInstruction.source=MIT" .
            "&standingInstruction.recurringType=SUBSCRIPTION" .
            "&merchantTransactionId=" . $payment->id .
            "&standingInstruction.expiry=2030-08-11";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . env('AUTH_TOKEN')
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // this should be set to true in production
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


    public function executeRecurringPayment($registrationId)
    {
        $registrationId = $registrationId;
        $amount = 5;
        $currency = 'SAR';

        $url = "https://eu-prod.oppwa.com/v1/registrations/{$registrationId}/payments";
        $data = [
            'entityId' => config('payments.gateways.card.entity_id'),
            'amount' => $amount,
            'currency' => $currency,
            'paymentType' => 'DB',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('payments.gateways.card.access_token'),
        ])->post($url, $data);

        return response()->json($response->json());
    }
}
