<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackagesController extends AdminBaseController
{
    public function index()
    {
        $packages = Package::orderBy('id', 'desc')->paginate(12);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'number_of_months' => 'required|integer|min:1',
            'installment_value' => 'required|numeric|min:0',
            'is_active' => 'required',
            'name' => 'required|string|max:255|unique:packages,name',
        ]);

        $package = new Package([
            'number_of_months' => $request->number_of_months,
            'installment_value' => $request->installment_value,
            'is_active' => $request->is_active == 'on' ? true : false,
            'name' => $request->name,
        ]);

        $package->save();
        notify()->success('تم إنشاء الباقة.');

        return redirect()->route('dashboard.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'number_of_months' => 'required|integer|min:1',
            'installment_value' => 'required|numeric|min:0',
            'is_active' => 'required',
            'name' => ['required','string','max:255', Rule::unique('packages', 'name')
            ->ignore(request('id'))],
        ]);

        $package = Package::findOrFail($id);
        $package->number_of_months = $request->number_of_months;
        $package->installment_value = $request->installment_value;
        $package->is_active = $request->is_active == 'on' ? true : false;
        $package->name = $request->name;

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
        $package->delete();
        notify()->success('تم حذف الباقة.');
        return redirect()->route('dashboard.packages.index');
    }
}

