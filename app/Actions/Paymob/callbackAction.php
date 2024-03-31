<?php

namespace App\Actions\Paymob;

 use App\Models\ClientPayOrder;
 use App\Models\Transaction;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class callbackAction
{
    use AsAction;

    public function handle(ActionRequest $request)
    {
        $data = $request->all();

        ksort($data);

        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        $connectedString = '';

        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }

        $secret = env('PAYMOB_HMAC');

        $hased = hash_hmac('sha512', $connectedString, $secret);

            $client_pay_order_id = data_get(explode('-', data_get($data, 'merchant_order_id')), 0);

            if ($hased == $hmac) {
                $transaction = Transaction::query()->create([
                    'transaction_id' => data_get($data, 'id'),
                    'client_pay_order_id' => $client_pay_order_id,
                     'success' => data_get($data, 'success'),
                    'amount' => data_get($data, 'amount_cents') / 100,
                    'data' => $data,
                ]);

                if (($transaction->success == "true") || ($transaction->success === true))
                {
                    $client_pay_order_id = ClientPayOrder::where('id' ,$client_pay_order_id)->first();
                    if ($client_pay_order_id){
                        $client_pay_order_id->update(['is_paid'=> 'paid']);
                    }

                   return  Redirect::away('https://www.motkalem.com/one-step-closer'.'?'.'status=success');
                } else {



                    return  Redirect::away('https://www.motkalem.com/one-step-closer'.'?'.'status=fail');
                }
            return Redirect::route('home');
        } else {

            echo 'tran not secure';
            exit;
        }

    }

}
