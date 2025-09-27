<?php

namespace App\Http\Controllers\Dashboard\Center;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Center\CenterInstallmentPayment;
use App\Models\Center\CenterPackage;
use App\Models\Center\CenterPatient;
use App\Models\CenterPayment;


use App\Notifications\Admin\CenterPaymentUrlNotification;
use App\Traits\HelperTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class PatientsController extends AdminBaseController
{
    use HelperTrait;
    public function index(): View|Factory|Application
    {
        $title = 'المرضى';
        $search = request()->query('search');

        $query = CenterPatient::query();

        if ($search) {

            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('mobile_number', 'LIKE', "%{$search}%");
        }

        $patients = $query->orderBy('id', 'desc')->paginate(12);
        $patientsCount = CenterPatient::count();


        return view('admin.center-patients.index', compact('patients', 'title', 'patientsCount'));
    }

    public function create() #: View|Factory|Application
    {
        $title = 'إضافة بيانات المريض ';
          $centerPackages = CenterPackage::query()->get();

        return view('admin.center-patients.create', compact('title' ,'centerPackages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'mobile_number'     => ['required', 'regex:/^(0\d{9}|966\d{9})$/', 'unique:center_patients,mobile_number'],
            'email'             => 'required|email',
            'id_number'         => 'nullable|digits:10',
            'id_end_date'       => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (strtotime($value) <= strtotime(now())) {
                    $fail('يجب ان يكون تاريخ الإنتهاء لاحق لتاريخ اليوم');
                }
            }],
            'age'               => 'required|integer|min:0',
            'center_package_id' => 'required|exists:center_packages,id',
            'city'              => 'required|string',
            'payment_type'      => ['required', 'string', Rule::in([
                CenterPayment::PAYMENT_TYPE_ONE_TIME, 
                CenterPayment::PAYMENT_TYPE_INSTALLMENT
            ])],
        ]);

        $phone = $this->formatMobile($request->mobile_number);
        $validated = array_merge($validated, [
            'mobile_number' => $phone,
            'source' => CenterPatient::DASHBOARD,
        ]);
        
        $patient = CenterPatient::query()->create($validated);
        $package = CenterPackage::find($validated['center_package_id']);

        // Handle payment based on type
        if ($validated['payment_type'] === CenterPayment::PAYMENT_TYPE_ONE_TIME) {
          

            $payment = CenterPayment::create([
                'center_patient_id' => $patient->id,
                'center_package_id' => $validated['center_package_id'],
                'amount'            => $package->total,  
                'payment_type'      => CenterPayment::PAYMENT_TYPE_ONE_TIME,
                'status'            => CenterPayment::STATUS_PENDING,
                'is_finished'       => false,
                'payment_data'      => [
                    'created_via' => 'dashboard',
                    'admin_ip'    => request()->ip(),
                ],
            ]);

            $this->generateOneTimePayUrl($payment);
            notify()->success('Patient data and one-time payment created successfully.', 'Success');
            
        } else {
            // Create installment payment (existing logic)
            $installmentPayment = CenterInstallmentPayment::query()->create([
                'patient_id'        => $patient->id,
                'center_package_id' => $validated['center_package_id'],
                'canceled'          => false,
                'is_completed'      => false,
            ]);

            $this->createInstallments($installmentPayment);
            $this->generatePayUrl($installmentPayment);
            notify()->success('Patient data and installments created successfully.', 'Success');
        }

        return redirect()->route('dashboard.center.center-patients.index');
    }


   /**
     * Generate payment URL for one-time payments
     */
    public function generateOneTimePayUrl($centerPayment)
    {
        $patient = $centerPayment->centerPatient;

        $payid = $this->encrypt($centerPayment->id);
        $patid = $this->encrypt($centerPayment->center_patient_id);

        // You'll need to create a route for one-time payments
        $url = route('checkout.center.onetime.index', [
            'payid' => $payid,
            'patid' => $patid
        ]);

        try {
            Notification::route('mail', $patient?->email)
                ->notify(new CenterPaymentUrlNotification($patient, $url));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function edit($id): View|Factory|Application
    {
        $title = 'تعديل بيانات المريض';
        $patient = CenterPatient::findOrFail($id);

        return view('admin.center-patients.edit', compact('patient', 'title'));
    }

    public function update(Request $request, $id)#: RedirectResponse
    {
        $patient = CenterPatient::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'mobile_number'=> [
                'required', 'string', 'max:15',
                Rule::unique('center_patients', 'mobile_number')->ignore($id),
            ],
            'email'        => 'nullable|email|max:255',
            'id_number'    => 'nullable|string|max:50',
            'id_end_date'  => 'nullable|date',
            'age'          => 'required|integer|min:0|max:100',
            'city'      => 'required|string',
        ]);

          $validated = array_merge($validated, [
           'source' => CenterPatient::DASHBOARD,
        ]);

        $patient->update($validated);

        notify()->success('تم تحديث بيانات المريض بنجاح.', 'نجاح');
        return redirect()->route('dashboard.center.center-patients.index');
    }

    public function show($id): View|Factory|Application
    {
        $title = 'عرض بيانات المريض';
        $patient = CenterPatient::findOrFail($id);

        return view('admin.center-patients.show', compact('patient', 'title'));
    }

    public function destroy($id): RedirectResponse
    {
        CenterPatient::findOrFail($id)->delete();
        return redirect()->route('dashboard.center.center-patients.index')
            ->with('success', 'تم حذف المريض بنجاح.');
    }

    public function createInstallments($installmentPayment)
    {
        $package = $installmentPayment->centerPackage;

        $installmentAmounts = [
            $package->first_inst,
            $package->second_inst,
            $package->third_inst,
            $package->fourth_inst,
            $package->fifth_inst,
        ];

        foreach ($installmentAmounts as $index => $amount) {
            if ($amount > 0) {
                $installmentPayment->centerInstallments()->create([
                    'installment_amount' => $amount,
                    'installment_date'   => $index === 0 ? now() : now()->startOfMonth()->addMonths($index),
                    'is_paid'            => false,
                    'admin_ip'           => request()->ip(),
                ]);
            }
        }
    }

    /**
     * @param $centerInstallmentPayment
     * @return string
     */
    public function generatePayUrl($centerInstallmentPayment)
    {

        $patient = $centerInstallmentPayment?->patient;

        $payid =  $this->encrypt($centerInstallmentPayment->id);
        $patid =  $this->encrypt($centerInstallmentPayment->patient_id);

        $url = route('center.recurring.checkout', [
            'payid'=> $payid,
            'patid'=> $patid]);

            try {

                Notification::route('mail', $patient?->email)
                    ->notify(new CenterPaymentUrlNotification($patient, $url));

            } catch (\Exception $e) {

                Log::error($e->getMessage());
            }
    }


}
