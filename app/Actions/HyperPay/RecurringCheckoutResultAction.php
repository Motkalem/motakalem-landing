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

    /**
     * @param ActionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(ActionRequest $request)#: JsonResponse|int
    {

          $url = env('HYPERPAY_WIDGET_URL') . $request->resourcePath ;

          $response = Http::withoutVerifying()->get($url);


        if ($response->successful()) {

           $data = $response->json();

            $registrationId = data_get($data,'registrationId') ;

            $installmentPayment = InstallmentPayment::query()
                ->find($request->paymentId)?->update([
                    'registration_id'=> $registrationId,
                    'first_installment_date'=>now()
                ]);

            return  Redirect::away('https://staging-front.motkalem.com/one-step-closer'.'?'.'status=success');

        } else {
            return  Redirect::away('https://staging-front.motkalem.com/one-step-closer'.'?'.'status=failed');

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
