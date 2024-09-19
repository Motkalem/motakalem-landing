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
            'entityId' => $this->entityId,
            'amount' => $amount??5,
            'currency' => $currency,
            'paymentType' => $paymentType,
            'customer' => [
                'id' => $customerId,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->post($url, $data);

        return response()->json($response->json());
    }

    public function executeRecurringPayment(Request $request)
    {
        $registrationId = $request->input('registrationId');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $url = "https://test.oppwa.com/v1/registrations/{$registrationId}/payments";
        $data = [
            'entityId' => $this->entityId,
            'amount' => $amount,
            'currency' => $currency,
            'paymentType' => 'DB',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->post($url, $data);

        return response()->json($response->json());
    }
}
