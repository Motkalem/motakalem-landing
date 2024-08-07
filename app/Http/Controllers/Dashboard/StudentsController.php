<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'الطلاب';

        $students = Student::orderBy('id', 'desc')->paginate(12);
        return view('admin.students.index',
         compact('students','title'));
    }

    public function create()
    {

        $title = 'إضافة طالب جديد';
        return view('admin.students.create',compact( 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'payment_type' => 'required|string|max:255',
            'total_payment_amount' => 'required|numeric',
            'age' => 'required|integer',
            'is_paid' => 'sometimes',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
        ]);

        $student = new Student([
            'name' => $request->name,
            'email' => $request->email,
            'payment_type' => $request->payment_type,
            'total_payment_amount' => $request->total_payment_amount,
            'age' => $request->age,
            'is_paid' => $request->has('is_paid') ? $request->is_paid : false,
            'phone' => $request->phone,
            'city' => $request->city,
        ]);

        $student->save();

        return redirect()->route('dashboard.students.index')->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $title = ' تعديل طالب ';

        $student = Student::findOrFail($id);
        return view('admin.students.edit',
         compact('student','title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'payment_type' => 'required|string|max:255',
            'total_payment_amount' => 'required|numeric',
            'age' => 'required|integer',
            'is_paid' => 'sometimes',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
        ]);

        $student = Student::findOrFail($id);
        $student->name = $request->name;
        $student->email = $request->email;
        $student->payment_type = $request->payment_type;
        $student->total_payment_amount = $request->total_payment_amount;
        $student->age = $request->age;
        $student->is_paid = $request->has('is_paid') ? $request->is_paid : false;
        $student->phone = $request->phone;
        $student->city = $request->city;

        $student->save();

        return redirect()->route('dashboard.students.index')->with('success', 'Student updated successfully.');
    }

    public function show($id)
    {
        $title = 'عرض طالب ';

        $student = Student::findOrFail($id);
        return view('admin.students.show',
         compact('student','title'));
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('dashboard.students.index')->with('success', 'Student deleted successfully.');
    }
}
