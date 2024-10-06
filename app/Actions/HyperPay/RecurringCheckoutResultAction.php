<?php

namespace App\Actions\HyperPay;

use App\Models\InstallmentPayment;
use App\Notifications\SuccessSubscriptionPaidNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RecurringCheckoutResultAction
{
    use AsAction;

    public function handle(ActionRequest $request)#: JsonResponse|int
    {

        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $request->resourcePath . "/payment";

        $response = Http::withoutVerifying()->get($url);

        #TODO GET THE FORMAT OF SUCCESS RESPONSE AND EXTRACT THE REGISTRATION ID

        if ($response->successful()) {

           $data = $response->json();
            $registrationId = $data['id']??'8acda4a0922e592f019238cc13433a95';

            $installmentPayment = InstallmentPayment::query()
                ->find($request->paymentId)?->update([
                    'registration_id'=> $registrationId,
                    'first_installment_date'=>now()
                ]);

            return  Redirect::away('https://www.motkalem.com/one-step-closer'.'?'.'status=success');

        } else {
            return  Redirect::away('https://www.motkalem.com/one-step-closer'.'?'.'status=failed');

        }
    }

    /**
     * @param $client
     * @param $transaction
     * @return void
     */
    public function notifyClient($client, $transaction): void
    {
        Notification::send($client,
            new SuccessSubscriptionPaidNotification($client, $transaction));

    }
}
