<?php

namespace App\Actions\Paymob;

use App\Actions\HyperPay\ScheduleRecurringPayment;
use App\Actions\HyperPay\StoreRecurringPaymentData;
use App\Actions\Paymob\GetAuthToken;
use App\Actions\Paymob\GetPaymentToken;
use Lorisleiva\Actions\ActionRequest;
use App\Http\Controllers\Api\JoinController;
use App\Models\InstallmentPayment;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Student;
use App\Services\JoinService;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

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

        if (Package::where('id',  request()->package_id)
            ->where('payment_type', Package::INSTALLMENTS)->exists()
        ) {

            $rules = [
                'card'=> 'required|array',
                'billing'=> 'required|array'
            ];
        }
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

            $package = Package::find($request->package_id);

            DB::commit();

            if ($package->payment_type == Package::ONE_TIME) { # create a one time payment

                $payment = $this->createOneTimePaymentUrl($student->id, $request->package_id);
            } else {

                # Reassign package to user if schedule payment failed
               InstallmentPayment::where('student_id', $student->id)
               ->whereNull('registration_id')?->first()?->delete();

              return  $this->createScheduledPayment($student->id, $request->package_id, $student, $request->all());
            }

            $this->joinController->notifyClient($contract);

            $response = [
                'status' => 1,
                'message' => 'success generate hyperpay url',
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => route('checkout.index') . '?pid=' . $payment?->id . '&sid=' . $student?->id
                ],
            ];

            if ($package->payment_type == Package::INSTALLMENTS)
            {

                $response = [
                    'status' => 1,
                    'message' => 'subscribed successfully',
                    'payload' => [
                        'payment_token' => '',
                        'hyper-pay-payment-page' => ''
                    ],
                ];
        }

            return response()->json($response, 200);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return [
        //         'status' => 0,
        //         'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى لاحقًا.',
        //     ];
        // }
    }

    protected function createOneTimePaymentUrl($stID, $pckID)
    {

        return Payment::firstOrcreate([
            'student_id' => $stID,
            'package_id' => $pckID,
        ], [
            'student_id' => $stID,
            'package_id' => $pckID,
            'payment_url' => '#'
        ]);
    }

    protected function createScheduledPayment($stID, $pckID, $student, $data)
    {

        $installmentPayment = InstallmentPayment::firstOrcreate([

                'student_id' => $stID,
                'package_id' => $pckID,
            ], [

            'student_id' => $stID,
            'package_id' => $pckID,

        ]);

        if($installmentPayment->wasRecentlyCreated && isNull($installmentPayment->registration_id)) {

                $response = StoreRecurringPaymentData::make()
                ->handle(
                    $installmentPayment?->package,
                    $installmentPayment,
                    $student,
                    $data
                );

                if(data_get(data_get(json_decode($response), 'result'), 'code') == '000.100.112'){

                        $installmentPayment->update([
                            'registration_id'=>data_get(json_decode($response), 'registrationId'),
                            'payment_id'=>data_get(json_decode($response), 'id')
                        ]);

                        $this->schedulePayment($installmentPayment); # shcedula payments for the student

                        $response = [
                            'status' => 1,
                            'message' => 'شكرا لانضمامك الينا ! تم إنضمامك الي الباقة بنجاح  ',
                            'payload' => [
                                'success'=> [
                                    'code'=> data_get(data_get(json_decode($response), 'result'), 'code') ,
                                    'message'=>data_get(data_get(json_decode($response), 'result'), 'description')
                                    ]
                            ],
                        ];
                }else {


                    $response = [
                        'status' => 0,
                        'message' => 'حدث خطأ اثناء اجراء عملية الدفع',
                        'payload' => [
                            'error'=> [
                                'code'=> data_get(data_get(json_decode($response), 'result'), 'code') ,
                                'message'=>data_get(data_get(json_decode($response), 'result'), 'description'),
                                'log'=> json_decode($response)

                                ]
                            ],
                    ];
                }
                return response()->json($response, 200);
            }else {

                $response = [
                    'status' => 0,
                    'message' => 'تم تسجيل بالباقة مسبقا',
                    'payload' => [],
                ];

                return response()->json($response, 200);
            }
        }

        protected function schedulePayment($installmentPayment)
        {

            ScheduleRecurringPayment::make()->handle(  $installmentPayment  );
        }
}
