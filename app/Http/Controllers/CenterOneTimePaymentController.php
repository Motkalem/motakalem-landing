<?php

namespace App\Http\Controllers;

use App\Classes\HyperpayNotificationProcessor;
use App\Models\Center\CenterPatient;
use App\Models\CenterPayment;
use App\Models\User;
use App\Notifications\Admin\NewCenterSubscriptionNotification;
use App\Notifications\SuccessSubscriptionPaidNotification;
use App\Traits\HelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class CenterOneTimePaymentController extends Controller
{
    use HelperTrait;

    public function getPayPage()
    {

        $brand = strtoupper(request()->brand);

        $payid = $this->decrypt(request()->payid);
        $patid = $this->decrypt(request()->patid);

        $patient = CenterPatient::find( $patid);

        $centerPayment = CenterPayment::with('centerPackage','centerPatient')->find( $payid);

        $responseData = null;

        if ($centerPayment == null) {
            echo '<h3 style="text-align:center; padding:10px">عذرًا، لم نتمكن من العثور على دفعتك. يرجى التواصل معنا    </h1>';
            echo '</br>';
            echo "<p style='text-align:center'><a href=" . url('/') . ">" . url('/') . " </a></p>";
            die();
        }


            if (  $brand) {

                 $responseData = $this->createCheckoutId($patient,$centerPayment);
            }



        $paymentId = data_get(json_decode($responseData), "id");
        $integrity = data_get(json_decode($responseData), "integrity");
        $nonce = bin2hex(random_bytes(16));


        return view('payments.one-time-center', compact('centerPayment', 'paymentId',
            'integrity', 'nonce','brand','payid','patid'));
    }



    /**
     * @param $payment
     * @return bool|string
     */
      public function createCheckoutId($centerPatient, $centerPayment): bool|string
    {
        $entity_id =  env('RYD_ENTITY_ID');
        $access_token = env('RYD_AUTH_TOKEN');
        $paymentMethod = strtoupper(request()->brand);

        if ($paymentMethod == 'MADA') {

            $entity_id = env('RYD_ENTITY_ID_MADA'); //MADA
        }

        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
            $access_token = config('hyperpay.ryd_apple_pay_token');
        }

        $url = env('RYD_HYPERPAY_URL') . "/checkouts";

        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);

        $unique_transaction_id = $centerPatient->id .'-'.$timestamp . str_replace('.', '', $micro_time);


        $data = 'entityId='
            . $entity_id
            . "&amount=" . $centerPayment->amount
            ."&currency=SAR"
            ."&paymentType=DB" .
            "&integrity=true".
            "&merchantTransactionId=" . $unique_transaction_id .
            "&customer.email=" . $centerPatient?->email .
            "&billing.street1=" . $centerPatient?->city .
            "&billing.city=" . $centerPatient?->city .
            "&billing.state=" . $centerPatient?->city .
            "&billing.country=" . "SA" .
            "&billing.postcode=" . "" .
            "&customer.givenName=" . $centerPatient?->name .
            "&customer.surname=" . "";

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


    /**
     * @return string|RedirectResponse
     */
    public function getStatus() #: string|RedirectResponse
    {

        $entity_id =  env('RYD_ENTITY_ID');
        $access_token = env('RYD_AUTH_TOKEN');
        $paymentMethod = strtoupper(request()->brand);

        $payid = $this->decrypt(request()->payid);
        $patid = $this->decrypt(request()->patid);

        $centerPayment = CenterPayment::find( $payid);
        $centerPatient = CenterPatient::query()->find($patid);



        if ($paymentMethod == 'MADA') {

            $entity_id = env('RYD_ENTITY_ID_MADA'); //MADA
        }
        if($paymentMethod == 'APPLEPAY')
        {
            $entity_id = config('hyperpay.ryd_entity_id_apple_pay');
            $access_token = config('hyperpay.ryd_apple_pay_token');
        }


        $url = env('RYD_HYPERPAY_URL') . "/checkouts/" . data_get($_GET,'id') . "/payment";
        $url .= "?entityId=" . $entity_id;

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

        $response = $responseData;

        $res = new HyperpayNotificationProcessor($response);

        $title = $res->processNotification();

        $data = (array)json_decode($responseData);

         $transactionData = array_merge($data, [
            'patient_id' => request()->studentId,
            'title' => $title
        ]);


        if( data_get($transactionData, 'id') ==  null)
        {

            return redirect(route('checkout.center.onetime.index',
                ['payid'=> $this->encrypt(request()->payid), 'patid'=>  request()->patid ]))
                ->with('status', 'fail')
                ->with('message', 'فشل في عملية الدفع، يرجى المحاولة مرة أخرى.');
        }

        $this->createTransactions($transactionData,  $centerPatient,  $centerPayment);


        if ($this->isSuccessfulNotification($transactionData) ) {

            $this->notifyCenterPaymentSuccess($centerPatient);

                $centerPayment->update([
                    'is_finished' => true,
                    'paid_at' => now(),
                ]);

                return to_route('checkout.center.onetime.invoice',
                    $this->encrypt($centerPayment->id));

            } else {
                return redirect(route('checkout.center.onetime.index',
                ['payid'=> $this->encrypt(request()->payid), 'patid'=>  request()->patid ]))
                ->with('status', 'fail')
                ->with('message', 'فشل في عملية الدفع، يرجى المحاولة مرة أخرى.');
        }
    }



    /**
     * @return string|RedirectResponse
     */
    public function getInvoice($id) #: string|RedirectResponse
    {
        $centerPayment = CenterPayment::query()->find($this->decrypt($id));

        $centerPayment->update([
            'is_finished' => true,
            'paid_at' => now(),
        ]);

        return view('payments.center-onetime-thank-you', compact('centerPayment'));
    }


    /**
     * @param $notification
     * @return bool
     */
    protected function isSuccessfulNotification($notification): bool
    {
        $resultCode = data_get($notification['result'], 'code');
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

        return  preg_match($successPattern, $resultCode) === 1;
    }

    /**
     * @param $transactionData
     * @param $payment
     * @return mixed
     */
    public function createTransactions($transactionData,  $centerPatient,  $centerPayment): mixed
    {



        return \App\Models\CenterPaymentTransaction::query()->create([
            'data' => $transactionData,
            'title' =>  data_get($transactionData, 'title'),
            'transaction_id' => data_get($transactionData, 'id'),
            'center_patient_id' => $this->decrypt(request()->patid),
            'center_payment_id' => $centerPayment?->id,
            'amount' => data_get($transactionData, 'amount') ?? 0.0,
            'success' =>
                in_array(data_get(data_get($transactionData, 'result'), 'code'),
                    ['000.100.110', '000.000.000']) ? 'true' : 'false',
        ]);
    }

        public function notifyClient($student, $transaction)
        {
            \Illuminate\Support\Facades\Notification::send(
                $student,
                new SuccessSubscriptionPaidNotification($student, $transaction)
            );
        }


    /**
     * Notify center payment success
     * @param $centerPatient
     * @return void
     */
    private function notifyCenterPaymentSuccess($centerPatient): void
    {

            // Send SMS notification
            $msg = "عزيزي {$centerPatient->name}، تم تأكيد دفعك بنجاح في مركز متكلم الطبي للسمعيات. سيتم التواصل معك قريباً لتحديد موعدك.";

            if (env('APP_ENV') == 'production') {
                (new \App\Http\Support\SMS())->setPhone($centerPatient->mobile_number)->SetMessage($msg)->build();
            }

            // Send email notification to admin
            $adminUsers = User::get();

            foreach ($adminUsers as $admin) {

                NotificationFacade::route('mail', $admin->email)->notify(
                    new NewCenterSubscriptionNotification(
                        $centerPatient,
                        $centerPatient->centerPayment
                    )
                );
            }

    }
}
