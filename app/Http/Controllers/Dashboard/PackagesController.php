<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Helper;
use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackagesController extends AdminBaseController
{
    public function index()
    {

        $packages = Package::orderBy('id', 'desc')->paginate(12);
        $title= 'الباقات';
        return view('admin.packages.index',
         compact('packages','title'));
    }

    public function create()
    {
        $title= 'إنشاء باقة';
        return view('admin.packages.create',compact('title'));
    }

    public function store(Request $request)
    {
          $request->validate([
            'name' => 'required|string|max:255|unique:packages,name',
            'total' => 'nullable|numeric|min:0|required_without_all:installment_value,number_of_months',
            'number_of_months' => 'nullable|integer|min:1|required_if:total,null',
            'installment_value' => 'nullable|numeric|min:0|required_if:total,null',
            'is_active' => 'sometimes',
        ]);


        $package = new Package([
            'number_of_months' => $request->number_of_months,
            'installment_value' => $request->installment_value,
            'is_active' => $request->is_active == 'on' ? true : false,
            'name' => $request->name,
            'total' => $request->total,
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

    public function update(Request $request, $id)
    {

        $request->validate([
            'total' => 'nullable|numeric|min:0|required_without_all:installment_value,number_of_months',
            'number_of_months' => 'nullable|integer|min:1|required_if:total,null',
            'installment_value' => 'nullable|numeric|min:0|required_if:total,null',
            'is_active' => 'sometimes',
            'name' => ['required','string','max:255', Rule::unique('packages', 'name')
            ->ignore($id)],
        ]);

        $package = Package::findOrFail($id);

        if($request->payment_type == 'one_time')
        {

            $package->number_of_months = null;
            $package->installment_value =null;
            $package->total = $request->total;
        }else {

            $package->number_of_months = $request->number_of_months;
            $package->installment_value = $request->installment_value;
            $package->total = null;
        }

        $package->is_active = $request->is_active == 'on' ? true : false;
        $package->name = $request->name;
        $package->payment_type = $request->payment_type;
        $package->save();
        notify()->success('تم تحديث الباقة.');

        return redirect()->route('dashboard.packages.index')->with('success', 'Package updated successfully.');
    }

      /**
     * Remove the specified package from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        $package = Package::findOrFail($id);
        Helper::tryDelete($package);
         return redirect()->route('dashboard.packages.index');
    }
}

