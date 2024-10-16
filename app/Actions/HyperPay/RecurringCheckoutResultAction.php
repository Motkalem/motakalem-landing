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

        $this->createWebHookNotification($data, $installmentPayment);

        if ($response->successful()) {

            $registrationId = data_get($data, 'registrationId');

            $installmentPayment?->update([
                'registration_id' => $registrationId,
                'first_installment_date' => now()
            ]);
            $installmentPayment->student?->update([
                'package_id'=> $installmentPayment->package_id,
                'is_paid'=> 1,
            ]);

            return Redirect::away('https://staging-front.motkalem.com/one-step-closer' . '?' . 'status=success');

        } else {
            return Redirect::away('https://staging-front.motkalem.com/one-step-closer' . '?' . 'status=failed');

        }
    }

    /**
     * @param $response
     * @param $installment
     * @return void
     */
    public function createWebHookNotification($response, $installment)
    {

        $notification = HyperpayWebHooksNotification::query()->create([
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
