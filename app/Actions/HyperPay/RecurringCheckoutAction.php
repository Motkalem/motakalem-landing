<?php

namespace App\Actions\HyperPay;

use App\Models\InstallmentPayment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RecurringCheckoutAction
{
    use AsAction;

   
    /**
     * @param ActionRequest $request
     * @return Application|Factory|View
     */
    public function handle(ActionRequest $request)
    {
        
         $installmentPayment = InstallmentPayment::query()
            ->where('id',$request->paymentId )
            ->where('student_id',$request->stdId )
            ->firstOrFail();

        $amount = $installmentPayment?->package?->first_inst;

        if (request()->has('brand')) {
            
            $response = StoreRecurringPaymentData::make()->handle($installmentPayment?->package, $installmentPayment);
            $checkoutId = data_get($response, 'id');
            $integrity = data_get($response, "integrity");
            $nonce = bin2hex(random_bytes(16));
        } else {
            $checkoutId = null;
            $integrity = null;
            $nonce = null;
        }

        return view('payments.recurring-new-pay', compact('checkoutId',
            'amount','integrity','nonce'));
    }
}
