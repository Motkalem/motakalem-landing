<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ContactUs;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class ProfileController extends AdminBaseController
{
    public function edit(): Factory|View|Application
    {
        $title = 'الملف الشخصي';

        return view('admin.profile.edit', get_defined_vars());
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $adminUser =auth('dashboard')->user();

        $adminUser->name = $request->input('name');

        if ($request->filled('password')) {

            $adminUser->password = bcrypt($request->input('password'));
        }

        $adminUser->save();
        notify()->success( 'تم تحديث بيانات المشرف بنجاح.','نجاح');

        return redirect()->route('dashboard.profile.edit');
    }


}
