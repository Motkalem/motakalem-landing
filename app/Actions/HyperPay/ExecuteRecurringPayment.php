<?php

namespace App\Actions\HyperPay;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;


class ExecuteRecurringPayment
{
    use AsAction;

    public function handle()
    {
        $registrationId = request()->registrationId;
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
