<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Package;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

    /**
     * @return Application|Factory|View
     */
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
            'total' => 'nullable|numeric|min:0|required_without_all:first_inst,number_of_months',
            'number_of_months' => 'nullable|integer|min:1|required_if:total,null',
            'first_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'second_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'third_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'fourth_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'fifth_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'starts_date' => 'nullable|date|before:ends_date',
            'ends_date' => 'nullable|date|after:starts_date',
            'is_active' => 'nullable',
        ]);


        $package = new Package([
            'number_of_months' => $request->number_of_months,

            'first_inst' => $request->first_inst??0,
            'second_inst' => $request->second_inst??0,
            'third_inst' => $request->third_inst??0,
            'fourth_inst' => $request->fourth_inst??0,
            'fifth_inst' => $request->fifth_inst??0,

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

        return view('admin.packages.edit', compact('package','title'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id) : RedirectResponse
    {

        $request->validate([
            'total' => 'nullable|numeric|min:0|required_without_all:first_inst,number_of_months',
            'number_of_months' => 'nullable|integer|min:1|required_if:total,null',

            'first_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'second_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'third_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'fourth_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'fifth_inst' => 'nullable|numeric|min:0|required_if:total,null',
            'is_active' => 'sometimes',
            'starts_date' => 'nullable|date|before:ends_date',
            'ends_date' => 'nullable|date|after:starts_date',
            'name' => ['required','string','max:255', Rule::unique('packages', 'name')
            ->ignore($id)],
        ]);

        $package = Package::query()->findOrFail($id);

        if($package->payment_type == Package::ONE_TIME || $package->payment_type == Package::TABBY)
        {

            $package->number_of_months = null;
            $package->first_inst =null;
            $package->total = $request->total;
        }else {

           $package->number_of_months = $package->payments->count() ? $package->number_of_months : $request->number_of_months;

           $package->first_inst = $request->first_inst??0;
           $package->second_inst = $request->second_inst??0;
           $package->third_inst = $request->third_inst??0;
           $package->fourth_inst = $request->fourth_inst??0;
           $package->fifth_inst = $request->fifth_inst??0;

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

