<?php

namespace App\Actions\Paymob;

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

        if ($hased == $hmac) {

                $transaction = Transaction::query()->create([
                    'transaction_id' => data_get($data, 'id'),
                     'success' => data_get($data, 'success'),
                    'amount' => data_get($data, 'amount_cents') / 100,
                    'data' => $data,
                ]);

                if (($transaction->success == "true") || ($transaction->success === true)) {

                     $invoice = Invoice::where('order_id', $order->id)->latest()->first();
                    $invoice->update(['is_paid' => 1]);
                    $invoice->update(['status' => Invoice::ACCEPTED]);

                    $this->notifyUserWithOrderConfirmed($order,$generalSettings);

                    return Redirect::route('front.orders.status', $order->id)->with('success', __('Success'));
                } else {

                    return Redirect::route('front.orders.status', $order->id)->with('error', __('Failed payment'));
                }


            return Redirect::route('home');
        } else {

            echo 'tran not secure';
            exit;
        }

    }

}
