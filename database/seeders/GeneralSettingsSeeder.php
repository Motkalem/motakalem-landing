<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing settings
        GeneralSetting::truncate();

        $settings = [
            [
                'key' => GeneralSetting::SHOW_ONETIME_PAYMENT,
                'name' => 'إظهار زر الدفع لمرة واحدة',
                'description' => 'إظهار زر الدفع لمرة واحدة في الواجهة الأمامية',
                'value' => '1',
                'type' => 'boolean',
                'group' => GeneralSetting::GROUP_PAYMENT_BUTTONS,
                'sort_order' => 1,
                'is_active' => true,
                'is_required' => false,
            ],
            [
                'key' => GeneralSetting::SHOW_RECURRING_PAYMENT,
                'name' => 'إظهار زر الدفع المتكرر',
                'description' => 'إظهار زر الدفع المتكرر (الأقساط) في الواجهة الأمامية',
                'value' => '1',
                'type' => 'boolean',
                'group' => GeneralSetting::GROUP_PAYMENT_BUTTONS,
                'sort_order' => 2,
                'is_active' => true,
                'is_required' => false,
            ],
        ];

        foreach ($settings as $setting) {
            GeneralSetting::create($setting);
        }
    }
}
