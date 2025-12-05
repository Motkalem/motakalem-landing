<?php

namespace App\Actions\Paymob;

use App\Actions\Paymob\GetAuthToken;
use App\Actions\Paymob\GetPaymentToken;
 use Lorisleiva\Actions\ActionRequest;
use App\Http\Controllers\Api\JoinController;
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
        return [
            'name' => 'required|string',
            'age' => 'required|numeric|min:10|max:100',
            'phone' => 'required|digits:10|unique:students,phone', // Add unique rule for phone
            'email' => 'required|email',
            'city' => 'required|string',
            'clienttermsConsent' => 'required|boolean',
            'payment_type' => 'nullable|in:' . implode(',', array_values(Student::$paymentTypes)),
            'id_number' => 'required|digits:10|unique:parent_contracts,id_number',
            'id_end' => ['required', 'date', function ($attribute, $value, $fail) {
                if (strtotime($value) <= strtotime(now())) {
                    $fail('يجب ان يكون تاريخ الإنتهاء لاحق لتاريخ اليوم');
                }
            }],
        ];
    }

    public function handle(ActionRequest $request)#: array
    {
//        DB::beginTransaction();

        try {

            $clientOrderPay = Student::query()->create( [
                'name' => $request->name,
                'email' => $request->email,
                'age' => $request->age,
                'phone' => $request->phone,
                'city' => $request->city,
                'payment_type' => $request->payment_type ?? 'one_time',
                'total_payment_amount' => env('SUBSCRIPTION_AMOUNT', 12000),
            ]);

             $contract = $this->joinController->sendContract($clientOrderPay, $request->all());

            if (!isset($contract)) {
                DB::rollBack();
                return [
                    'status' => 0,
                    'message' => 'حدث خطأ أثناء معالجة العقد',
                ];
            }

            $token = GetAuthToken::make()->handle();

            $order = CreateOrder::make()->handle($clientOrderPay->id, $token);

            $paymentToken = GetPaymentToken::make()->handle($clientOrderPay->id, $order, $token, $clientOrderPay);

            DB::commit();

            $this->joinController->notifyClient($contract);

            return [
                'status' => 1,
                'payment_token' => 'https://ksa.paymob.com/api/acceptance/iframes/'
                    . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 0,
                'message' => 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى لاحقًا.',
            ];
        }
    }
}
