<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\HyperpayWebHooksNotification;
use App\Models\Installment;
use App\Models\InstallmentPayment;
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

        $url = env('SNB_HYPERPAY_URL');
        $recurring_entity_id = env('SNB_RECURRING_ENTITY_ID');
        $auth_token = env('SNB_AUTH_TOKEN');

        if(in_array($registrationID, $this->riyadBankRegisterationIds())){
            $url = env('RYD_HYPERPAY_URL');
            $recurring_entity_id = env('RYD_RECURRING_ENTITY_ID');
            $auth_token = env('RYD_AUTH_TOKEN');
        }

        $url = $url . "/registrations/" . $registrationID . "/payments";

        $data = "entityId=" . $recurring_entity_id.
            "&amount=" . $amount .
            "&currency=SAR" .
            "&paymentType=DB" .
            "&standingInstruction.mode=REPEATED" .
            "&standingInstruction.type=UNSCHEDULED" .
            "&standingInstruction.source=MIT" .
            "&shopperResultUrl=" . env(env('VERSION_STATE') . 'FRONT_URL');

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
}
