<?php

namespace App\Actions\HyperPay;

use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use App\Notifications\Admin\HyperPayNotification;
use App\Notifications\SendContractNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Notifications\SentPaymentUrlNotification;


class RecurringCheckoutResultAction
{
    use AsAction;

    /**
     * @param ActionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(ActionRequest $request)#: JsonResponse|int
    {

        $url = env('SNB_HYPERPAY_WIDGET_URL') . $request->resourcePath;

        $response = Http::withoutVerifying()->get($url);

        $data = $response->json();

        $installmentPayment = InstallmentPayment::query()->with('student')->find($request->paymentId);

        $webHookNotification = $this->createWebHookNotification($data, $installmentPayment);

        if ($response->successful() && $this->isSuccessfulNotification($webHookNotification)) {

            $registrationId = data_get($data, 'registrationId');

            $installmentPayment?->update([
                'registration_id' => $registrationId,
                'first_installment_date' => now()
            ]);

            $installmentPayment->student?->update([
                'package_id' => $installmentPayment->package_id,
                'is_paid' => 1,
            ]);

            ## Mark first installment as paid
           $firstInstallment = $this->markFirstInstallmentAsPaid($installmentPayment);

           $webHookNotification->update(['installment_id' => $firstInstallment->id]);


            ## NOTIFICATION
            $this->notifyAdmin($webHookNotification);
            $this->sendContract($installmentPayment?->student?->parentContract);
            $this->notifyStudent($webHookNotification, $installmentPayment->student?->email);


           return Redirect::away(env(env('VERSION_STATE') . 'FRONT_URL') . '/one-step-closer?status=success');
        } else {

            $payment_url = route('recurring.checkout', [
                'paymentId' => $installmentPayment?->id,
                'stdId' => $installmentPayment?->student?->id
            ]);

            Notification::route('mail', $installmentPayment?->student?->email)
                ->notify(new SentPaymentUrlNotification($installmentPayment?->student, $payment_url));

            return Redirect::away(env(env('VERSION_STATE') . 'FRONT_URL') . '/one-step-closer?status=fail');
        }

    }

    /**
     * @param $row
     * @return void
     */
    public function sendContract($row): void
    {
        try {
            $row = $row->load('package');
            Notification::route('mail', $row->email)->notify(new SendContractNotification($row));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function markFirstInstallmentAsPaid($installmentPayment)
    {

         $firstInstallment = $installmentPayment->installments?->first();

        $firstInstallment?->update(['is_paid' => 1]);

        return $firstInstallment;
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

                $result = $this->isSuccessfulNotification($notification) ? "تمت المعاملة بنجاح !" : "فشلت العملية !";

                Notification::route('mail', $adminEmail)->notify(new HyperPayNotification($notification, $result));
                $notification->update(['admin_notified' => 1]);
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

            $result = $this->isSuccessfulNotification($notification) ? "تمت المعاملة بنجاح !" : "فشلت العملية !";

            Notification::route('mail', $email)->notify(new HyperPayNotification($notification, $result));

            $notification->update(['student_notified' => 1]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
        }
    }

}
