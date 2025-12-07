<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends AdminBaseController
{
    public function index(): Factory|View|Application
    {
        $title = 'الإعدادات';

        $settingsByGroup = GeneralSetting::ordered()->get()->groupBy('group');
        $groupOrder = [
            GeneralSetting::GROUP_PAYMENT_BUTTONS => 'أزرار الدفع',
        ];

        return view('admin.settings.index', get_defined_vars());
    }

    /**
     * Update all settings in bulk
     */
    public function updateAll(Request $request): RedirectResponse
    {
        $settings = $request->input('settings', []);

        $paymentButtonSettings = GeneralSetting::where('group', GeneralSetting::GROUP_PAYMENT_BUTTONS)->get();
        $hasEnabledPaymentButton = false;

        foreach ($paymentButtonSettings as $paymentSetting) {

            if (isset($settings[$paymentSetting->id]['value'])) {
                $hasEnabledPaymentButton = true;
                break;
            }
        }

        if (!$hasEnabledPaymentButton) {
            notify()->error('يجب تفعيل على الأقل أحد خيارات أزرار الدفع', 'خطأ');
            return redirect()->back()->withInput();
        }

        foreach ($settings as $settingId => $settingData) {
            $setting = GeneralSetting::find($settingId);
            if ($setting) {

                if ($setting->type === 'boolean') {
                    $setting->value = isset($settingData['value']) ? '1' : '0';
                } else {
                    $setting->value = $settingData['value'] ?? $setting->value;
                }

                $setting->name = $settingData['name'] ?? $setting->name;
                $setting->description = $settingData['description'] ?? $setting->description;
                $setting->save();
            }
        }

        notify()->success('تم تحديث جميع الإعدادات بنجاح', 'نجاح');
        return redirect()->route('dashboard.settings.index');
    }



    public function create(): Factory|View|Application
    {
        $title = 'إضافة إعداد جديد';
        return view('admin.settings.create', get_defined_vars());
    }

    /**
     * Store a new setting
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:general_settings,key',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'value' => 'nullable|string',
            'type' => 'required|string|in:text,number,boolean,email,url,textarea,select,file',
            'group' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
        ]);

        $data = $request->all();

        // Handle boolean values properly
        if ($data['type'] === 'boolean') {
            $data['value'] = $request->boolean('value') ? '1' : '0';
        }

        GeneralSetting::create($data);

        notify()->success('تم إضافة الإعداد بنجاح', 'نجاح');

        return redirect()->route('dashboard.settings.index');
    }

    /**
     * Delete a setting
     */
    public function destroy(int $id): RedirectResponse
    {
        $setting = GeneralSetting::findOrFail($id);
        $setting->delete();

        notify()->success('تم حذف الإعداد بنجاح', 'نجاح');

        return redirect()->route('dashboard.settings.index');
    }

    /**
     * Get settings by group
     */
    public function byGroup(string $group): Factory|View|Application
    {
        $title = 'الإعدادات - ' . ucfirst($group);
        $settings = GeneralSetting::byGroup($group)->ordered()->get();
        return view('admin.settings.group', get_defined_vars());
    }
}
