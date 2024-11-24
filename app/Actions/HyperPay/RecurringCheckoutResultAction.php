<?php

namespace App\Actions\HyperPay;

use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use App\Notifications\Admin\HyperPayNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        $this->notifyAdmin($webHookNotification);

        $this->notifyStudent($webHookNotification, $installmentPayment->student?->email);

        if ($response->successful() &&  $this->isSuccessfulNotification($webHookNotification)) {

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
     * @param $notification
     * @return bool
     */
    protected function isSuccessfulNotification($notification): bool
    {
        $resultCode = data_get($notification->payload, 'result.code');
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

        return preg_match($successPattern, $resultCode) === 1;
    }

    /**
     * @param $response
     * @param $installment
     * @return Builder|Model
     */
    public function createWebHookNotification($response, $installment): Model|Builder
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
     * @param $notification
     * @return void
     */
    protected function notifyAdmin($notification): void
    {
        try {
            $adminEmails = explode(',', env('ADMIN_EMAILS'));

            foreach ($adminEmails as $adminEmail) {

                Notification::route('mail', $adminEmail) ->notify(new HyperPayNotification($notification));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param $notification
     * @param $email
     * @return void
     */
    protected function notifyStudent($notification, $email): void
    {
        try {

            Notification::route('mail', $email)->notify(new HyperPayNotification($notification));
        } catch (\Exception $e) {

            Log::error($e->getMessage());
        }
    }

}
