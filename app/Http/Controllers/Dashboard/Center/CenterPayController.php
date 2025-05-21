<?php

namespace App\Http\Controllers\Dashboard\Center;

use App\Actions\HyperPay\GenerateCenterRecurringPaymentData;
use App\Actions\HyperPay\StoreRecurringPaymentData;
use App\Classes\HyperpayNotificationProcessor;
use App\Http\Controllers\Controller;
use App\Models\Center\CenterInstallmentPayment;
use App\Models\Center\CenterTransaction;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;

class CenterPayController extends Controller
{
    public function getPayPage(ActionRequest $request)
    {

        $installmentPayment = CenterInstallmentPayment::query()
            ->where('id',$request->payid )
            ->where('patient_id',$request->patid )
            ->firstOrFail();

         $amount = $installmentPayment?->centerPackage?->first_inst;

        $response = GenerateCenterRecurringPaymentData::make()->handle($installmentPayment?->centerPackage, $installmentPayment);

        $checkoutId = data_get($response, 'id');
        $integrity = data_get( $response , "integrity");
        $nonce = bin2hex(random_bytes(16));

        return view('payments.center-recurring-pay', compact('checkoutId',
            'amount','integrity','nonce'));
    }

    public function getStatus() #: string|RedirectResponse
    {

        $entity_id = env('RYD_ENTITY_ID');
        $access_token = env('RYD_AUTH_TOKEN');

        $url = env('RYD_HYPERPAY_URL')."/checkouts/" . $_GET['id'] . "/payment";
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

         $transactionData = array_merge($data, [
            'center_payment_id' =>  request()->payid,
            'patient_id' => request()->patid,
            'title' =>  $title
        ]);

        $transaction = $this->createTransactions($transactionData);

        $centerInstallmentPayment = CenterInstallmentPayment::query()->with('centerInstallments')->find(request()->payid);
        $centerInstallmentPayment ->update(['registration_id'=> data_get($transactionData, 'registrationId')]);

        if( data_get($transactionData, 'id') ==  null)
        {

            return redirect(route('center.recurring.checkout',['payid'=> request()->payid, 'patid'=> request()->patid]))
                ->with('status', 'fail')
                ->with('message', 'فشل في عملية الدفع، يرجى المحاولة مرة أخرى.');
        }


        if ($transaction->success == 'true') {

            $centerInstallmentPayment->centerInstallments->first()?->update([
                'is_paid' => true,
                'paid_at' => now(),
            ]);

            return to_route('center.thank.you', Crypt::encrypt($centerInstallmentPayment->id));
        } else {


            return redirect(route('center.recurring.checkout',['payid'=> request()->payid, 'patid'=> request()->patid] ))
                ->with('status', 'fail')
                ->with('message', 'فشل في عملية الدفع، يرجى المحاولة مرة أخرى.');
        }
    }

    public function getThankYouPage($id)
    {
        try {

            $id = Crypt::decrypt($id);

        } catch (DecryptException $e) {
            abort(403, 'Invalid or tampered ID.');
        }

        $centerInstallmentPayment = CenterInstallmentPayment::query()->with('centerInstallments')->find($id);

        return view('payments.center-recurring-thank-you', compact('centerInstallmentPayment'));
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createTransactions($data): mixed
    {
        return  CenterTransaction::query()->create([
            'data' => $data,
            'title' => data_get($data, 'title'),
            'center_installment_payment_id' => data_get($data, 'center_payment_id'),
            'amount' => data_get($data, 'amount')??0.0,
            'success' =>
                in_array(data_get(data_get($data, 'result'), 'code'),
                    ['000.100.110','000.000.000'])  ? 'true' : 'false',
        ]);
    }

}
