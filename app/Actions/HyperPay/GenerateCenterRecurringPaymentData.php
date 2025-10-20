<?php

namespace App\Actions\HyperPay;

use App\Traits\HelperTrait;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateCenterRecurringPaymentData
{
    use AsAction, HelperTrait;


    public function handle($package, $payment, $paymentBrand=null)
    {
        $url = env('RYD_HYPERPAY_URL')."/checkouts";

        $entity_id = env('RYD_ENTITY_ID'); //visa or master
        $access_token = env('RYD_AUTH_TOKEN');

        /*$paymentMethod = strtoupper(request()->brand);
        if(request()->brand == 'MADA')
        {
            $entity_id = env('RYD_ENTITY_ID_MADA'); //mada
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
                $access_token = config('hyperpay.ryd_apple_pay_token');
        }*/


        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);
        $unique_transaction_id = $timestamp . str_replace('.', '', $micro_time);
        $unique_transaction_id = $payment->id.'-'. $unique_transaction_id;
        $unique_agreement_id = $payment->id.'-'. $unique_transaction_id.rand(10,9999);

        $customer_email = $payment?->patient?->email ?? $this->sanitizeUsername($payment?->patient?->name);
        $billing_street1 = $payment?->patient?->city ?? '123 Test Street';
        $billing_city = $payment?->patient?->city ?? 'Jeddah';
        $billing_state = $payment?->patient?->city ?? 'JED';
        $billing_country = 'SA';
        $billing_postcode = '22230';
        $customer_given_name = $payment?->patient?->name ?? 'John';
        $customer_surname = 'Doe';
        $customer_mobile = $this->formatMobile($payment?->patient?->mobile_number ?? '0555555555');

        $data = [
            'entityId' => $entity_id,
            'amount' => $package->first_inst,
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'createRegistration' => 'true',
            'standingInstruction.type' => 'UNSCHEDULED',
            'standingInstruction.mode' => 'INITIAL',
            'standingInstruction.source' => 'CIT',
            'standingInstruction.expiry' => '2030-12-31',
            'standingInstruction.frequency' => '0030', // 30 days between payments
            'standingInstruction.numberOfInstallments' => '99',
            'standingInstruction.recurringType' => 'SUBSCRIPTION', // Fixed amount
            'customParameters[paymentFrequency]' => 'OTHER',
            'customParameters[recurringPaymentAgreement]' => $unique_agreement_id,
            'merchantTransactionId' => $unique_transaction_id,
            "customer.email" => $customer_email,
            "billing.street1" => $billing_street1,
            "billing.city" => $billing_city,
            "billing.state" => $billing_state,
            "billing.country" => $billing_country,
            "billing.postcode" => $billing_postcode,
            "integrity" => "true",
            "customer.givenName" => $customer_given_name,
            "customer.surname" => $customer_surname,
            "customer.mobile" => $customer_mobile
        ];

        if (env('VERSION_STATE') == 'STAGING_'){
            $data = [
                'entityId' =>$entity_id,
                'amount' => $package->first_inst,
                'currency' => 'SAR',
                'paymentType' => 'DB',
                'createRegistration' => 'true',
                'standingInstruction.type' => 'UNSCHEDULED',
                'standingInstruction.mode' => 'INITIAL',
                'standingInstruction.source' => 'CIT',
                'standingInstruction.expiry' => '2030-12-31',
                'standingInstruction.frequency' => '0030',
                'standingInstruction.numberOfInstallments' => '99',
                'standingInstruction.recurringType' => 'SUBSCRIPTION',
                'customParameters[paymentFrequency]' => 'OTHER',
                'customParameters[recurringPaymentAgreement]' => $unique_agreement_id,
                'merchantTransactionId' => $unique_transaction_id,
                "customer.email" => $customer_email,
                "billing.street1" => $billing_street1,
                "billing.city" => $billing_city,
                "billing.state" => $billing_state,
                "billing.country" => $billing_country,
                "billing.postcode" => $billing_postcode,
                "integrity" => "true",
                "customer.givenName" => $customer_given_name,
                "customer.surname" => $customer_surname,
                "customer.mobile" => $customer_mobile
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
