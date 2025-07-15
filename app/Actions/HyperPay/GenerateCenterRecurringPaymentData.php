<?php

namespace App\Actions\HyperPay;

use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateCenterRecurringPaymentData
{
    use AsAction;
    public function handle($package, $payment, $paymentBrand=null)
    {
        $url = env('RYD_HYPERPAY_URL')."/checkouts";

        $entity_id = env('RYD_ENTITY_ID'); //visa or master
        $access_token = env('RYD_AUTH_TOKEN');

        if(request()->brand == 'MADA')
        {
            $entity_id = env('RYD_ENTITY_ID_MADA'); //mada
        }
        if(request()->brand == 'applepay')
        {
            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
                $access_token = config('hyperpay.ryd_apple_pay_token');
        }


        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);
        $unique_transaction_id = $timestamp . str_replace('.', '', $micro_time);
        $unique_transaction_id = $payment->id.'-'. $unique_transaction_id;
        $unique_agreement_id = $payment->id.'-'. $unique_transaction_id.rand(10,9999);

        $data = [
            'entityId' => $entity_id,
            'amount' => $package->first_inst,
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'createRegistration' => 'true',
            'standingInstruction.type' => 'RECURRING',
            'standingInstruction.mode' => 'INITIAL',
            'standingInstruction.source' => 'CIT',
            'standingInstruction.expiry' => '2030-12-31',
            'standingInstruction.frequency' => '0030', // 30 days between payments
            'standingInstruction.numberOfInstallments' => '99',
            'standingInstruction.recurringType' => 'SUBSCRIPTION', // Fixed amount
            'customParameters[paymentFrequency]' => 'OTHER',
            'customParameters[recurringPaymentAgreement]' => $unique_agreement_id,
            'merchantTransactionId' => $unique_transaction_id,
            "customer.email" => $payment?->patient?->email,
            "billing.street1" => $payment?->patient?->city,
            "billing.city" => $payment?->patient?->city,
            "billing.state" => $payment?->patient?->city,
            "billing.country" => "SA",
            "billing.postcode" => "",
            "integrity" => "true",
            "customer.givenName" => $payment?->patient?->name,
            "customer.surname" => ""
        ];

        if (env('VERSION_STATE') == 'STAGING_'){
            $data = [
                'entityId' =>$entity_id,
                'amount' => $package->first_inst,
                'currency' => 'SAR',
                'paymentType' => 'DB',
                'createRegistration' => 'true',
                'standingInstruction.type' => 'RECURRING',
                'standingInstruction.mode' => 'INITIAL',
                'standingInstruction.source' => 'CIT',
                'standingInstruction.expiry' => '2030-12-31',
                'standingInstruction.frequency' => '0030',
                'standingInstruction.numberOfInstallments' => '99',
                'standingInstruction.recurringType' => 'SUBSCRIPTION',
                'customParameters[paymentFrequency]' => 'OTHER',
                'customParameters[recurringPaymentAgreement]' => $unique_agreement_id,
                'merchantTransactionId' => $unique_transaction_id,
                "customer.email" => $payment?->patient?->email,
                "billing.street1" => $payment?->patient?->city,
                "billing.city" => $payment?->patient?->city,
                "billing.state" => $payment?->patient?->city,
                "billing.country" => "SA",
                "billing.postcode" => "",
                "integrity" => "true",
                "customer.givenName" => $payment?->patient?->name,
                "customer.surname" => ""
            ];
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
        ]);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, env('SSL_VERIFYPEER'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        return json_decode($responseData);
    }
}
