<?php

namespace App\Http\Controllers;

use App\Classes\HyperpayNotificationProcessor;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use App\Notifications\Admin\NewSubscriptionNotification;
use App\Notifications\SuccessSubscriptionPaidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Notification as NotificationFacade;

use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

class PaymentController extends Controller
{

    public function getPayPage()
    {
        $payment = Payment::with('package')->find(request()->pid);

        $responseData = null;

        if ($payment == null) {

            echo '<h3 style="text-align:center; padding:10px">عذرًا، لم نتمكن من العثور على دفعتك. يرجى التواصل معنا    </h1>';
            echo '</br>';
            echo "<p style='text-align:center'><a href=" . url('/') . ">" . url('/') . " </a></p>";
            die();
        }
        try {
            if ($payment->package?->payment_type == Package::ONE_TIME) {
                $responseData = $this->createCheckoutId($payment?->package?->total);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $paymentId = data_get(json_decode($responseData), "id");

        return view('payments.one-time-pay', compact('payment', 'paymentId'));
    }

    public function createCheckoutId($total_price)
    {

        $entitiy_id = config('hyperpay.entity_id'); //visa or master


        if(request()->payment_method == 'MADA'){

            $entitiy_id = env('ENTITY_ID_MADA'); //mada
        }

        $access_token = env('AUTH_TOKEN');

        $url = env('HYPERPAY_URL')."/checkouts";

        $data = 'entityId='
        . $entitiy_id . "&amount=". $total_price
        . "&currency=SAR"
        . "&paymentType=DB";

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array('Authorization:Bearer ' . $access_token)
        );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        return $responseData;
    }

    public function processResponse(Request $request)
    {

        return view('payments.one-time-pay');
    }

    public function getStatus()
    {
        $entitiy_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');

        $url = env('HYPERPAY_URL')."/checkouts/" . $_GET['id'] . "/payment";
        $url .= "?entityId=" . $entitiy_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {

            return curl_error($ch);
        }
        curl_close($ch);

       return  $response  = $responseData;
        $res = new HyperpayNotificationProcessor( $response);

        $title =  $res->processNotification()   ;

        $data = (array) json_decode($responseData);

        $transactionData = array_merge($data, [

            'student_id' => request()->studentId,
            'payment_id' =>  request()->paymentId,
            'title' =>  $title
        ]);

        $payment = Payment::find(request()->paymentId);

       if( data_get($transactionData, 'id') ==  null){

         return Redirect::away('https://motkalem.com/one-step-closer?status=fail');

       }
        $transaction = $this->createTransactions($transactionData,   $payment);

        $this->markPaymentAsCompleted($payment);

        if ($transaction->success == 'true') {

            return Redirect::away('https://motkalem.com/one-step-closer?status=success');
        } else {

            return redirect('https://motkalem.com/one-step-closer?status=fail');
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function createTransactions($data, $payment=null)
    {

        return  Transaction::create([
            'data' => $data,
            'title' => data_get($data, 'title'),
            'transaction_id' => data_get($data, 'id'),
            'student_id' => data_get($data, 'student_id'),
            'payment_id' => data_get($data, 'payment_id'),
            'amount' => data_get($data, 'amount'),
            'success' =>
            data_get(data_get($data, 'result'), 'code') == '000.100.110' ? 'true' : 'false',
        ]);
    }

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
                $this->notifyAdmin($payment->student, $transaction, 'one time');
            }
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
