<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Helper;
use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Package;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackagesController extends AdminBaseController
{
    public function index()
    {

        $search = request()->query('search');

        $query = Package::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $packages = $query->orderBy('id', 'desc')->paginate(12);

        $title= 'الباقات';

        $packagesCount = Package::query()->count();

        return view('admin.packages.index',   compact('packages','title', 'packagesCount'));
    }

    public function create()
    {
        $title= 'إنشاء باقة';
        return view('admin.packages.create',
        compact('title'));
    }

    public function store(Request $request)
    {
          $request->validate([
            'name' => 'required|string|max:255|unique:packages,name',
            'total' => 'nullable|numeric|min:0|required_without_all:installment_value,number_of_months',
            'number_of_months' => 'nullable|integer|min:1|required_if:total,null',
            'installment_value' => 'nullable|numeric|min:0|required_if:total,null',

            'starts_date' => 'required|date|before:ends_date',
            'ends_date' => 'required|date|after:starts_date',

            'is_active' => 'sometimes',
        ]);

        $package = new Package([
            'number_of_months' => $request->number_of_months,
            'installment_value' => $request->installment_value,
            'is_active' => $request->is_active == 'on' ? true : false,
            'name' => $request->name,
            'total' => $request->total,
            'starts_date' => $request->starts_date,
            'ends_date' => $request->ends_date,
            'payment_type' => $request->payment_type
        ]);

        $package->save();
        notify()->success('تم إنشاء الباقة.');

        return redirect()->route('dashboard.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit($id)
    {

        $title= 'تحديث الباقة';
        $package = Package::findOrFail($id);

        return view('admin.packages.edit',
         compact('package','title'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {

        $request->validate([
            'total' => 'nullable|numeric|min:0|required_without_all:installment_value,number_of_months',
            'number_of_months' => 'nullable|integer|min:1|required_if:total,null',
            'installment_value' => 'nullable|numeric|min:0|required_if:total,null',
            'is_active' => 'sometimes',
            'starts_date' => 'required|date|before:ends_date',
            'ends_date' => 'required|date|after:starts_date',
            'name' => ['required','string','max:255', Rule::unique('packages', 'name')
            ->ignore($id)],
        ]);

        $package = Package::query()->findOrFail($id);

        if($package->payment_type == Package::ONE_TIME)
        {

            $package->number_of_months = null;
            $package->installment_value =null;
            $package->total = $request->total;
        }else {
            $package->number_of_months = $package->payments->count() ? $package->number_of_months : $request->number_of_months;

            $package->installment_value = $request->installment_value;
            $package->total = null;
        }

        $package->is_active = $request->is_active == 'on' ? true : false;
        $package->name = $request->name;
        $package->starts_date = $request->starts_date;
        $package->ends_date = $request->ends_date;

        $package->save();

        notify()->success('تم تحديث الباقة.');
        return redirect()->route('dashboard.packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function changeStatus(Request $request, $id): RedirectResponse
    {
        $package = Package::query()->findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        notify()->success('تم تحديث الباقة.');
        return redirect()->route('dashboard.packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
//    public function destroy($id): RedirectResponse
//    {
//
//        $package = Package::with('payments')->findOrFail($id);
//
//        Helper::tryDelete($package);
//
//         return redirect()->route('dashboard.packages.index');
//    }
}

