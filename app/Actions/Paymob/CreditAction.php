<?php

namespace App\Actions\Paymob;

 use App\Actions\Paymob\GetAuthToken;
use App\Actions\Paymob\GetPaymentToken;
 use App\Models\ClientPayOrder;
 use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreditAction
{

    use AsAction;

    public function rules(){
       return $rules =  [
            'name'=>'required|string',
            'age'=>'required|numeric|min:10|max:100',
            'phone'=>'required|digits:10',
            'city'=>'required|string',
        ];
    }
    public function handle(ActionRequest $request)
    {

         $clientOrderPay = ClientPayOrder::query()->firstOrCreate([
             'phone'=> $request->phone
         ], [
             'name'=> $request->name,
             'age'=> $request->age,
             'phone'=> $request->phone,
             'city'=> $request->city,
         ]);

        $token = GetAuthToken::make()->handle();


        $order = CreateOrder::make()->handle($clientOrderPay->id,$token);

        $paymentToken = GetPaymentToken::make()->handle($clientOrderPay->id,$order, $token, $clientOrderPay);

        return ['payment_token'=>  'https://ksa.paymob.com/api/acceptance/iframes/'
            . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken];

    }
}
