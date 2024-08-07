<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentsController extends AdminBaseController
{
    public function index()
    {
        $title= 'المدفوعات';

        $payments = Payment::with(['student', 'package'])->orderBy('id', 'desc')->paginate(12);
        $students = Student::all();
        $packages = Package::all();

        return view('admin.payments.index',
         compact('payments',
          'students','title', 'packages'));
    }

    public function create()
    {
        $title= 'إنشاء دفعة جديدة';
        $students = Student::all();
        $packages = Package::all();
        return view('admin.payments.create',
         compact('students',
         'title', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'package_id' => 'required|exists:packages,id',
            'payment_type' => 'required|string|max:255',
            'is_finished' => 'sometimes',
        ]);

        $payment = new Payment([
            'student_id' => $request->student_id,
            'package_id' => $request->package_id,
            'payment_type' => $request->payment_type,
            'payment_url' => 'htts://example.com',
            'is_finished' => $request->has('is_finished') ? $request->is_finished : false,
        ]);

        $payment->save();

        notify()->success('تم إنشاء الدفعة بنجاح.');
        return redirect()->route('dashboard.payments.index')->with('success', 'Payment created successfully.');
    }

    public function edit($id)
    {
        $title = 'تحديث الدفعة';
        $payment = Payment::findOrFail($id);
        $students = Student::all();
        $packages = Package::all();
        return view('admin.payments.edit',
         compact('payment','title', 'students', 'packages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'package_id' => 'required|exists:packages,id',
            'payment_type' => 'required|string|max:255',
            'payment_url' => 'nullbale|url',
            'is_finished' => 'sometimes|boolean',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->student_id = $request->student_id;
        $payment->package_id = $request->package_id;
        $payment->payment_type = $request->payment_type;
        $payment->payment_url = $request->payment_url?? $payment->payment_url;
        $payment->is_finished = $request->has('is_finished') ? $request->is_finished : false;

        $payment->save();

        notify()->success('تم تحديث الدفعة بنجاح.');
        return redirect()->route('dashboard.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        notify()->success('تم حذف الدفعة بنجاح.');
        return redirect()->route('dashboard.payments.index')->with('success', 'Payment deleted successfully.');
    }
}
