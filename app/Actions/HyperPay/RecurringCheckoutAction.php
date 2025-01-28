<?php

namespace App\Actions\HyperPay;

use App\Models\InstallmentPayment;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RecurringCheckoutAction
{
    use AsAction;

    public function handle(ActionRequest $request) #: Factory|View|Application
    {
         $installmentPayment = InstallmentPayment::query()
            ->where('id',$request->paymentId )
            ->where('student_id',$request->stdId )
            ->firstOrFail();

         $amount = $installmentPayment?->package?->first_inst;

        $response = StoreRecurringPaymentData::make()->handle($installmentPayment?->package, $installmentPayment);

        $checkoutId = data_get($response, 'id');

        return view('payments.recurring-new-pay', compact('checkoutId', 'amount'));
    }

}
