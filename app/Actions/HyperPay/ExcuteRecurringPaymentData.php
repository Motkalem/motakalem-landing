<?php

namespace App\Actions\HyperPay;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class ExcuteRecurringPaymentData
{
    use AsAction;

    public function handle($amount=5, $recurringRegistrationId="8acda4a391e0561a01920982bd5d7e2e")
    {
        dd(2);

        $url = env('HYPERPAY_URL') . "/payments";
        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=" . $amount .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&recurringType=REPEATED" .
            "&recurring.registrationId=" . $recurringRegistrationId;

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
        \Log::info('Hyperpay Recurring Response: ', ['response' => $responseData]);

        return json_decode($responseData, true);
    }
}
