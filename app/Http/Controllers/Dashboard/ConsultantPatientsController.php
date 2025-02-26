<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\HyperpayNotificationProcessor;
use App\Http\Support\SMS;
use App\Models\ConsultantPatient;
use App\Models\ConsultantType;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use App\Notifications\Admin\HyperPayNotification;
use App\Traits\HelperTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class ConsultantPatientsController extends AdminBaseController
{
    use HelperTrait;

    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $search = $request->query('search'); // Get the 'search' parameter from the URL query string

        $query = ConsultantPatient::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%");
        }

        $consultantPatients = $query->orderBy('id', 'desc')->paginate(12);
        $consultantPatientsCount = $query->count();
        $title = 'قائمة المرضى';

        return view('admin.consultant-patients.index', compact('consultantPatients', 'title', 'consultantPatientsCount', 'search'));
    }


    public function create()
    {
        $title = 'إضافة مريض جديد';

        $consultationTypes = ConsultantType::where('is_active',true)->get();

        return view('admin.consultant-patients.create', compact('title', 'consultationTypes'));
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'consultation_type_id' => 'required|exists:consultant_types,id',
            'mobile' => 'required|string|max:15',
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female',
            'city' => 'nullable|string|max:255',
        ]);

        $formattedMobile = $this->formatMobile($request->input('mobile'));

        $data = array_merge($data, ['mobile' => $formattedMobile]);

        if ($request->expectsJson())
            $data = array_merge($data, ['source'=> 'campaign']);

        $patient = ConsultantPatient::query()->create($data);

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'message' => __('Registered successfully.'),
                'data' => $patient,
                'payment_url' => $this->generatePaymentLink($patient),
            ], 201);
        }

        notify()->success('تم إضافة المريض بنجاح.');

        return redirect()->route('dashboard.consultant-patients.index')->with('success', 'Patient created successfully.');
    }

    public function edit($id)
    {
        $title = 'تحديث بيانات المريض';
        $consultantPatient = ConsultantPatient::findOrFail($id);
        $consultationTypes = ConsultantType::all();
        return view('admin.consultant-patients.create', compact('consultantPatient',
            'title', 'consultationTypes'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $consultantPatient = ConsultantPatient::findOrFail($id);

        $request->validate([
            'consultation_type_id' => 'required|exists:consultant_types,id',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'mobile' => 'required|string|max:15',
            'city' => 'required|string|max:255',
        ]);

        $consultantPatient->update($request->only(['consultation_type_id', 'name', 'age', 'gender', 'mobile', 'city']));

        notify()->success('تم تحديث بيانات المريض بنجاح.');

        return redirect()->route('dashboard.consultant-patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $consultantPatient = ConsultantPatient::findOrFail($id);

        $consultantPatient->delete();

        notify()->success('تم حذف المريض بنجاح.');

        return redirect()->route('dashboard.consultant-patients.index')->with('success', 'Patient deleted successfully.');
    }


    public function sendPaymentLink($id)
    {
        $consultantPatient = ConsultantPatient::findOrFail($id);

        $paymentLink = $this->generatePaymentLink($consultantPatient);

        $msg = 'عزيزي المراجع،

لحجز موعدك وتأكيده في مركز متكلم الطبي للسمعيات، يرجى استخدام الرابط التالي لإتمام عملية الدفع:

' . $paymentLink . '

لأي استفسارات، لا تتردد في التواصل معنا
نسعد بخدمتك';

         (new SMS())->setPhone($consultantPatient->mobile)->SetMessage($msg)->build();

        return redirect()->route('dashboard.consultant-patients.index')
            ->with('success',  'تم إرسال رابط الدفع بنجاح');
    }


    /**
     * @param ConsultantPatient $consultantPatient
     * @return string
     */
    private function generatePaymentLink(ConsultantPatient $consultantPatient)
    {

        $patientPaymentUrl = route('checkout.consultation.index')
            . '?pid=' . $consultantPatient->id;

        return $patientPaymentUrl;
    }

    public function getPayPage()
    {

        $consultantPatient = ConsultantPatient::query()->findOrFail(request()->pid);

        $responseData = null;

        try {

            $responseData = $this->createCheckoutId($consultantPatient);

        } catch (\Throwable $th) {

            throw $th;
        }

        $paymentId = data_get(json_decode($responseData), "id");

        return view('payments.consultation-pay', compact('consultantPatient', 'paymentId'));
    }

    /**
     * @param $payment
     * @return bool|string
     */
    public function createCheckoutId($consultationPatient): bool|string
    {

        $entity_id = config('hyperpay.entity_id');

        if (request()->brand == 'mada') {

            $entity_id = env('ENTITY_ID_MADA'); //MADA
        }

        $access_token = env('AUTH_TOKEN');

        $url = env('HYPERPAY_URL') . "/checkouts";

        $timestamp = Carbon::now()->timestamp;
        $micro_time = microtime(true);

        $unique_transaction_id = $consultationPatient->id .'-'.$timestamp . str_replace('.', '', $micro_time);

        $data = 'entityId='
            . $entity_id
            . "&amount=" . $consultationPatient->consultationType?->price
            . "&currency=SAR"
            . "&paymentType=DB" .
            "&merchantTransactionId=" . $unique_transaction_id .
            "&customer.email=" . $consultationPatient?->email .
            "&billing.street1=" . $consultationPatient?->city .
            "&billing.city=" . $consultationPatient?->city .
            "&billing.state=" . $consultationPatient?->city .
            "&billing.country=" . "SA" .
            "&billing.postcode=" . "" .
            "&customer.givenName=" . $consultationPatient?->name .
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
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
        $entity_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');

        $url = env('HYPERPAY_URL') . "/checkouts/" . data_get($_GET,'id') . "/payment";
        $url .= "?entityId=" . $entity_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
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

        $consultationPatient = ConsultantPatient::query()->with('consultationType')->find(request()->pid);

        if (data_get($transactionData, 'id') == null) {


        }

        $this->createTransactions($consultationPatient, $title,$transactionData);

        if ($this->isSuccessfulNotification($transactionData) ) {

            $this->markPaymentAsCompleted($consultationPatient);

            $invoicetLink = route('checkout.send-sms-invoice-link', $consultationPatient->id);

            $msg = 'شكرا تمت عملية الدفع : ' . $invoicetLink;

            $this->notifyAdmin($consultationPatient);

            (new SMS())->setPhone($consultationPatient->mobile)->SetMessage($msg)->build();

          return $this->getInvoice($consultationPatient->id);
        } else {

            echo "<h2 style='text-align: center; color: red;padding-top: 20px'> !  لم تنجح عملية الدفع </h2>";
            echo "<a href='https://motkalem.sa' style='text-align: center; padding-top: 20px;display: block'> الرئيسية ! </a>";
        }
    }

    /**
     * Notify the admin via email.
     *
     * @param $notification
     * @return void
     */


    /**
     * @param $consultationPatient
     * @return void
     */
    protected function notifyAdmin($consultationPatient): void
    {
        try {
            $adminEmails = explode(',', env('ADMIN_EMAILS'));
            foreach ($adminEmails as $adminEmail) {

                $result = "تمت المعاملة بنجاح !";
                $subject = 'تنبيه بخصوص دفع استشارة';

                $transactionData = $consultationPatient->transaction_data;
                $transactionDetails = json_encode($transactionData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

                $logoUrl = 'https://motkalem.sa/assets/img/new-logo-colored.png'; // Update with your logo URL

                $htmlBody = "
            <div style='text-align: right; direction: rtl;font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0;'>
                <!-- Email Header -->
                <div style=' text-align: center; padding: 10px 0;'>
                    <img src='{$logoUrl}' alt='Motkalem Logo' style='text-align: right;height: 60px;'>
                </div>

                <!-- Email Content -->
                <div style='text-align: right;background-color: #ffffff; padding: 30px; border-radius: 8px; margin: 20px auto; max-width: 600px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);'>
                    <h2 style='text-align: rightcolor: #2d3748; text-align: center; margin-bottom: 20px;'>نتيجة العملية: <span style='text-align: rightcolor: #28a745;'>{$result}</span></h2>

                    <table style='text-align: right; width: 100%; font-size: 14px; line-height: 1.6; color: #555; border-collapse: collapse; margin-bottom: 20px;'>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>اسم المريض:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->name}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>الإستشارة:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->consultationType?->name}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>السعر:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->consultationType?->price} ريال</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>العمر:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->age}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>المدينة:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->city}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>رقم الهاتف:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->mobile}</td>
                        </tr>
                    </table>
                    <div style=' text-align: center; margin-top: 30px;'>
                        <a href='https://motkalem.sa' style='background-color: #06A996; color: #ffffff;
                         text-decoration: none; padding: 12px 25px; font-size: 16px;
                          border-radius: 5px; display: inline-block;'>   الرئيسية  </a>
                    </div>
                </div>

                <!-- Footer -->
                <div style='text-align: center; font-size: 12px; color: #888; margin-top: 20px;'>
                    <p>© 2024 Motkalem. جميع الحقوق محفوظة.</p>
                </div>
            </div>
            ";

                Mail::html($htmlBody, function ($message) use ($adminEmail, $subject) {
                    $message->to($adminEmail)
                        ->subject($subject);
                });
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    /**
     * @param $consultationPatient
     * @return Application|Factory|View
     */
    protected function getInvoice($id)
    {
         $consultationPatient = ConsultantPatient::where('is_paid', 1)->where('id', $id)->firstOrfail();



        $data =  $consultationPatient->transaction_data ;

        $timestamp = '';
        foreach ($data as $key => $details) {
            $time = data_get($details, 'timestamp');
            $timestamp =  $time;
            break;
        }


        return \view('payments.consultation-pay-thank-you',compact( 'consultationPatient', 'timestamp'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    protected function sendInvoice($id)
    {
        $consultationPatient = ConsultantPatient::find($id);

        $invoicetLink = route('checkout.send-sms-invoice-link', $consultationPatient->id);

         $msg = 'شكرا تمت عملية الدفع : ' . $invoicetLink;

        (new SMS())->setPhone($consultationPatient->mobile)->SetMessage($msg)->build();

        return redirect()->route('dashboard.consultant-patients.index')
            ->with('success',  'تم إرسال رابط الفاتورة بنجاح');
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
     * @param $consultationPatient
     * @param $data
     * @return mixed
     */
    public function createTransactions($consultationPatient, $title, $data): mixed
    {

        $data = array_merge($consultationPatient->transaction_data??[],[$title=> $data]);

        return $consultationPatient->update(['transaction_data' =>  $data]);
    }

    /**
     * @param $consultationPayment
     * @return mixed
     */
    private function markPaymentAsCompleted($consultationPayment): mixed
    {

        $consultationPayment->update(['is_paid' => true]);

        return $consultationPayment->update([

            'is_paid' => true,
        ]);
    }
}

