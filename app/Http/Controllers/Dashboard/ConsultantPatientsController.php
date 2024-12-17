<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\HyperpayNotificationProcessor;
use App\Http\Support\SMS;
use App\Models\ConsultantPatient;
use App\Models\ConsultantType;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use App\Traits\HelperTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ConsultantPatientsController extends AdminBaseController
{
    use HelperTrait;

    /**
     * @return Application|Factory|View
     */
    public function index()
    {

         $consultantPatients = ConsultantPatient::query()->orderBy('id', 'desc')->paginate(12);
        $consultantPatientsCount = ConsultantPatient::query()->count();
        $title = 'قائمة المرضى';

        return view('admin.consultant-patients.index', compact('consultantPatients',
            'title', 'consultantPatientsCount'));
    }

    public function create()
    {
        $title = 'إضافة مريض جديد';
        $consultationTypes = ConsultantType::all();

        return view('admin.consultant-patients.create', compact('title', 'consultationTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'consultation_type_id' => 'required|exists:consultant_types,id',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'mobile' => 'required|string|max:15',
            'city' => 'required|string|max:255',
        ]);

        $formattedMobile = $this->formatMobile($request->input('mobile'));

        $data = array_merge($data, ['mobile' => $formattedMobile]);

        ConsultantPatient::query()->create($data);

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

        $msg = 'عزيزي العميل، يرجى استخدام الرابط التالي لدفع تكلفة الاستشارة: ' . $paymentLink;

//         (new SMS())->setPhone($consultantPatient->mobile)->SetMessage($msg)->build();

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

        if (request()->payment_method == 'MADA') {

            $entity_id = env('ENTITY_ID_MADA'); //mada
        }

        $access_token = env('AUTH_TOKEN');

        $url = env('HYPERPAY_URL') . "/checkouts";

        $data = 'entityId='
            . $entity_id
            . "&amount=" . $consultationPatient->consultationType?->price
            . "&currency=SAR"
            . "&paymentType=DB" .
            "&merchantTransactionId=" . $consultationPatient->id .
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
        $entity_id = config('hyperpay.entity_id');
        $access_token = config('hyperpay.access_token');

        $url = env('HYPERPAY_URL') . "/checkouts/" . $_GET['id'] . "/payment";
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

        $consultationPatient = ConsultantPatient::query()->with('consultationType')->find(request()->pid);

        if (data_get($transactionData, 'id') == null) {


        }

        $this->createTransactions($consultationPatient, $title,$transactionData);

        if ($this->isSuccessfulNotification($transactionData) ) {

            $this->markPaymentAsCompleted($consultationPatient);

            $invoicetLink = route('checkout.send-sms-invoice-link', $consultationPatient->id);

            $msg = 'شكرا تمت عملية الدفع : ' . $invoicetLink;

//            (new SMS())->setPhone($consultationPatient->mobile)->SetMessage($msg)->build();

          return $this->getInvoice($consultationPatient->id);
        } else {

            echo "<h2 style='text-align: center; color: red;padding-top: 20px'> !  لم تنجح عملية الدفع </h2>";
            echo "<a href='https://motkalem.sa' style='text-align: center; padding-top: 20px;display: block'> الرئيسية ! </a>";
        }
    }

    /**
     * @param $consultationPatient
     * @return Application|Factory|View
     */
    protected function getInvoice($id)
    {
        $consultationPatient = ConsultantPatient::where('is_paid', 1)->where('id', $id)->firstOrfail();

        return \view('payments.consultation-pay-thank-you',compact( 'consultationPatient'));
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

//        (new SMS())->setPhone($consultationPatient->mobile)->SetMessage($msg)->build();

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

