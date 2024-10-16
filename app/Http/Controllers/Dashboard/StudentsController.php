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
use Illuminate\Validation\Rule;

class StudentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'الطلاب';
        $students = Student::orderBy('id', 'desc')->paginate(12);
        return view('admin.students.index',
         compact('students','title'));
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
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('dashboard.students.index')->with('success', 'Student deleted successfully.');
    }


    public function sendContract($id)
    {
        $student = Student::query()->findOrFail($id);
        $contractData = [
            'email' => $student->email,
            'name' => $student->name,
            'age' => $student->age,
            'phone' => $student->phone,
            'city' => $student->city,
            'id_number' => $student->id_number,
            'id_end' => $student->id_end,
        ];

        // Create the contract and handle potential exceptions
        try {
            $contract = ParentContract::query()->create(array_merge($contractData, ['accept_terms']));

            // Notify the student about the contract
            Notification::route('mail', $contract->email)
                ->notify(new SendContractNotification($contract));

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

}
