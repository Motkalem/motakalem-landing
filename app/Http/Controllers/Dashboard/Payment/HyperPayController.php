<?php

namespace App\Http\Controllers\Dashboard\Api\Payment;

use App\Constants\LazerResponseConstants;
use App\Http\Controllers\Controller;
use App\Models\MaintenanceOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HyperPayController extends Controller
{

    public function createCheckoutId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_price' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
        ]);

        $total_price = $this->priceFormatting($request['total_price']);

        $entitiy_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');

        $url = "https://eu-test.oppwa.com/v1/checkouts";

        $data =  $entitiy_id."&amount=".$total_price
        ."&currency=SAR" ."&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
        array(  'Authorization:Bearer '. $access_token));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }

    public function getPaymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => [
                'required',
                'numeric',
                Rule::exists(MaintenanceOrder::class, 'id')
                    ->where('user_id', auth()->user()->id)
            ],
            'checkout_id' => [
                'required',
            ],
            'payment_method' => 'required|in:VISA,MASTER,MADA,APPLEPAY',
        ]);

        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->lazerResponse(
                LazerResponseConstants::STATUS_FAILURE,
                LazerResponseConstants::ERROR_STATUS,
                $errorMessage,
                [],
                LazerResponseConstants::HTTP_UNPROCESSABLE
            );
        }

        $url = config('hyperpay.link')."checkouts/".$request->checkout_id."/payment";

        $entitiy_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');
        if($request->payment_method == 'MADA'){
            $entitiy_id = config('hyperpay.entity_id_mada');
        }elseif($request->payment_method == 'APPLEPAY'){
            $entitiy_id = config('hyperpay.entity_id_apple_pay');
            $access_token = config('hyperpay.access_token_apple_pay');
        }

        $url .= "?entityId=".$entitiy_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Authorization:Bearer '.$access_token)
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $d = json_decode($responseData);

        $status = $this->checkStatus($d->result->code);

        Log::error('=== Response == ',[$responseData]);

        if ($status === true) {
            $transaction = Transaction::create([
                'order_id' => $request->order_id,
                'transaction_response' => $responseData,
            ]);
            return $this->lazerResponse();
        } else {
            return $status;
        }
    }

    public function checkStatus($code) {
        Log::notice('=== Response code == ',[$code]);
        if ($this->isSuccessCode($code)) {
            return true;
        } else {
            $status = $this->getStatusMessage($code);
            return $this->lazerResponse(
                LazerResponseConstants::STATUS_FAILURE,
                LazerResponseConstants::ERROR_STATUS,
                $status,
                [],
                LazerResponseConstants::HTTP_UNPROCESSABLE
            );
        }
    }

    private function isSuccessCode($code) {
        $success_codes = [
            '000.000.000', '000.000.100', '000.100.105','000.100.106',
            '000.100.110', '000.100.111', '000.100.112', '000.300.000',
            '000.300.100', '000.300.101', '000.300.102', '000.300.103',
            '000.310.100', '000.310.101','000.310.110', '000.400.110',
            '000.400.120', '000.600.000',
        ];
        return in_array($code, $success_codes);
    }

    private function isPendingCode($code) {
        $pending_codes = [
            '000.200.000', '000.200.001', '000.200.100', '000.200.101',
            '000.200.102','000.200.103', '000.200.200', '000.200.201',
            '000.200.999', '100.400.500', '800.400.500', '800.400.501',
            '800.400.502',
        ];
        return in_array($code, $pending_codes);
    }

    private function isLimitExceed($code) {
        $limit_exceed_codes = [
            '800.120.103'
        ];
        return in_array($code, $limit_exceed_codes);
    }

    private function isRejectionCode($code) {
        $rejection_codes = [
            '100.100.100', '100.100.101', '100.100.102', '100.100.103',
            '100.200.100', '100.200.101', '100.200.102', '100.200.103',
            '100.200.200', '100.300.100', '100.300.101', '100.300.200',
            '100.300.201', '100.300.300', '100.300.301'
        ];
        return in_array($code, $rejection_codes);
    }

    private function isError($code) {
        $error_codes = [
            '200.100.000', '200.100.001', '200.100.002', '200.200.001',
            '200.200.002', '200.300.001', '200.300.002', '200.300.003',
            '200.400.001', '200.400.002', '200.400.003', '200.400.004'
        ];
        return in_array($code, $error_codes);
    }

    private function getStatusMessage($code) {
        if ($this->isPendingCode($code)) {
            return __('general.payment.status.pending');
        } elseif ($this->isLimitExceed($code)) {
            return __('general.payment.status.limit.exceed');
        } elseif ($this->isRejectionCode($code)) {
            return __('general.payment.status.rejected');
        } elseif ($this->isError($code)) {
            return __('general.payment.status.error');
        } else {
            return __('general.payment.status.unknown');
        }
    }

}
