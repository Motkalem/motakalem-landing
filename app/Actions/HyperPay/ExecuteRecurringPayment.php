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
        $amount = 5.00;
        $currency = 'SAR';

        $url = "https://eu-prod.oppwa.com/v1/registrations/{$registrationId}/payments";
        $data = [
            'entityId' => env('RECURRING_ENTITY_ID'),
            'amount' => $amount,
            'currency' => $currency,
            'paymentType' => 'DB',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .env('AUTH_TOKEN'),
        ])->post($url, $data);

        return response()->json($response->json());
    }

}
