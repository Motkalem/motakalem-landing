<?php

namespace App\Http\Controllers\Dashboard\Payment;

use App\Classes\HyperpayNotificationProcessor;
use App\Http\Controllers\Controller;
use App\Models\HyperpayWebHooksNotification;
use App\Models\Installment;
use App\Models\Package;
use App\Models\ParentContract;
use App\Models\Payment;
use App\Models\Transaction;
use App\Notifications\Admin\NewSubscriptionNotification;
use App\Notifications\SendContractNotification;
use App\Notifications\SuccessSubscriptionPaidNotification;
use App\Traits\HelperTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Notifications\SentPaymentUrlNotification;

class PayInstallmentController extends Controller
{
    use HelperTrait;

    public function getPayPage()
    {

        $installment = Installment::with('installmentPayment')->find(request()->instId);

        $brand = strtoupper(request()->brand);

        if ($brand == 'APPLEPAY') {
            $brands = $brand;
        } else {
            $brands = 'VISA MASTER MADA';
        }

        if (request()->has('brand')) {

            $responseData = $this->createCheckoutId($installment);
            $checkoutId = data_get(json_decode($responseData), "id");
            $integrity = data_get(json_decode($responseData), "integrity");

        } else {
            $checkoutId = null;
            $integrity = null;
        }

        $nonce = bin2hex(random_bytes(16));
        return view('payments.pay-installment', compact('installment', 'checkoutId', 'integrity', 'nonce','brands'));
    }


    /**
     * @param $payment
     * @return bool|string
     */
    public function createCheckoutId($installment): bool|string
    {

        $entity_id = env('SNB_ENTITY_ID'); //visa or master
        $access_token = env('SNB_AUTH_TOKEN');
        $url = env('SNB_HYPERPAY_URL')."/checkouts";

        $paymentMethod = strtoupper(request()->brand);
        /*if($paymentMethod == 'MADA')
        {
            $entity_id = env('SNB_ENTITY_ID_MADA'); //mada
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.snb_entity_id_apple_pay');
            $access_token = config('hyperpay.snb_apple_pay_token');
        }*/

        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);
        $unique_transaction_id = $timestamp . str_replace('.', '', $micro_time);
        $unique_transaction_id = $installment->id.'-'. $unique_transaction_id;

        $customer_email = $installment?->installmentPayment?->student?->email ?? $this->sanitizeUsername($installment?->installmentPayment?->student?->name);
        $billing_street1 = $installment?->installmentPayment?->student?->city ?? '123 Test Street';
        $billing_city = $installment?->installmentPayment?->student?->city ?? 'Jeddah';
        $billing_state = $installment?->installmentPayment?->student?->city ?? 'JED';
        $billing_country = 'SA';
        $billing_postcode = '22230';
        $customer_given_name = $installment?->installmentPayment?->student?->name ?? 'John';
        $customer_surname = 'Doe';
        $customer_mobile = $this->formatMobile($installment?->installmentPayment?->student?->phone);

        $data = "entityId=".$entity_id .
            "&amount=".$installment?->installment_amount.
            "&currency=SAR".
            "&paymentType=DB".
            "&integrity=true".
            "&merchantTransactionId=".$unique_transaction_id .
            "&customer.email=".$customer_email.
            "&billing.street1=".$billing_street1 .
            "&billing.city=".$billing_city .
            "&billing.state=".$billing_state.
            "&billing.country=".$billing_country.
            "&billing.postcode=".$billing_postcode.
            "&customer.givenName=".$customer_given_name.
            "&customer.surname=".$customer_surname.
            "&customer.mobile=".$customer_mobile;

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

        $entity_id = env('SNB_ENTITY_ID');
        $access_token = env('SNB_AUTH_TOKEN');

        /*if(request()->paymentMethod == 'MADA')
        {
            $entity_id = env('SNB_ENTITY_ID_MADA'); //mada
        }

        if(request()->paymentMethod == 'APPLEPAY') {
            $entity_id = config('hyperpay.snb_entity_id_apple_pay');
            $access_token = config('hyperpay.snb_apple_pay_token');
        }*/

        $url = env('SNB_HYPERPAY_URL')."/checkouts/" . $_GET['id'] . "/payment";

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

        $installment = Installment::query()->find(request()->instId);

        $transactionData = array_merge($data, [

            'student_id' => $installment->installmentPayment?->student_id,
            'payment_id' =>  $installment->installmentPayment?->id,
            'title' =>  $title
        ]);

        $transaction = $this->storeNotification($transactionData, $installment->installmentPayment,   $installment );

        $isSuccessful = $this->isSuccessfulResponse(data_get( data_get($transactionData,'result'), 'code'));


        $this->markInstallmentAsCompleted($installment,  $installment->installmentPayment, $isSuccessful);

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


    public function storeNotification($response, $installmentPayment, $installment)
    {


        return  $notification = HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installmentPayment->id,
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
