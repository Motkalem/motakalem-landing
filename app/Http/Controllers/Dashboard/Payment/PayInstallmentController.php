<?php

namespace App\Http\Controllers\Dashboard\Payment;

use App\Classes\HyperpayNotificationProcessor;
use App\Http\Controllers\Controller;
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

        $responseData = $this->createCheckoutId($installment);

        $checkoutId = data_get(json_decode($responseData), "id");
        $integrity = data_get(json_decode($responseData), "integrity");
        $nonce = bin2hex(random_bytes(16));

        return view('payments.pay-installment', compact('installment', 'checkoutId', 'integrity', 'nonce'));
    }


    /**
     * @param $payment
     * @return bool|string
     */
    public function createCheckoutId($installment): bool|string
    {

        $entity_id = env('SNB_ENTITY_ID'); //visa or master

        $paymentMethod = strtoupper(request()->brand);

        if($paymentMethod == 'MADA')
        {
            $entity_id = env('SNB_ENTITY_ID_MADA'); //mada
        }

        $access_token = env('SNB_AUTH_TOKEN');
        $url = env('SNB_HYPERPAY_URL')."/checkouts";

        if($paymentMethod == 'TABBY'){

            $entity_id = env('RYD_ENTITY_ID_MADA'); //mada
            $access_token = env('RYD_AUTH_TOKEN');
            $url = env('RYD_HYPERPAY_URL')."/checkouts";
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.snb_entity_id_apple_pay');
            $access_token = config('hyperpay.snb_apple_pay_token');
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
        "&customer.email=".$installment?->installmentPayment?->student?->email.
        "&billing.street1=".$installment?->installmentPayment?->student?->city .
        "&billing.city=".$installment?->installmentPayment?->student?->city .
        "&billing.state=".$installment?->installmentPayment?->student?->city .
        "&billing.country="."SA".
        "&billing.postcode="."22230".
        "&customer.givenName=".$installment?->installmentPayment?->student?->name.
        "&customer.surname=Doe" .
        "&customer.mobile=" . $this->formatMobile($installment?->installmentPayment?->student?->phone);


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


    /**
     * @return string|RedirectResponse
     */
    public function getStatus(): string|RedirectResponse
    {
        $entity_id = env('SNB_ENTITY_ID');
        $access_token = env('SNB_AUTH_TOKEN');

        if(request()->paymentMethod == 'APPLEPAY') {

            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
            $access_token = config('hyperpay.RYD_APPLE_PAY_ACCESS_TOKEN');
        }

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

        $transaction = $this->createTransactions($transactionData,    $installment->installmentPayment);


       if( data_get($transactionData, 'id') ==  null)
       {
            $payment_url = route('checkout.index') . '?sid=' . request()->studentId . '&pid=' . request()->paymentId;
            Notification::route('mail', $installment->installmentPayment?->student?->email)->notify(new SentPaymentUrlNotification($payment->student, $payment_url));

           return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=fail');

       }

        $this->markInstallmentAsCompleted($installment,  $installment->installmentPayment);

        if ($transaction->success == 'true') {

            return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=success');
        } else {

            // Send payment URL via email on failure
            $payment_url = route('checkout.index') . '?sid=' . request()->studentId . '&pid=' . request()->paymentId;

            Notification::route('mail', $payment->student->email)->notify(new SentPaymentUrlNotification($payment->student, $payment_url));

            return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=fail');
        }
    }

    /**
     * @param $data
     * @param $payment
     * @return mixed
     */
    public function createTransactions($data, $payment=null): mixed
    {

        return  Transaction::query()->create([
            'data' => $data,
            'title' => data_get($data, 'title'),
            'transaction_id' => data_get($data, 'id'),
            'student_id' => data_get($data, 'student_id'),
            'payment_id' => data_get($data, 'payment_id'),
            'amount' => data_get($data, 'amount')??0.0,
            'success' =>
           in_array(data_get(data_get($data, 'result'), 'code'),
               ['000.100.110','000.000.000'])  ? 'true' : 'false',
        ]);
    }

    /**
     * @param $payment
     * @return void
     */
    private function markInstallmentAsCompleted($installment, $payment=null)
    {

        $transaction = Transaction::where('payment_id', $payment->id)->latest()->first();

        if ($transaction->success == 'true')
             {
                 $installment->update(['is_paid' => true, 'paid_at'=> now(), 'paid_type'=> 'payment link']);
            }
    }


}
