<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestPaymentController extends Controller
{
    protected $entityId;
    protected $accessToken;

    public function __construct()
    {
        $this->entityId = config('payments.gateways.card.entity_id');
        $this->accessToken = config('payments.gateways.card.access_token');
    }

    public function createRecurringPayment(Request $request)
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $paymentType = $request->input('paymentType');
        $customerId = $request->input('customerId');

        $url = "https://test.oppwa.com/v1/registrations";
        $data = [
            'entityId' => '8acda4c991e0574b0191e0b39afe0790',
            'amount' => $amount ?? 5,
            'currency' => $currency,
            'paymentType' => $paymentType,
            'customer' => [
                'id' => $customerId,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . 'OGFjZGE0Yzk5MWUwNTc0YjAxOTFlMGE1ZjU2MzA2Zjh8S25wc0xTaHM0YVlmK2o0PU01b1U',
        ])->post($url, $data);

        return response()->json($response->json());
    }

    public function executeRecurringPayment(Request $request)
    {

        $registrationId = $request->input('registrationId');
        $amount = 5.00;
        $currency = 'SAR';

        $url = "https://eu-prod.oppwa.com/v1/registrations/{$registrationId}/payments";

        // Concatenate parameters into the URL
        $url .= '?entityId=8acda4c991e0574b0191e0b39afe0790';
        $url .= '&amount=' . urlencode($amount);
        $url .= "&shopperResultUrl=https://motkalem.com/";
        $url .= '&currency=' . urlencode($currency);
        $url .= '&paymentType=DB';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . 'OGFjZGE0Yzk5MWUwNTc0YjAxOTFlMGE1ZjU2MzA2Zjh8S25wc0xTaHM0YVlmK2o0PU01b1U',
        ])->post($url);

        return response()->json($response->json());
    }
}
