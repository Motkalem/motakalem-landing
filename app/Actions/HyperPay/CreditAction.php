<?php

namespace App\Actions\HyperPay;

use App\Actions\HyperPay\StoreRecurringPaymentData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use App\Http\Controllers\Api\JoinController;
use App\Models\InstallmentPayment;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Student;
use App\Services\JoinService;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;

class CreditAction
{
    use AsAction;

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
            'name' => 'required|string',
            'age' => 'required|numeric|min:10|max:100',
            'phone' => 'required|digits:10',
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

    public function handle(ActionRequest $request)
    {

        DB::beginTransaction();

        // try {
        $request = request();
        $contract = $this->joinController->sendContract($request);
        if (!isset($contract)) {
            DB::rollBack();
            return [
                'status' => 0,
                'message' => 'حدث خطأ أثناء معالجة العقد',
            ];
        }

        $student = Student::query()->firstOrCreate([
            'phone' => $request->phone,
        ], [
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'phone' => $request->phone,
            'city' => $request->city,
            'payment_type' => $request->payment_type ?? Package::ONE_TIME,
            'total_payment_amount' => env('SUBSCRIPTION_AMOUNT', 12000),
        ]);

        $package = Package::query()->find($request->package_id);

        DB::commit();

        if ($package->payment_type == Package::ONE_TIME) {


            $payment = $this->createOneTimePaymentUrl($student->id, $request->package_id);
        } else {

            InstallmentPayment::query()->where('student_id', $student->id)
                ->whereNull('registration_id')?->first()?->delete();

            return $this->createScheduledPayment($student->id, $request->package_id,
                $student, $request->all());
        }

        $this->joinController->notifyClient($contract);

        if ($package->payment_type == Package::ONE_TIME) {

            $response = [
                'status' => 1,
                'message' => 'success generate hyperpay url',
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => route('checkout.index') . '?pid=' . $payment?->id . '&sid=' . $student?->id
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
     * @param $stID
     * @param $pckID
     * @return Model|Builder
     */
    protected function createOneTimePaymentUrl($stID, $pckID): Model|Builder
    {

        return Payment::query()->firstOrcreate([
            'student_id' => $stID,
            'package_id' => $pckID,
        ], [
            'student_id' => $stID,
            'package_id' => $pckID,
            'payment_url' => '#'
        ]);
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

        $installmentPayment = InstallmentPayment::query()->firstOrcreate([

            'student_id' => $stID,
            'package_id' => $pckID,
        ], [

            'student_id' => $stID,
            'package_id' => $pckID,

        ]);

        if ($installmentPayment->wasRecentlyCreated && ($installmentPayment->registration_id == null)) {


            $response = StoreRecurringPaymentData::make()
                ->handle(
                    $installmentPayment?->package,
                    $installmentPayment,
                    $student,
                    $data
                );

            if (data_get(data_get($response, 'result'), 'code') == '000.200.100') {

                $response = [
                    'status' => 1,
                    'message' => 'successfully created checkout',
                    'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => route('recurring.checkout', data_get($response, 'id'))
                        .'?paymentId='.$installmentPayment->id,
                        'data'=> $response
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
}
