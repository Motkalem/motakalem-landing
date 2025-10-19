<?php

namespace App\Actions\HyperPay;

use App\Traits\HelperTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreRecurringPaymentData
{
    use AsAction, HelperTrait;
    public function handle($package, $payment, $paymentBrand=null)
    {
        $url = env('SNB_HYPERPAY_URL')."/checkouts";

        $access_token = env('SNB_AUTH_TOKEN');

        $entity_id = env('SNB_ENTITY_ID'); //visa or master

        /*$paymentMethod = strtoupper(request()->brand);

        if($paymentMethod == 'MADA')
        {
            $entity_id = env('SNB_ENTITY_ID_MADA'); //mada
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.snb_entity_id_apple_pay');
            $access_token = config('hyperpay.snb_apple_pay_token');
        }*/

        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);
        $unique_transaction_id = $timestamp . str_replace('.', '', $micro_time);
        $unique_transaction_id = $payment->id.'-'. $unique_transaction_id;

        Log::debug('Initial Agreement ID', ['agreement' => $unique_transaction_id]);

        $payment->update([
            'recurring_agreement_id' => $unique_transaction_id
        ]);

        $customer_email = $payment?->student?->email ?? $this->sanitizeUsername($payment?->student?->name);
        $billing_street1 = $payment?->student?->city ?? '123 Test Street';
        $billing_city = $payment?->student?->city ?? 'Jeddah';
        $billing_state = $payment?->student?->city ?? 'JED';
        $billing_country = 'SA';
        $billing_postcode = '22230';
        $customer_given_name = $payment?->student?->name ?? 'John';
        $customer_surname = 'Doe';


        $data = [
            'entityId' => $entity_id,
            'amount' => $package->first_inst,
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'createRegistration' => 'true',
            'standingInstruction.type' => 'UNSCHEDULED',
            'standingInstruction.mode' => 'INITIAL',
            'standingInstruction.source' => 'CIT',
            'standingInstruction.expiry' => \Carbon\Carbon::now()->addYear()->format('Y-m-d'),
            'standingInstruction.frequency' => '0030', // 30 days between payments
            'standingInstruction.numberOfInstallments' => '99',
            'standingInstruction.recurringType' => 'SUBSCRIPTION', // For fixed amount
            'customParameters[paymentFrequency]' => 'OTHER',
            'customParameters[recurringPaymentAgreement]' => $unique_transaction_id,
            'merchantTransactionId' => $unique_transaction_id,
            "customer.email" => $customer_email,
            "billing.street1" => $billing_street1,
            "billing.city" => $billing_city,
            "billing.state" => $billing_state,
            "billing.country" => $billing_country,
            "billing.postcode" => $billing_postcode,
            "integrity" => "true",
            "customer.givenName" => $customer_given_name,
            "customer.surname" => $customer_surname
        ];

        Log::debug('Data Initial Agreement', [$data]);

        if (env('VERSION_STATE') == 'STAGING_'){
            $data = [
                'entityId' => env('SNB_ENTITY_ID'),
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
                'standingInstruction.recurringType' => 'SUBSCRIPTION', // For fixed amount
                'customParameters[paymentFrequency]' => 'OTHER',
                'customParameters[recurringPaymentAgreement]' => $unique_transaction_id.rand(1,2000),
                'merchantTransactionId' => $unique_transaction_id,
                "customer.email" => $customer_email,
                "billing.street1" => $billing_street1,
                "billing.city" => $billing_city,
                "billing.state" => $billing_state,
                "billing.country" => $billing_country,
                "billing.postcode" => $billing_postcode,
                "integrity" => "true",
                "customer.givenName" => $customer_given_name,
                "customer.surname" => $customer_surname
            ];
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token ,
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
