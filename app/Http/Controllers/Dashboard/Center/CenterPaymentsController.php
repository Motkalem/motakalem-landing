<?php

namespace App\Http\Controllers\Dashboard\Center;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Center\CenterTransaction;
use App\Models\HyperpayWebHooksNotification;
use App\Models\Center\CenterInstallment;
use App\Models\Center\CenterInstallmentPayment;
use App\Models\Installment;
use App\Notifications\Admin\CenterPaymentUrlNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CenterPaymentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'المدفوعات المجدولة';
        $search = request()->query('search');

        $query = CenterInstallmentPayment::query()->with('centerInstallments');

        if ($search) {

            $query->where('name', 'like', '%' . $search . '%'); // Assuming 'name' exists in the model
        }

        $installmentPayments = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.center-payments.index', compact('installmentPayments', 'title'));
    }

    public function show($id)
    {
        $title = 'عرض الدفعه';
        $installmentPayment = CenterInstallmentPayment::with('centerInstallments')->findOrFail($id);

        return view('admin.center-payments.show', compact('installmentPayment', 'title'));
    }

    public function deductInstallment()
    {
        $installment = CenterInstallment::query()
            ->with('centerInstallmentPayment')
            ->findOrFail(request()->id);

        if ($installment->is_paid) {
            notify()->error('هذا القسط مدفوع بالفعل.');
            return redirect()->back();
        }

        $installmentPayment = $installment->centerInstallmentPayment;
        $registrationID = $installmentPayment->registration_id ?? null;
        $amount = $installment->installment_amount;

        if (!$registrationID) {
            notify()->error('لا يوجد معرّف تسجيل.');
            return redirect()->back();
        }

        $url = env('RYD_HYPERPAY_URL');
        $recurring_entity_id = env('RYD_RECURRING_ENTITY_ID');
        $auth_token = env('RYD_AUTH_TOKEN');

        $url .= "/registrations/{$registrationID}/payments";

        $data = http_build_query([
            'entityId' => $recurring_entity_id,
            'amount' => $amount,
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'standingInstruction.mode' => 'REPEATED',
            'standingInstruction.type' => 'UNSCHEDULED',
            'standingInstruction.source' => 'MIT',
            'shopperResultUrl' => env(env('VERSION_STATE') . 'FRONT_URL')
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => ['Authorization:Bearer ' . $auth_token],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYPEER => env('SSL_VERIFYPEER', false),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            notify()->error('حدث خطأ أثناء معالجة الدفع: ' . curl_error($ch));
            curl_close($ch);
            return redirect()->back();
        }
        curl_close($ch);

        $response = json_decode($responseData);
        $data = (array)json_decode($responseData);


        $title = $this->processResponse($response);
        $data = array_merge($data, [
            'center_payment_id' => $installmentPayment->id,
            'title' => $title,
        ]);

        $this->createTransactions($data);

        if (isset($response->result) && $this->isSuccessfulResponse($response->result?->code)) {
            $installment->update([
                'is_paid' => true,
                'paid_at' => now(),
                'admin_ip' => request()->ip(),
            ]);

            $remaining = $installmentPayment->centerInstallments->where('is_paid', false);
            if ($remaining->isEmpty()) {
                $installmentPayment->update(['is_completed' => true]);
            }

            notify()->success('تم خصم القسط بنجاح.');
        } else {
            $errorMessage = $response->result->description ?? 'حدث خطأ غير معروف.';
            notify()->error('فشل الدفع: ' . $errorMessage);
        }
        return redirect()->back();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createTransactions($data): mixed
    {

        return CenterTransaction::query()->create([
            'data' => $data,
            'title' => data_get($data, 'title'),
            'center_installment_payment_id' => data_get($data, 'center_payment_id'),
            'amount' => data_get($data, 'amount') ?? 0.0,
            'success' => in_array(data_get(data_get($data, 'result'), 'code'),
                ['000.100.110', '000.000.000']) ? 'true' : 'false',
        ]);
    }

    private function isSuccessfulResponse(?string $resultCode): bool
    {
        return preg_match('/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/', $resultCode) === 1;
    }

    public function sendPayUrl($id)
    {
        $centerInstallmentPayment = CenterInstallmentPayment::with('patient')->findOrFail($id);

        $url = route('center.recurring.checkout', [
            'payid' => $centerInstallmentPayment->id,
            'patid' => $centerInstallmentPayment->patient_id
        ]);

        try {
            Notification::route('mail', $centerInstallmentPayment->patient?->email)
                ->notify(new CenterPaymentUrlNotification($centerInstallmentPayment->patient, $url));

            return response()->json([
                'status' => 'success',
                'message' => 'تم إرسال رابط الدفع.',
                'payment_url' => $url,
                'email' => $centerInstallmentPayment->patient?->email
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إرسال رابط الدفع.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function processResponse($response)
    {
        if (is_object($response)) {
            $response = json_decode(json_encode($response), true);
        }

        $resultCode = $response['result']['code'] ?? $response['payload']['result']['code'] ?? null;

        if (!$resultCode) {
            return 'استجابة غير صالحة: لم يتم العثور على رمز النتيجة.';
        }

        // Known result codes
        $knownCodes = [
            '000.100.110' => 'تمت معالجة الطلب بنجاح في "وضع الاختبار المتكامل للتاجر".',
            '800.100.161' => 'تم رفض المعاملة (عدد المحاولات غير الصحيحة تجاوز الحد المسموح به).',
            '200.300.404' => 'خطأ في المعاملة: لم يتم العثور على جلسة الدفع - ربما تم خلط بين خوادم الاختبار/المباشر أو مر أكثر من 30 دقيقة.',
            '100.396.101' => 'تم إلغاء المعاملة من قبل المستخدم.',
            '000.200.000' => 'المعاملة قيد الانتظار.',
            '100.100.101' => 'التسجيل غير صالح، ربما تم رفضه في البداية.',
        ];

        if (isset($knownCodes[$resultCode])) {
            return $knownCodes[$resultCode];
        }

        // Pattern-based heuristic evaluation
        if (preg_match('/^000\.000\.|000\.100\.1|000\.[36]/', $resultCode)) {
            return 'تمت المعاملة بنجاح.';
        }

        if (preg_match('/^000\.200\./', $resultCode)) {
            return 'المعاملة قيد الانتظار.';
        }

        if (preg_match('/^000\.400\.1/', $resultCode)) {
            return 'تمت المعاملة بنجاح ولكنها تحت المراجعة.';
        }

        if (preg_match('/^(800|900)\./', $resultCode)) {
            return 'تم رفض المعاملة.';
        }

        if (preg_match('/^(100\.39|200\.3|300\.)/', $resultCode)) {
            return 'حدث خطأ في الاتصال مع بوابة الدفع.';
        }

        if (preg_match('/^100\.1/', $resultCode)) {
            return 'حدث خطأ متعلق بطريقة الدفع.';
        }

        if (preg_match('/^000\.400\.2/', $resultCode)) {
            return 'المعاملة تتضمن إشعار خطر أو تحت المراجعة.';
        }

        return 'رمز النتيجة غير معروف.';
    }


}
