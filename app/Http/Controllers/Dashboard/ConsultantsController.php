<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Helper;
use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\ConsultantType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultantsController extends AdminBaseController
{
    public function index()
    {
        $consultantTypes = ConsultantType::query()->orderBy('id', 'desc')->paginate(12);
        $consultantTypesCount = ConsultantType::query()->count();
        $title = 'أنواع الإستشارات';

        return view('admin.consultant-types.index', compact('consultantTypes', 'title', 'consultantTypesCount'));
    }

    public function create()
    {
        $title = 'إنشاء نوع استشارة';

        return view('admin.consultant-types.create', compact('title'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:consultant_types,name',
            'price' => 'required|numeric|min:0',
        ]);

        ConsultantType::create([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        notify()->success('تم إنشاء نوع الاستشارة.');

        return redirect()->route('dashboard.consultant-types.index')->with('success', 'Consultant type created successfully.');
    }

    public function edit($id)
    {
        $title = 'تحديث نوع الاستشارة';
        $consultantType = ConsultantType::findOrFail($id);

        return view('admin.consultant-types.create', compact('consultantType', 'title'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $consultantType = ConsultantType::findOrFail($id);

        $request->validate([
            'name' => [
                'required', 'string', 'max:255', Rule::unique('consultant_types', 'name')->ignore($id),
            ],
            'price' => 'required|numeric|min:0',
        ]);

        $consultantType->update([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => $request->is_active ? 1 : 0,

        ]);

        notify()->success('تم تحديث نوع الاستشارة.');

        return redirect()->route('dashboard.consultant-types.index')->with('success', 'Consultant type updated successfully.');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)#: RedirectResponse
    {
      $consultantType = ConsultantType::with('consultantPatients')->findOrFail($id);

      $result =  Helper::tryDelete($consultantType);

      if($result){

        notify()->success('تم حذف نوع الاستشارة.');
      } else {
          notify()->error('لا يمكن الحذف .');
      }

        return redirect()->route('dashboard.consultant-types.index')->with('success', 'Consultant type deleted successfully.');
    }
}
