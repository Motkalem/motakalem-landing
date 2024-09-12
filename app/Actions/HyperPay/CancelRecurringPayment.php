<?php

namespace App\Actions\HyperPay;

use App\Models\InstallmentPayment;
use App\Models\Package;
use Lorisleiva\Actions\Concerns\AsAction;

class CancelRecurringPayment
{
    use AsAction;

    public function handle($id)
    {

        $url = "https://eu-prod.oppwa.com/scheduling/v1/schedules/".$id;
        $url .= "?entityId=".env('RECURRING_ENTITY_ID');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer '.env('AUTH_TOKEN')));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);

        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        notify()->success('تم الغاء الاشتراك  .');

        $InstallmentPayment = InstallmentPayment::where('payment_id', $id)->first();
        $InstallmentPayment->update(['canceled'=>1]);
        return back();
    }
}
