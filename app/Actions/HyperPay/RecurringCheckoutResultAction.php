<?php

namespace App\Actions\HyperPay;

use App\Models\InstallmentPayment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RecurringCheckoutResultAction
{
    use AsAction;

    public function handle(ActionRequest $request): JsonResponse|int
    {

        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $request->resourcePath . "/payment";

        $response = Http::withoutVerifying()->get($url);

        #TODO GET THE FORMAT OF SUCCESS RESPONSE AND EXTRACT THE REGISTRATION ID
        if ($response->successful()) {

           $data = $response->json();
            $registrationId = $data['id']??'8acda4a0922e592f019238cc13433a95';
            $installmentPayment = InstallmentPayment::query()
                ->find($request->paymentId)?->update(['registration_id'=> $registrationId]);

        } else {
               return response()->json([
                'error' => 'Failed to retrieve payment status',
                'status_code' => $response->status(),
                'response_body' => json_decode($response->body())
            ], $response->status());
        }
    }
}
