<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Api\JoinController;
use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\ParentContract;
use App\Models\Student;
use App\Notifications\SendContractNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PDF;
class StudentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'الطلاب';
        $search = request()->query('search');

        $query = Student::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
        }

        // Eager load relationships needed for Blade logic
        $students = $query->with([
            'installmentPayments.installments',
            'payments',
            'parentContract'
        ])
            ->orderBy('id', 'desc')
            ->paginate(12);

        $studentsCount = Student::count();

        return view('admin.students.index', compact('students','title','studentsCount'));
    }


    /**
     * @return Application|Factory|View
     */
    public function create()
    {

        $title = 'إضافة طالب جديد';
        return view('admin.students.create',compact( 'title'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'payment_type' => 'nullable|string|max:255',
            'total_payment_amount' => 'nullable|numeric',
            'age' => 'required|integer',
            'is_paid' => 'sometimes',
            'phone' => 'required|string|max:10|unique:students,phone',
            'city' => 'required|string|max:255',
        ]);

        $student = new Student([
            'name' => $request->name,
            'email' => $request->email,
            'payment_type' => $request->payment_type,
            'total_payment_amount' => $request->total_payment_amount??0,
            'age' => $request->age,
            'is_paid' => $request->has('is_paid') ? $request->is_paid : false,
            'phone' => $request->phone,
            'city' => $request->city,
        ]);

        $student->save();
        notify()->success('تم الإنشاء.', 'نجاح');
        return redirect()->route('dashboard.students.index')->with('success', 'Student created successfully.');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $title = ' تعديل طالب ';

        $student = Student::findOrFail($id);
        return view('admin.students.edit',
         compact('student','title'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required','email',Rule::unique('students', 'email')->ignore($id)],
            'phone' => ['required',Rule::unique('students', 'phone')->ignore($id)],
            'age' => 'required|integer',
            'city' => 'required|string|max:255',
        ]);

        $student = Student::findOrFail($id);
        $student->name = $request->name;
        $student->email = $request->email;
        $student->payment_type = $request->payment_type;
        $student->total_payment_amount = $request->total_payment_amount??0;
        $student->age = $request->age;
        $student->is_paid = $request->has('is_paid') ? $request->is_paid : false;
        $student->phone = $request->phone;
        $student->city = $request->city;

        $student->save();
        notify()->success('تم التحديث.','نجاح');
        return redirect()->route('dashboard.students.index')->with('success', 'Student updated successfully.');
    }

    public function show($id)
    {
        $title = 'عرض طالب ';

        $student = Student::with('installmentPayment', 'payment', 'package')
            ->findOrFail($id);

        return view('admin.students.show',
         compact('student','title'));
    }

    public function destroy($id)
    {
        $student = Student::with(['installmentPayments.installments', 'payments.transactions', 'parentContract'])
            ->findOrFail($id);

        // Prevent deletion if any installment payment is paid
        foreach ($student->installmentPayments as $payment) {
            if ($payment->installments->where('is_paid', 1)->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('Cannot delete student because some installment payments have been paid.')
                ], 403);
            }
        }
        // Prevent deletion if any one-time payment is paid
        foreach ($student->payments as $payment) {
            if ($payment->is_finished == 1) {
                return response()->json([
                    'success' => false,
                    'message' => __('Cannot delete student because payment have been paid.')
                ], 403);
            }
        }

        // Delete installment payments and installments
        foreach ($student->installmentPayments as $payment) {
            $payment->installments()->delete();
            $payment->delete();
        }

        // Delete one-time payments and transactions
        foreach ($student->payments as $payment) {
            $payment->transactions()->delete();
            $payment->delete();
        }

        // Delete parent contract if exists
        if ($student->parentContract) {
            $student->parentContract->delete();
        }

        // Delete student
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => __('Student and all related data deleted successfully.')
        ]);
    }




    public function sendContract($id)
    {

        $student = Student::query()->with(['parentContract'])->findOrFail($id);

        $contractData = [
            'email' => $student->email,
            'name' => $student->name,
            'age' => $student->age,
            'phone' => $student->phone,
            'city' => $student->city,
            'id_number' => $student->id_number ?? $student->parentContract?->id_number,
            'id_end' => $student->id_end,
            'package_id' => $student->package_id,
        ];

        // Create the contract and handle potential exceptions
        try {

            $contract = ParentContract::query()->with('course')->create(array_merge($contractData, ['accept_terms']));

            $contract = $contract->load('package');

            Notification::route('mail', $contract->email)->notify(new SendContractNotification($contract));

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال العقد بنجاح!', // Success message in Arabic
            ]);
        } catch (\Exception $e) {
            // Log the error and return a failure response
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال العقد: ' . $e->getMessage(), // Error message in Arabic
            ], 500); // 500 Internal Server Error
        }
    }

    public function downloadContract($id)
    {

        $contract = ParentContract::findOrFail($id);
        $oldPdfBasePath = 'students/contract-' . $contract->id;

        $existingFiles = Storage::disk('public')->files('students');

        foreach ($existingFiles as $file) {
            if (str_contains($file, $oldPdfBasePath)) {
                Storage::disk('public')->delete($file);
            }
        }


        $pdf = PDF::loadView('pdf.contract-pdf', ['data'=>$contract]);

        $filename = 'contract-' . $contract->id  . '.pdf';

        $pdfPath = 'students/' . $filename;

        Storage::disk('public')->put($pdfPath, $pdf->output());

        return Redirect::away(Storage::url($pdfPath));
    }

    public function payManually($id)
    {
        $student = Student::findOrFail($id);

        // Update the student to mark as paid manually
        $student->update([
            'is_paid' => true,
        ]);

        $this->sendContract($id);
        notify()->success('تم تأكيد الدفع اليدوي للطالب بنجاح.','نجاح');

        return redirect()->back();
    }

}
