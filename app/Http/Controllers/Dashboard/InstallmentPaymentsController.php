<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\HyperpayWebHooksNotification;
use App\Models\Installment;
use App\Models\InstallmentPayment;


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
            ->orderBy('id', 'desc')->paginate(12);


        return view(
         'admin.installmentPayments.index',
            compact(
                'installmentPayments',
                'title',
            ));
    }


    public function show($id)
    {
        $title = 'عرض الدفعه ';

         $installmentPayment = InstallmentPayment::with('installments')->findOrFail($id);

        return view('admin.installmentPayments.show',
         compact('installmentPayment','title'));
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

        $url = env('HYPERPAY_URL') . "/registrations/" . $registrationID . "/payments";

        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
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
            'Authorization:Bearer ' . env('AUTH_TOKEN'),
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
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

        $this->storeNotification($response, $installmentPayment);

        // Check if the response indicates success
        if (isset($response->result) && in_array($response->result->code, ['000.100.110', '000.000.000'])) {
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

    public function storeNotification($response, $installment)
    {
        $notification = HyperpayWebHooksNotification::query()->create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installment->id,
            'type' => 'execute recurring payment',
            'payload' => $response,
            'log' => $response,
        ]);
    }

}
