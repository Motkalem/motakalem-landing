<?php

namespace App\Http\Controllers\Dashboard\Payment;

use App\Classes\HyperpayNotificationProcessor;
use App\Http\Controllers\Controller;
use App\Models\HyperpayWebHooksNotification;
use App\Models\Center\CenterInstallment;
use App\Models\Payment;
use App\Traits\HelperTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\SentPaymentUrlNotification;

class CenterPayInstallmentController extends Controller
{
    use HelperTrait;

    public function getPayPage()
    {

        $installment = CenterInstallment::with('centerInstallmentPayment')->find(request()->instId);

        if (request()->has('brand')) {

            $responseData = $this->createCheckoutId($installment);
            $checkoutId = data_get(json_decode($responseData), "id");
            $integrity = data_get(json_decode($responseData), "integrity");

        } else {

            $checkoutId = null;
            $integrity = null;
        }

        $nonce = bin2hex(random_bytes(16));
        return view('payments.pay-center-installment', compact('installment', 'checkoutId', 'integrity', 'nonce'));
    }


    /**
     * @param $payment
     * @return bool|string
     */
    public function createCheckoutId($installment): bool|string
    {

        $entity_id = env('RYD_ENTITY_ID'); //visa or master

        $paymentMethod = strtoupper(request()->brand);

        if($paymentMethod == 'MADA')
        {
            $entity_id = env('RYD_ENTITY_ID_MADA'); //mada
        }

        $access_token = env('RYD_AUTH_TOKEN');

        $url = env('RYD_HYPERPAY_URL')."/checkouts";

        if($paymentMethod == 'TABBY'){

            $entity_id = env('RYD_ENTITY_ID_MADA'); //mada
            $access_token = env('RYD_AUTH_TOKEN');
            $url = env('RYD_HYPERPAY_URL')."/checkouts";
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
            $access_token = config('hyperpay.ryd_apple_pay_token');
        }

        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);
        $unique_transaction_id = $timestamp . str_replace('.', '', $micro_time);
        $unique_transaction_id = $installment->id.'-'. $unique_transaction_id;

        $data = "entityId=".$entity_id .
        "&amount=".$installment?->installment_amount.
        "&currency=SAR".
        "&paymentType=DB".
        "&integrity=true".
        "&merchantTransactionId=".$unique_transaction_id .
        "&customer.email=".$installment?->centerInstallmentPayment?->patient?->email.
        "&billing.street1=".$installment?->centerInstallmentPayment?->patient?->city .
        "&billing.city=".$installment?->centerInstallmentPayment?->patient?->city .
        "&billing.state=".$installment?->centerInstallmentPayment?->patient?->city .
        "&billing.country="."SA".
        "&billing.postcode="."22230".
        "&customer.givenName=".$installment?->centerInstallmentPayment?->patient?->name.
        "&customer.surname=Doe" .
        "&customer.mobile=" . $this->formatMobile($installment?->centerInstallmentPayment?->patient?->phone ?? '0555555555');


//        if(request()->brand == 'tabby')
//        {
//            $data .="
//                &cart.items[0].name=item1".
//                "&cart.items[0].sku=15478".
//                "&cart.items[0].price=".$installment?->installmentPayment?->installments?->sum('installment_amount').
//                "&cart.items[0].quantity=1".
//                "&cart.items[0].description=test1".
//                "&cart.items[0].productUrl=http://url1.com";
//        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization:Bearer ' . $access_token)
        );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, env('SSL_VERIFYPEER'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        return $responseData;
    }



    public function getStatus()
    {
        $entity_id = env('RYD_ENTITY_ID');

        $access_token = env('RYD_AUTH_TOKEN');

        if(request()->paymentMethod == 'APPLEPAY') {

            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
            $access_token = config('hyperpay.ryd_apple_pay_token');
        }

        if(request()->paymentMethod == 'MADA')
        {
            $entity_id = env('RYD_ENTITY_ID_MADA');
        }


        $url = env('RYD_HYPERPAY_URL')."/checkouts/" . $_GET['id'] . "/payment";

        $url .= "?entityId=" . $entity_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, env('SSL_VERIFYPEER'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {

            return curl_error($ch);
        }

        curl_close($ch);

       $response  = $responseData;

         $res = new HyperpayNotificationProcessor( $response);

        $title =  $res->processNotification()   ;

        $data = (array) json_decode($responseData);

        $centerInstallment = CenterInstallment::query()->find(request()->instId);

        $transactionData = array_merge($data, [

            'student_id' => $centerInstallment->centerInstallmentPayment?->patient_id,
            'payment_id' =>  $centerInstallment->centerInstallmentPayment?->id,
            'title' =>  $title
        ]);

        $transaction = $this->storeNotification($transactionData, $centerInstallment->centerInstallmentPayment,   $centerInstallment );

        $isSuccessful = $this->isSuccessfulResponse(data_get( data_get($transactionData,'result'), 'code'));

       if( data_get($transactionData, 'id') ==  null)
       {

           return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=fail');
       }

        $this->markInstallmentAsCompleted($centerInstallment,  $centerInstallment->installmentPayment, $isSuccessful);

        if ($isSuccessful == 'true') {

            return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=success');
        } else {

            return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=fail');
        }
    }

    private function isSuccessfulResponse(?string $resultCode): bool
    {
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';
        return preg_match($successPattern, $resultCode) === 1;
    }


    public function storeNotification($response, $centerInstallmentPayment, $installment)
    {


        \App\Models\Center\CenterTransaction::query()->create([
            'title' => data_get($response, 'result.description'),
            'center_installment_payment_id' => $centerInstallmentPayment->id,
            'success' => in_array(data_get($response, 'result.code'), ['000.100.110', '000.000.000']) ? 'true' : 'false',
            'amount' => data_get($response, 'amount') ?? 0.0,
            'data' => $response,
        ]);

      return  $notification = HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'center_installment_payment_id' => $centerInstallmentPayment->id,
            'installment_id' => $installment->id,
            'type' => 'pay installment with link',
            'payload' => $response,
            'log' => $response,
        ]);
    }
    /**
     * @param $payment
     * @return void
     */
    private function markInstallmentAsCompleted($installment, $payment=null, $isSuccessful)
    {
        if ($isSuccessful == 'true')
             {
                 $installment->update(['is_paid' => true, 'paid_at'=> now(), 'paid_type'=> 'payment link']);
            }
    }


}
