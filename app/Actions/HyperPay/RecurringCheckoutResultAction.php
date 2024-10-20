<?php

namespace App\Actions\HyperPay;

use App\Models\HyperpayWebHooksNotification;
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

        $url = env('HYPERPAY_WIDGET_URL') . $request->resourcePath;

        $response = Http::withoutVerifying()->get($url);

        $data = $response->json();

        $installmentPayment = InstallmentPayment::query()->with('student')->find($request->paymentId);

       $webHookNotification = $this->createWebHookNotification($data, $installmentPayment);

        $resultCode = data_get($webHookNotification->payload, 'result.code');

        if ($response->successful() && in_array($resultCode, ['000.100.112','000.000.000'])) {

            $registrationId = data_get($data, 'registrationId');

            $installmentPayment?->update([
                'registration_id' => $registrationId,
                'first_installment_date' => now()
            ]);
            $installmentPayment->student?->update([
                'package_id'=> $installmentPayment->package_id,
                'is_paid'=> 1,
            ]);

            return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=success');

        } else {
            return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=fail');

        }
    }

    /**
     * @param $response
     * @param $installment
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createWebHookNotification($response, $installment)
    {

      return HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installment->id,
            'type' => 'init recurring payment',
            'payload' => $response,
            'log' => $response,
        ]);
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
