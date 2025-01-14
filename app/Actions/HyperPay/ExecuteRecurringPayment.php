<?php

namespace App\Actions\HyperPay;

use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class ExecuteRecurringPayment
{
    use AsAction;

    public function handle($registrationID = null)
    {
        $registrationId = $registrationID != null ? $registrationID : request()->registrationId;

        $installment = InstallmentPayment::where('registration_id', $registrationId)->first();

        $amount = $installment->package?->first_inst;

        $url = env('HYPERPAY_URL') . "/registrations/" . $registrationId . "/payments";

        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=".$amount .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&standingInstruction.mode=REPEATED" .
            "&standingInstruction.type=UNSCHEDULED" .
            "&standingInstruction.source=MIT" .
            "&shopperResultUrl=".env(env('VERSION_STATE').'FRONT_URL');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . env('AUTH_TOKEN')));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $response = json_decode($responseData);

        if (request()->registrationId) { # to avoid duplicate store notification from cron job

            $installmentPayment = InstallmentPayment::where('registration_id', request()->registrationId)->first();
            $this->storeNotification($response, $installmentPayment);
        }

        return $response;
    }

    public function storeNotification($response, $installment)
    {
        $notification = HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installment->id,
            'type' => 'execute recurring payment',
            'payload' => $response,
            'log' => $response,
        ]);
    }
}
