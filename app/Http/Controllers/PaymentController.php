<?php

namespace App\Http\Controllers;

use App\Classes\HyperpayNotificationProcessor;
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

class PaymentController extends Controller
{
    use HelperTrait;

    public function getPayPage()
    {

        $brand = strtoupper(request()->brand);
        $pid = request()->pid;
        $sid = request()->sid;
        $payment = Payment::with('package','student')->find($pid);

        $responseData = null;

        if ($payment == null) {

            echo '<h3 style="text-align:center; padding:10px">عذرًا، لم نتمكن من العثور على دفعتك. يرجى التواصل معنا    </h1>';
            echo '</br>';
            echo "<p style='text-align:center'><a href=" . url('/') . ">" . url('/') . " </a></p>";
            die();
        }

        try {

            if ($payment->package?->payment_type == Package::ONE_TIME && $brand) {
                 $responseData = $this->createCheckoutId($payment);
            }
        } catch (\Throwable $th) {}


        $paymentId = data_get(json_decode($responseData), "id");

        $integrity = data_get(json_decode($responseData), "integrity");
        $nonce = bin2hex(random_bytes(16));


        if ($brand == 'APPLEPAY' || $brand == 'TABBY') {

            $brands = $brand;
            $actionSuffix = $brand;

        } else {
            $brands = 'VISA MASTER MADA';
            $actionSuffix = 'CARD';
        }

        return view('payments.one-time-pay-new', compact('payment', 'paymentId',
            'integrity', 'nonce','brands', 'brand','actionSuffix','pid','sid'));
    }

    /**
     * @param $payment
     * @return bool|string
     */
    public function createCheckoutId($payment): bool|string
    {

        $entity_id = env('SNB_ENTITY_ID');
        $access_token = env('SNB_AUTH_TOKEN');
        $url = env('SNB_HYPERPAY_URL')."/checkouts";
        $paymentMethod = strtoupper(request()->brand);

        /*$paymentMethod = strtoupper(request()->brand);

        if($paymentMethod == 'MADA')
        {
            $entity_id = env('SNB_ENTITY_ID_MADA'); //mada
        }

        $access_token = env('SNB_AUTH_TOKEN');
        $url = env('SNB_HYPERPAY_URL')."/checkouts";

        if($paymentMethod == 'TABBY'){
            $entity_id = env('SNB_ENTITY_ID_MADA'); //mada
            $access_token = env('SNB_AUTH_TOKEN');
            $url = env('SNB_HYPERPAY_URL')."/checkouts";
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.snb_entity_id_apple_pay');
            $access_token = config('hyperpay.snb_apple_pay_token');
        }*/

        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);
        $unique_transaction_id = $timestamp . str_replace('.', '', $micro_time);
        $unique_transaction_id = $payment->id.'-'. $unique_transaction_id;

        $data = "entityId=".$entity_id .
        "&amount=".$payment?->package?->total.
        "&currency=SAR".
        "&paymentType=DB".
        "&integrity=true".
        "&merchantTransactionId=".$unique_transaction_id .
        "&customer.email=".$payment?->student?->email.
        "&billing.street1=".$payment?->student?->city .
        "&billing.city=".$payment?->student?->city .
        "&billing.state=".$payment?->student?->city .
        "&billing.country="."SA".
        "&billing.postcode="."22230".
        "&customer.givenName=".$payment?->student?->name.
        "&customer.surname=Doe" .
        "&customer.mobile=" . $this->formatMobile($payment?->student?->phone);

        if($paymentMethod == 'TABBY')
        {
            $data .=
                "&cart.items[0].name=item1".
                "&cart.items[0].sku=15478".
                "&cart.items[0].price=".$payment?->package?->total.
                "&cart.items[0].quantity=1".
                "&cart.items[0].description=test1".
                "&cart.items[0].productUrl=http://url1.com";

        }

        Log::notice('=== Create Checkout == ',[$data]);

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
     * @param Request $request
     * @return Factory|View|Application
     */
    public function processResponse(Request $request): Factory|View|Application
    {

        return view('payments.one-time-pay');
    }

    /**
     * @return string|RedirectResponse
     */
    public function getStatus(): string|RedirectResponse
    {
        $entity_id = env('SNB_ENTITY_ID');
        $access_token = env('SNB_AUTH_TOKEN');
        $url = env('SNB_HYPERPAY_URL')."/checkouts/" . $_GET['id'] . "/payment";
        $url .= "?entityId=" . $entity_id;

        /*if(request()->paymentMethod == 'MADA') {

            $entity_id = env('SNB_ENTITY_ID_MADA');

        }

        if(request()->paymentMethod == 'APPLEPAY') {

            $entity_id = config('hyperpay.snb_entity_id_apple_pay');
            $access_token = config('hyperpay.snb_apple_pay_token');

        }*/



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

        $transactionData = array_merge($data, [

            'student_id' => request()->studentId,
            'payment_id' =>  request()->paymentId,
            'title' =>  $title
        ]);

        $payment = Payment::query()->find(request()->paymentId);

       if( data_get($transactionData, 'id') ==  null)
       {
            $payment_url = route('checkout.index') . '?sid=' . request()->studentId . '&pid=' . request()->paymentId;
            Notification::route('mail', $payment->student->email)->notify(new SentPaymentUrlNotification($payment->student, $payment_url));

           return Redirect::away(env(env('VERSION_STATE').'FRONT_URL').'/one-step-closer?status=fail');

       }
        $transaction = $this->createTransactions($transactionData,   $payment);

        $this->markPaymentAsCompleted($payment);

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
    private function markPaymentAsCompleted($payment=null)
    {
        if ($payment?->package?->payment_type == Package::ONE_TIME) {

            $transaction = Transaction::where('payment_id', $payment->id)->latest()->first();

            if ($transaction->success == 'true')
             {
                $payment->update(['is_finished' => true]);

                $payment->student?->update([
                    'package_id'=> $payment?->package?->id,
                    'is_paid'=> true,
                ]);
                $this->notifyClient($payment->student, $transaction);
                $this->sendContract($payment->student?->parentContract);
                $this->notifyAdmin($payment->student, $transaction, 'one time');
            }
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

    public function notifyClient($student, $transaction)
    {
        \Illuminate\Support\Facades\Notification::send(
            $student,
            new SuccessSubscriptionPaidNotification($student, $transaction)
        );
    }



    public function notifyAdmin($student, $transaction, $type): void
    {

        if ($type == 'one time') {
            try {
                if (env('NOTIFY_ADMINS') == true) {

                    $adminEmails = explode(',', env('ADMIN_EMAILS'));
                    foreach ($adminEmails as $adminEmail) {
                        NotificationFacade::route('mail', $adminEmail)
                            ->notify(new  NewSubscriptionNotification(
                                $student,
                                $transaction
                            ));
                    }
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
