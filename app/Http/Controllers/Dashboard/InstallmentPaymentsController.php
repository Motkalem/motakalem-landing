<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Center\CenterInstallmentPayment;
use App\Models\HyperpayWebHooksNotification;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use App\Notifications\Admin\CenterPaymentUrlNotification;
use App\Notifications\Admin\ProgramInstallmentPayLinkNotification;
use App\Notifications\SentPaymentUrlNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class InstallmentPaymentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'المدفوعات المجدولة';

        $search = request()->query('search');

        $query = InstallmentPayment::query()
            ->with('installments');

        if ($search) {
            $query->whereHas('student', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $installmentPayments = $query->with(['student', 'package', 'hyperpayWebHooksNotifications'])
            ->orderBy('id', 'desc')->paginate(10);


        return view(
            'admin.installment-payments.index',
            compact(
                'installmentPayments',
                'title',
            ));
    }


    public function show($id)
    {
        $title = 'عرض الدفعه ';

        $installmentPayment = InstallmentPayment::with('installments')->findOrFail($id);

        return view('admin.installment-payments.show',
            compact('installmentPayment','title'));
    }


    public function sendPaymentLink($id)
    {
        $installmentPayment = InstallmentPayment::with('installments', 'student')->findOrFail($id);
        $payment_url = route('recurring.checkout', [
            'paymentId' => $installmentPayment?->id,
            'stdId' => $installmentPayment->student_id
        ]);

        if( $installmentPayment->student) {

            try {
                Notification::route('mail', $installmentPayment->student?->email)
                    ->notify(new SentPaymentUrlNotification($installmentPayment->student, $payment_url));

                return response()->json([
                    'status' => 'success',
                    'message' => __('Payment link sent successfully to the student.'),
                    'payment_url' => $payment_url,
                ], 200);
            } catch (\Exception $e) {
                Log::error('Error sending payment link: ' . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => __('There was an error sending the payment link. Please try again later.'),
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => __('There was an error sending the payment link. Please try again later.'),
        ], 500);
    }


    public function deductInstallment()
    {
        $installment = Installment::query()
            ->with('installmentPayment')
            ->findOrFail(request()->id);

        // Check if the installment is already paid
        if ($installment->is_paid) {

            notify()->error('هذا القسط مدفوع بالفعل.');
            return redirect()->back();
        }

        $installmentPayment = $installment->installmentPayment;
        $registrationID = $installmentPayment->registration_id;
        $amount = $installment->installment_amount;

        $notification = $this->getSuccessfulInitialNotification($installmentPayment);

        $recurringPaymentAgreement = $installmentPayment->recurring_agreement_id;

        $merchantTransactionId = data_get($notification, 'payload.merchantTransactionId');
        $cardholderInitiatedTransactionID = data_get($notification, 'payload.resultDetails.CardholderInitiatedTransactionID');

        Log::debug('Subsequent Agreement ID', ['agreement' => $recurringPaymentAgreement]);

        $url = env('SNB_HYPERPAY_URL');
        $recurring_entity_id = env('SNB_RECURRING_ENTITY_ID');
        $auth_token = env('SNB_AUTH_TOKEN');

        if(in_array($registrationID, $this->riyadBankRegisterationIds())){
            $url = env('RYD_HYPERPAY_URL');
            $recurring_entity_id = env('RYD_RECURRING_ENTITY_ID');
            $auth_token = env('RYD_AUTH_TOKEN');
        }

        $url = $url . "/registrations/" . $registrationID . "/payments";

        $data = "entityId=" . $recurring_entity_id .
            "&amount=" . $amount .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&standingInstruction.mode=REPEATED" .
            "&standingInstruction.type=UNSCHEDULED" .
            "&standingInstruction.source=MIT" .
            "&standingInstruction.numberOfInstallments=99" .
            "&standingInstruction.recurringType=SUBSCRIPTION" .
            "&standingInstruction.initialTransactionId=" .$cardholderInitiatedTransactionID .
            //"&customParameters[CardholderInitiatedTransactionID]=" .  $cardholderInitiatedTransactionID .
            "&customParameters[recurringPaymentAgreement]=" . $recurringPaymentAgreement .
            "&shopperResultUrl=" . env(env('VERSION_STATE') . 'FRONT_URL');

        Log::debug('deduct Installment', [$data]);
//print_r($data); die();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization:Bearer ' . $auth_token,
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, env('SSL_VERIFYPEER')); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            notify()->error('حدث خطأ أثناء معالجة الدفع: ' . $error);
            return redirect()->back();
        }
        curl_close($ch);

        $response = json_decode($responseData);

        $this->storeNotification($response, $installmentPayment, $installment);

        $isSuccessful = $this->isSuccessfulResponse($response->result?->code);

        // Check if the response indicates success
        if (isset($response->result) &&  $isSuccessful) {

            $installment->update([
                'is_paid' => true,
                'paid_at' => now(),
                'admin_ip' => request()->ip(),
            ]);

            // Check if this is the last installment
            $remainingInstallments = $installmentPayment->installments->where('is_paid', false);

            if ($remainingInstallments->isEmpty()) {
                $installmentPayment->update([
                    'is_completed' => true,
                ]);
            }

            notify()->success('تم خصم القسط بنجاح.');
            return redirect()->back();
        } else {
            $errorMessage = $response->result->description ?? 'حدث خطأ غير معروف.';
            notify()->error('فشل الدفع: ' . $errorMessage);
            return redirect()->back();
        }
    }

    public function sendPaymentUrl($id)
    {


        $installment = Installment::query()
            ->with('installmentPayment')
            ->findOrFail($id);


        $url = route('pay-installment.index', [
            'instId' => $installment->id,
        ]);


        try {

            Notification::route('mail', $installment->installmentPayment?->student?->email)
                ->notify(new ProgramInstallmentPayLinkNotification($installment->installmentPayment->student, $url));

            notify()->success('تم إرسال الرباط: '  );

        } catch (\Exception $e) {


            notify()->error('حدث خطأ أثناء  إرسال الرابط: ' . $e);

        }

        return redirect()->back();
    }

    /**
 * Get the successful initial notification for the installment payment
 */
    protected function getSuccessfulInitialNotification($installmentPayment)
    {
        return $installmentPayment->hyperpayWebHooksNotifications
            ->filter(function($notification) {

                $resultCode = data_get($notification->payload, 'result.code');
                $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';

                return $notification->type === 'init recurring payment' &&
                       preg_match($successPattern, $resultCode) === 1;
            })
            ->first();
    }

    /**
     * @param $response
     * @param $installmentPayment
     * @param $installment
     * @return void
     */
    public function storeNotification($response, $installmentPayment, $installment): void
    {
        $notification = HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installmentPayment->id,
            'installment_id' => $installment->id,
            'type' => 'execute recurring payment',
            'payload' => $response,
            'log' => $response,
        ]);
    }


    private function isSuccessfulResponse(?string $resultCode): bool
    {
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';
        return preg_match($successPattern, $resultCode) === 1;
    }

    public function riyadBankRegisterationIds()
    {
        return [
            '8ac9a4a094ef66a50194f6a84ad26959', '8ac9a49f94ef2e92019500a0bbeb28ee',
            '8ac9a4a394b200fa0194cd115ecf7c9c', '8acda4a594b683e90194c7f5b2644a46',
            '8ac9a4a49469716801946a204afd7cfd'
        ];
    }


    public function destroy($id)
    {
        $payment = InstallmentPayment::with(['installments', 'student.parentContract'])->findOrFail($id);

        // Check if any installments were paid
        $hasPaidInstallments = $payment->installments()->where('is_paid', 1)->exists();
        if ($hasPaidInstallments) {
            return response()->json([
                'success' => false,
                'message' => __('Cannot delete a payment that has already been paid.')
            ], 403);
        }

        // Delete all related installments
        $payment->installments()->delete();

        // Delete related student and parent contract (if exist)
        if ($payment->student) {
            if ($payment->student->parentContract) {
                $payment->student->parentContract->delete();
            }
            $payment->student->delete();
        }

        // Delete main InstallmentPayment
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => __('Payment request, related student, and parent contract deleted successfully.')
        ]);
    }



}
