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
use  App\Notifications\Admin\NotifyAdminWithTabbyNotification;

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
            'id_end' => ['nullable'],
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
        $normalizedPhone = preg_replace('/^966/', '+966', $normalizedPhone); // Also convert starting `966` to `+966`

        #TODO CHECK IF THE USER PAY FOR THIS PACKAGE WITH THE SAME PHONE NUMBER (CHANGE THE JSON TO GIVE ALERT)

        if (Student::query()->where('phone', $normalizedPhone)->exists()) {

            return $this->checkPaymentProblem($normalizedPhone);
        }

        DB::beginTransaction();

        // try {
        $request = request();
        $phone = $this->formatMobile($request->phone);

        $name = $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;

        $package = Package::query()->find($request->package_id);

        $student = Student::query()->firstOrCreate(
            ['phone' => $phone],
        [
            'name' => $name, 'email' => $request->email,
            'age' => $request->age, 'phone' => $phone,
            'city' => $request->city, 'payment_type' => $package->payment_type,
            'total_payment_amount' => $package->total??0,
            'package_id' => $package->id
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

        } elseif($package->payment_type == Package::INSTALLMENTS){

           return $this->createScheduledPayment($student->id, $request->package_id, $student, $request->all());
        } else { # then it is tabby

            try {

                $adminEmails = explode(',', env('ADMIN_EMAILS'));
                foreach ($adminEmails as $adminEmail) {

                    Notification::route('mail', $adminEmail)->notify(new NotifyAdminWithTabbyNotification($student, ''));
                }

            } catch (\Exception $e) {

                Log::error($e->getMessage());
            }

            $response = [
                'status' => 1,
                'message' => __('Your payment will be processed via Tabby. You will receive a payment link shortly.'),
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => '',
                ],
            ];

            return response()->json($response, 200);
        }

        if ($package->payment_type == Package::ONE_TIME) {

            $response = [
                'status' => 1,
                'message' => __('success generate hyperpay url'),
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => route('checkout.index') . '?pid=' . $payment?->id . '&sid=' . $student?->id,
                ],
            ];
        } else {
            $response = [
                'status' => 1,
                'message' => __('success generate hyperpay url'),
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
     * @return array
     */
    public function checkPaymentProblem($phone) #: array
    {
         $student = Student::query()
            ->where('phone', $phone)
            ->with(['parentContract.package', 'payment.transactions', 'installmentPayment'])
            ->first();

        if (!$student || !$student->parentContract || !$student->parentContract->package) {
            return [
                'status' => 0,
                'message' => __('Student or package not found')
            ];
        }

         $package = $student->parentContract->package;


        if ($package->payment_type == Package::ONE_TIME) {
            $payment = $student->payment;


            if ($payment) {
                $latestTransaction = $payment->transactions()->latest()->first();


                if ($latestTransaction && $latestTransaction->success == 'true') {
                    return [
                        'status' => 0,
                        'message' => __('User is already registered and has paid for this package')
                    ];
                }
            }

            try {

                Notification::route('mail', $student->email)
                    ->notify(new SentPaymentUrlNotification($student, $student->payment?->payment_url));

                return [
                    'status' => 1,
                    'message' => __('success generate hyperpay url'),
                    'payload' => [
                        'payment_token' => '#',
                        'hyperpay_payment' => $student->payment?->payment_url
                    ]
                ];
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return [
                    'status' => 0,
                    'message' => __('Error sending payment link')
                ];
            }

        }elseif($package->payment_type == Package::TABBY){

            return [
                'status' => 0,
                'message' => __('You are already registered with a Tabby package.') . ' - '. $package->name,
                'payload' => [
                    'payment_token' => '#',
                    'hyperpay_payment' => ''
                ]
            ];


        }else {

            $insP = $student->installmentPayment;

            if ($insP &&
                $insP->registration_id &&
                $insP->installments?->first()?->is_paid) {

                return [
                    'status' => 0,
                    'message' => __('User is already registered and paid first installment for this package'),
                    'payload' => [
                        'payment_token' => '#',
                        'hyperpay_payment' =>''
                    ]
                ];
            }

            try {

                    $payment_url = route('recurring.checkout', [
                        'paymentId' => $insP?->id,
                        'stdId' => $student->id
                    ]);

                    Notification::route('mail', $student->email)->notify(new SentPaymentUrlNotification($student, $payment_url));

                    return [
                        'status' => 1,
                        'message' => __('success generate hyperpay url and sent to your email'),
                        'payload' => [ 'payment_token' => '#',
                            'hyperpay_payment' => $payment_url ]
                    ];

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return [
                    'status' => 0,
                    'message' => __('Error setting up recurring payment')
                ];
            }
        }

        return [
            'status' => 0,
            'message' => __('Unable to process payment')
        ];
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
     * @return array
     */
    protected function createScheduledPayment($stID, $pckID)
    {

        $installmentPayment = InstallmentPayment::query()->firstOrcreate([
            'student_id' => $stID,
            'package_id' => $pckID,
        ], [

            'student_id' => $stID,
            'package_id' => $pckID,
        ]);

        $this->createInstallments($installmentPayment);


       return $response = [
            'status' => 1,
            'message' => __('successfully created checkout'),

            'payload' => [
                'payment_token' => '#',
                'hyperpay_payment' => route('recurring.checkout',
                    [ 'paymentId'=>$installmentPayment->id,'stdId'=> $installmentPayment->student_id]),
            ],
        ];

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
