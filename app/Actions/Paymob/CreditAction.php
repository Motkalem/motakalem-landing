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

    public function rules()
    {


       return $rules =  [
            'name'=>'required|string',
            'age'=>'required|numeric|min:10|max:100',
            'phone'=>'required|digits:10',
            'email'=>'required|email',
            'city'=>'required|string',
            'payment_type'=>'required|in:'.implode(',',array_values(ClientPayOrder::$paymentTypes)),
        ];
    }

    public function handle(ActionRequest $request): array
    {

         $clientOrderPay = ClientPayOrder::query()->firstOrCreate([
             'phone'=> $request->phone,
         ], [
             'name'=> $request->name,
             'email'=> $request->email,
             'age'=> $request->age,
             'phone'=> $request->phone,
             'city'=> $request->city,
             'payment_type'=> $request->payment_type,
             'total_payment_amount'=>  env('SUBSCRIPTION_AMOUNT')??12000,
         ]);

        $token = GetAuthToken::make()->handle();

        $order = CreateOrder::make()->handle($clientOrderPay->id,$token);

        $paymentToken = GetPaymentToken::make()->handle($clientOrderPay->id,$order, $token, $clientOrderPay);

        return ['payment_token'=>  'https://ksa.paymob.com/api/acceptance/iframes/'
            . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken];

    }
}
