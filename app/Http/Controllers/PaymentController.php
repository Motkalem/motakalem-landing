<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{


    public function getPayPage()
    {

        $payment = Payment::with('package')->find(request()->paymentId);
        return view('payments.one-time-pay', compact('payment'));
    }

    public function processResponse(Request $request)
    {

        return view('payments.one-time-pay');
    }

    public function getStatus()
    {
        $entitiy_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');

        $url = "https://eu-test.oppwa.com/v1/checkouts/" . $_GET['id'] . "/payment";
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

        $data = (array) json_decode($responseData);

        $transactionData = array_merge($data, [

            'student_id' => request()->studentId,
            'payment_id' =>  request()->paymentId
        ]);

        $transaction = $this->createTransactions($transactionData);

        $this->markPaymentAsCompleted(request()->paymentId);

        if($transaction->success == 'true'){

            echo "<h1 style='text-align:center;padding:10px;color:green'>Your transaction is successful, and you will be redirected shortly.</h1>";
        }else{

            echo "<h1 style='text-align:center;padding:10px;color:red'>Your transaction has been failed, and you will be redirected shortly.</h1>";
        }

        echo "<script>
            setTimeout(function(){
                window.location.href = '/';
            }, 5000);
        </script>";
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function createTransactions($data)
    {
        return  Transaction::create([
            'data' => $data,
            'transaction_id' => data_get($data, 'id'),
            'student_id' => data_get($data, 'student_id'),
            'payment_id' => data_get($data, 'payment_id'),
            'amount' => data_get($data, 'amount'),
            'success' =>
            data_get(data_get($data, 'result'), 'code') == '000.100.110' ? 'true' : 'false',
        ]);
    }

    private function markPaymentAsCompleted($id)
    {

        $payment = Payment::find($id);

        if ($payment?->package?->payment_type == Package::ONE_TIME) {
            $transaction = Transaction::where('payment_id', $id)->latest()->first();

            if ($transaction->success == 'true') {

                $payment->update(['is_finished' => true]);
            }
        }
    }
}
