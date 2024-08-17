<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Helper;
use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\InstallmentPayment;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InstallmentPaymentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'المدفوعات المجدولة';

        $installmentPayments = InstallmentPayment::with(['student', 'package', 'transactions'])->orderBy('id', 'desc')->paginate(12);

        return view(

         'admin.installmentPayments.index',
            compact(
                'installmentPayments',
                'title',
            )
        );
    }

    public function create()
    {
        $title = 'إنشاء دفعة جديدة';
        $students = Student::all();
        $packages = Package::where('is_active', 1)->get();
        return view(
            'admin.payments.create',
            compact(
                'students',
                'title',
                'packages'
            )
        );
    }

    public function store(Request $request)
    {

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'package_id' => 'required|exists:packages,id',
            'is_finished' => 'sometimes',
        ]);

        $package = Package::find($request->package_id);
        try {
            if ($package->payment_type == Package::ONE_TIME) {
                $responseData = $this->createCheckoutId($package->total);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $payment = Payment::create([
            'student_id' => $request->student_id,
            'package_id' => $request->package_id,
            'payment_type' => $request->payment_type,
            'payment_url' => route('checkout.index')
                . '?pid='
                . data_get(json_decode($responseData), "id")
                . '&sid=' . $request->student_id,
            'is_finished' => $request->has('is_finished') ? $request->is_finished : false,
        ]);

        $paymentUrl = route('checkout.index')
        . '?checkoutId='
        . data_get(json_decode($responseData), "id")
            . '&sid=' . $request->student_id
            . '&pid=' . $payment->id;

        $payment->update([
            'payment_url' =>  $paymentUrl
        ]);

        $studentPaymentUrl = route('checkout.index')
        . '?sid=' . $request->student_id
        . '&pid=' . $payment->id;
        $this->notifyStudent($payment->student?->email, $studentPaymentUrl);

        notify()->success('تم إنشاء الدفعة بنجاح.');
        return redirect()->route('dashboard.payments.index')->with('success', 'Payment created successfully.');
    }

    public function edit($id)
    {
        $title = 'تحديث الدفعة';
        $payment = Payment::findOrFail($id);
        $students = Student::all();
        $packages = Package::all();
        return view(
            'admin.payments.edit',
            compact('payment', 'title', 'students', 'packages')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'student_id' => 'required|exists:students,id',
            // 'package_id' => 'required|exists:packages,id',
            // 'payment_url' => 'nullbale|url',
            'is_finished' => 'sometimes',
        ]);

        $payment = Payment::findOrFail($id);
        // $payment->student_id = $request->student_id;
        // $payment->package_id = $request->package_id;
        // $payment->payment_type = $request->payment_type;
        // $payment->payment_url = $request->payment_url ?? $payment->payment_url;
        $payment->is_finished = $request->has('is_finished') ? true : false;

        $payment->save();

        notify()->success('تم تحديث الدفعة بنجاح.');
        return redirect()->route('dashboard.payments.index')->with('success', 'Payment updated successfully.');
    }

    protected function updatePaymentUrl($paymentId)
    {

        $payment = Payment::with('package')->find($paymentId);

        try {

            $responseData = $this->createCheckoutId($payment->package?->total);

            $payment->update([
                'payment_url' => route('checkout.index')
                    . '?checkoutId='
                    . data_get(json_decode($responseData), "id")
                    . '&sid=' . $payment->student_id
                    . '&pid=' . $payment->id
            ]);

            notify()->success('تم تحديث رابط الدفعة', 'نجاح');
        } catch (\Throwable $th) {
            notify()->error('لم يتم تحديث رابط الدفعة', 'فشل');
        }

        return back();
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->transactions()->delete();

        Helper::tryDelete($payment);

        return redirect()->route('dashboard.payments.index')->with('success', 'Payment deleted successfully.');
    }


    public function createCheckoutId($total_price)
    {

        $entitiy_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');

        $url = "https://eu-test.oppwa.com/v1/checkouts";
        $data = 'entityId=' . $entitiy_id . "&amount=" . $total_price . "&currency=SAR" . "&paymentType=DB";

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

    public function notifyStudent($email, $paymentUrl)
    {

        Mail::html("
        <p>شكرًا للاشتراك في متكلم.</p>
        <p>لقد قمنا بإنشاء رابط دفع لك. يمكنك الدفع من خلال الرابط أدناه:</p>
        <p><a href=\"{$paymentUrl}\" target=\"_blank\">اضغط هنا للدفع</a></p>
        <br>
        <p>للمزيد من المعلومات، يرجى زيارة موقعنا على:</p>
        <p><a href=\"" . url('/') . "\" target=\"_blank\">" . url('/') . "</a></p>
    ", function ($message) use ($email) {
            $message->to($email)
                ->subject('شكرًا للاشتراك في متكلم');
        });
    }
}
