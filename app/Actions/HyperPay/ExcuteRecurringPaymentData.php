<?php

namespace App\Actions\HyperPay;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class ExcuteRecurringPaymentData
{
    use AsAction;

    public function handle($package, $payment, $student, $data)
    {
        $url = env('HYPERPAY_URL') . "/payments";
        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=" . $package->installment_value .
            "&paymentType=DB" .
            "&createRegistration=true" .
            "&currency=SAR" .
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
            "&shopperResultUrl=" . url('/') .
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

        // Log the response data for debugging
        \Log::info('Hyperpay Response: ', ['response' => $responseData]);

        // Decode the response data
        $responseArray = json_decode($responseData, true);

        // Check for pending status and handle accordingly
        if (isset($responseArray['result']['code']) && $responseArray['result']['code'] === '000.200.000') {
            // Transaction is pending, handle accordingly
            \Log::warning('Transaction is pending', ['response' => $responseArray]);
        }

        // Store the recurring registration ID for future use
        $recurringRegistrationId = $responseArray['id'];

        return $responseArray;
    }
}
