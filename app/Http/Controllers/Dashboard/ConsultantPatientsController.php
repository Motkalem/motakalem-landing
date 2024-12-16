<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ConsultantPatient;
use App\Models\ConsultantType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConsultantPatientsController extends AdminBaseController
{
    public function index()
    {
        $consultantPatients = ConsultantPatient::query()->orderBy('id', 'desc')->paginate(12);
        $consultantPatientsCount = ConsultantPatient::query()->count();
        $title = 'قائمة المرضى';

        return view('admin.consultant-patients.index', compact('consultantPatients',
            'title', 'consultantPatientsCount'));
    }

    public function create()
    {
        $title = 'إضافة مريض جديد';
        $consultationTypes = ConsultantType::all();

        return view('admin.consultant-patients.create', compact('title', 'consultationTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'consultation_type_id' => 'required|exists:consultant_types,id',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'mobile' => 'required|string|max:15',
            'city' => 'required|string|max:255',
        ]);

        ConsultantPatient::create($request->only(['consultation_type_id', 'name', 'age', 'gender', 'mobile', 'city']));

        notify()->success('تم إضافة المريض بنجاح.');

        return redirect()->route('dashboard.consultant-patients.index')->with('success', 'Patient created successfully.');
    }

    public function edit($id)
    {
        $title = 'تحديث بيانات المريض';
        $consultantPatient = ConsultantPatient::findOrFail($id);
        $consultationTypes = ConsultantType::all();
        return view('admin.consultant-patients.create', compact('consultantPatient',
            'title', 'consultationTypes'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $consultantPatient = ConsultantPatient::findOrFail($id);

        $request->validate([
            'consultation_type_id' => 'required|exists:consultant_types,id',
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'mobile' => 'required|string|max:15',
            'city' => 'required|string|max:255',
        ]);

        $consultantPatient->update($request->only(['consultation_type_id', 'name', 'age', 'gender', 'mobile', 'city']));

        notify()->success('تم تحديث بيانات المريض بنجاح.');

        return redirect()->route('dashboard.consultant-patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $consultantPatient = ConsultantPatient::findOrFail($id);

        $consultantPatient->delete();

        notify()->success('تم حذف المريض بنجاح.');

        return redirect()->route('dashboard.consultant-patients.index')->with('success', 'Patient deleted successfully.');
    }
}
