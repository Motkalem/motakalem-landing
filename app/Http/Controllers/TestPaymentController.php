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
        $student = $request->user();  // Assuming the student is the authenticated user
        $package = $request->package; // Assuming the package comes from the request or related model
        $payment = $request->payment; // Payment object from the request
        $data = $request->all(); // All request data

        // Construct the URL
        $url = "https://eu-prod.oppwa.com/v1/registrations";

        // Build the data string for the request
        $postData = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=" . $package->installment_value .
            "&paymentType=DB" .
            "&shopperResultUrl=" . url('/') .
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

        // Make the POST request with the concatenated string
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . 'OGFjZGE0Yzk5MWUwNTc0YjAxOTFlMGE1ZjU2MzA2Zjh8S25wc0xTaHM0YVlmK2o0PU01b1U',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post($url, $postData);

        return $response->json();
    }

    public function executeRecurringPayment(Request $request)
    {

        $registrationId = 'REGISTRATION_ID_FROM_INITIAL_PAYMENT';

        $url = "https://eu-prod.oppwa.com/v1/registrations/{$registrationId}/payments";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer OGFjZGE0Yzk5MWUwNTc0YjAxOTFlMGE1ZjU2MzA2Zjh8S25wc0xTaHM0YVlmK2o0PU01b1U',
        ])->asForm()->post($url, [
            'entityId' => '8acda4c991e0574b0191e0b39afe0790',
            'amount' => '5.00',
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'recurringType' => 'REPEATED',
        ]);

        return $response->json();


    }
}
