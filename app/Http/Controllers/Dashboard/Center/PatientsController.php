<?php

namespace App\Http\Controllers\Dashboard\Center;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Center\CenterInstallmentPayment;
use App\Models\Center\CenterPackage;
use App\Models\Center\CenterPatient;
use App\Models\Center\MedicalInquiry;
use App\Models\InstallmentPayment;
use App\Models\Package;
use App\Models\Student;
use App\Notifications\Admin\CenterPaymentUrlNotification;
use App\Notifications\SentPaymentUrlNotification;
use App\Traits\HelperTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;

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

    public function store(Request $request)#: RedirectResponse
    {
        $validated = $request->validate([
            'source'            => 'nullable|string|max:255',
            'name'              => 'required|string|max:255',
            'mobile_number' => ['required', 'regex:/^(0\d{9}|966\d{9})$/', 'unique:medical_inquiries,mobile_number'],
            'email' => 'required|email',
            'id_number' => 'nullable|digits:10',
            'id_end_date' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (strtotime($value) <= strtotime(now())) {
                    $fail('يجب ان يكون تاريخ الإنتهاء لاحق لتاريخ اليوم');
                }
            }],
            'age'               => 'required|integer|min:0',
            'message'           => 'nullable|string',
            'center_package_id' => 'required|exists:center_packages,id',
        ]);

        $phone = $this->formatMobile($request->mobile_number);
        $validated = array_merge($validated, ['mobile_number' => $phone]);
        $patient = CenterPatient::create($validated);

        $installmentPayment = CenterInstallmentPayment::create([
            'patient_id'        => $patient->id,
            'center_package_id' => $validated['center_package_id'],
            'canceled'          => false,
            'is_completed'      => false,
        ]);

        $this->createInstallments($installmentPayment);
        $this->generatePayUrl($installmentPayment);
        notify()->success('Patient data and installments created successfully.', 'Success');

        return redirect()->route('dashboard.center.center-patients.index');
    }


    public function edit($id): View|Factory|Application
    {
        $title = 'تعديل بيانات المريض';
        $patient = CenterPatient::findOrFail($id);

        return view('admin.center-patients.edit', compact('patient', 'title'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $patient = CenterPatient::findOrFail($id);

        $validated = $request->validate([
            'source'       => 'nullable|string|max:255',
            'name'         => 'required|string|max:255',
            'mobile_number'=> [
                'required', 'string', 'max:15',
                Rule::unique('medical_inquiries', 'mobile_number')->ignore($id),
            ],
            'email'        => 'nullable|email|max:255',
            'id_number'    => 'nullable|string|max:50',
            'id_end_date'  => 'nullable|date',
            'age'          => 'required|integer|min:0',
            'message'      => 'nullable|string',
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

        $url = route('center.recurring.checkout', [
            'payid'=>$centerInstallmentPayment->id,
            'patid'=> $centerInstallmentPayment->patient_id]);

            try {

                Notification::route('mail', $patient?->email)
                    ->notify(new CenterPaymentUrlNotification($patient, $url));

            } catch (\Exception $e) {

                Log::error($e->getMessage());
            }
    }


}
