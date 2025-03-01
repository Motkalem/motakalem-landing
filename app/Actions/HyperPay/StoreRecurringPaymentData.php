<?php

namespace App\Actions\HyperPay;

use Lorisleiva\Actions\Concerns\AsAction;

class StoreRecurringPaymentData
{
    use AsAction;
    public function handle($package, $payment, $student, $data)
    {
        $url = env('SNB_HYPERPAY_URL')."/checkouts";

        $data = [
            'entityId' => env('SNB_ENTITY_ID'),
            'amount' => $package->first_inst,
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'createRegistration' => 'true',
            'standingInstruction.type' => 'UNSCHEDULED',
            'standingInstruction.mode' => 'INITIAL',
            'standingInstruction.source' => 'CIT',
            'merchantTransactionId' => $payment->id,
            "customer.email"=>$payment?->student?->email,
            "billing.street1"=>$payment?->student?->city ,
            "billing.city"=>$payment?->student?->city ,
            "billing.state"=>$payment?->student?->city  ,
            "billing.country"=>"SA",
            "billing.postcode"=>"",
            "customer.givenName"=>$payment?->student?->name,
            "customer.surname"=>""
        ];

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

            'testMode'=> 'EXTERNAL',
            'merchantTransactionId' => $payment->id.'-'.microtime(),
            "customer.email"=>$payment?->student?->email,
            "billing.street1"=>$payment?->student?->city ,
            "billing.city"=>$payment?->student?->city ,
            "billing.state"=>$payment?->student?->city  ,
            "billing.country"=>"SA",
            "billing.postcode"=>"",
            "customer.givenName"=>$payment?->student?->name,
            "customer.surname"=>""
        ];
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . env('SNB_AUTH_TOKEN'),
        ]);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        return json_decode($responseData);
    }
}
