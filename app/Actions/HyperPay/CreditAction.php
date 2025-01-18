<?php

namespace App\Actions\HyperPay;

use App\Notifications\Admin\HyperPayNotification;
use App\Notifications\SentPaymentUrlNotification;
use App\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\ActionRequest;
use App\Http\Controllers\Api\JoinController;
use App\Models\InstallmentPayment;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Student;
use App\Services\JoinService;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class CreditAction
{
    use AsAction, HelperTrait;

    protected JoinService $joinService;
    protected JoinController $joinController;

    public function __construct(JoinService $joinService, JoinController $joinController)
    {
        $this->joinService = $joinService;
        $this->joinController = $joinController;
    }

    public function rules(): array
    {

        $rules = [
            'package_id' => 'required|exists:packages,id',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'age' => 'required|numeric|min:10|max:100',
            'phone' => ['required', 'regex:/^(0\d{9}|966\d{9})$/', 'unique:students,phone'],
            'email' => 'required|email',
            'city' => 'required|string',
            'clienttermsConsent' => 'required|boolean',
            'payment_type' => 'nullable|in:' . implode(',', array_values(Student::$paymentTypes)),
            'id_number' => 'required|digits:10',
            'id_end' => ['required', 'date', function ($attribute, $value, $fail) {
                if (strtotime($value) <= strtotime(now())) {
                    $fail('يجب ان يكون تاريخ الإنتهاء لاحق لتاريخ اليوم');
                }
            }],
        ];

        return $rules;
    }

    /**
     * @param ActionRequest $request
     * @return array|JsonResponse
     * @throws ValidationException
     */
    public function handle(ActionRequest $request)
    {

        $normalizedPhone = preg_replace('/^0/', '+966', request()->phone); // Convert starting `0` to `+966`

        if (Student::query()->where('phone', $normalizedPhone)->exists()) {

            $this->checkPaymentProblem($normalizedPhone);

            throw ValidationException::withMessages([
                'phone' => __('The phone number already exists.'),
            ]);
        }

        DB::beginTransaction();

        // try {
        $request = request();
        $phone = $this->formatMobile($request->phone);


        $name = $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;

        $package = Package::query()->find( $request->package_id);

        $student = Student::query()->firstOrCreate([
            'phone' => $phone,
        ],
            [
                'name' => $name,
                'email' => $request->email,
                'age' => $request->age,
                'phone' => $phone,
                'city' => $request->city,
                'payment_type' => $package->payment_type,
                'total_payment_amount' => $package->total??0,
            ]);

        $data= request()->all();

        $contract = $this->joinController->sendContract($student, $request->package_id, $data);

        if (!isset($contract)) {
            DB::rollBack();
            return [
                'status' => 0,
                'message' => 'حدث خطأ أثناء معالجة العقد',
            ];
        }

        $package = Package::query()->find($request->package_id);

        DB::commit();

        if ($package->payment_type == Package::ONE_TIME) {

            $payment = $this->createOneTimePaymentUrl($student->id, $request->package_id);
        } else {

            return $this->createScheduledPayment($student->id, $request->package_id, $student, $request->all());
        }

        if ($package->payment_type == Package::ONE_TIME) {

            $response = [
                'status' => 1,
                'message' => 'success generate hyperpay url',
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => route('checkout.index') . '?pid=' . $payment?->id . '&sid=' . $student?->id,
                ],
            ];

        } else {

            $response = [
                'status' => 1,
                'message' => 'success generate hyperpay url',
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => route('checkout.index') . '?pid=' . $payment?->id . '&sid=' . $student?->id
                ],
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * @param $phone
     * @return void
     */
    public function checkPaymentProblem($phone) #: void
    {
        $student = Student::query()->where('phone', $phone)->first();
        if (is_null($student?->package_id) && $student->payment) {
            try {

                Notification::route('mail', $student->email)
                    ->notify(new SentPaymentUrlNotification($student, $student->payment?->payment_url));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

        }


        if (is_null($student?->package_id) && $student->installmentPayment) {

            try {
                $response = StoreRecurringPaymentData::make()
                    ->handle(
                        $student->installmentPayment?->package,
                        $student->installmentPayment,
                        $student,
                        $student?->toArray() ?? []);


                if (data_get($response, 'id')) {

                    $payment_url = route('recurring.checkout', data_get($response, 'id'))
                        . '?paymentId=' . $student->installmentPayment?->id;

                    Notification::route('mail', $student->email)
                        ->notify(new SentPaymentUrlNotification($student, $payment_url));
                }

            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

        }
    }

    /**
     * @param $stID
     * @param $pckID
     * @return Model|Builder
     */
    protected function createOneTimePaymentUrl($stID, $pckID): Model|Builder
    {

        $payment = Payment::query()->firstOrcreate([
            'student_id' => $stID,
            'package_id' => $pckID,
        ], [
            'student_id' => $stID,
            'package_id' => $pckID,
            'payment_url' => '#'
        ]);

        $studentPaymentUrl = route('checkout.index')
            . '?sid=' . $stID
            . '&pid=' . $payment->id;

        $payment->update([
            'payment_url' => $studentPaymentUrl
        ]);

        return $payment;
    }

    /**
     * @param $stID
     * @param $pckID
     * @param $student
     * @param $data
     * @return JsonResponse
     */
    protected function createScheduledPayment($stID, $pckID, $student, $data): JsonResponse
    {
        InstallmentPayment::query()->where('student_id', $stID)
            ->whereNull('registration_id')?->first()?->delete();

        $installmentPayment = InstallmentPayment::query()->firstOrcreate([
            'student_id' => $stID,
            'package_id' => $pckID,
        ], [

            'student_id' => $stID,
            'package_id' => $pckID,
        ]);

        $this->createInstallments($installmentPayment);

        if ($installmentPayment->wasRecentlyCreated && ($installmentPayment->registration_id == null)) {
            $response = StoreRecurringPaymentData::make()
                ->handle(
                    $installmentPayment?->package,
                    $installmentPayment,
                    $student,
                    $data);

            if (data_get(data_get($response, 'result'), 'code') == '000.200.100') {

                $response = [
                    'status' => 1,
                    'message' => 'successfully created checkout',
                    'payload' => [
                        'payment_token' => '#',
                        'hyperpay_payment' => route('recurring.checkout', data_get($response, 'id'))
                        . '?paymentId=' . $installmentPayment->id,
                        'data' => $response
                    ],
                ];
            } else {

                $response = [
                    'status' => 0,
                    'message' => 'حدث خطأ',
                    'payload' => $response,
                ];
            }
            return response()->json($response, 200);
        } else {

            $response = [
                'status' => 0,
                'message' => 'تم التسجيل بالباقة مسبقا',
                'payload' => [],
            ];
            return response()->json($response, 200);
        }
    }

    /**
     * @param $installmentPayment
     * @return void
     */
    public function createInstallments($installmentPayment)
    {
        $package = $installmentPayment->package;

        $installmentAmounts = [
            $package->first_inst,
            $package->second_inst,
            $package->third_inst,
            $package->fourth_inst,
            $package->fifth_inst
        ];

        foreach ($installmentAmounts as $index => $amount) {
            if ($amount > 0) {
                $installmentPayment->installments()->create([
                    'installment_amount' => $amount,
                    'installment_date' => $index === 0 ? now() : now()->startOfMonth()->addMonths($index), // First installment is now
                    'is_paid' => false
                ]);
            }
        }
    }
}
