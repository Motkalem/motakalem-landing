<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\JsonResponse;

class PaymentSettingsController extends Controller
{
    /**
     * Get all settings for frontend
     *
     * @return JsonResponse
     */
    public function getAllSettings(): JsonResponse
    {
            $settings = GeneralSetting::all()->groupBy('group')->map(function ($group) {
                return $group->map(function ($setting) {
                    return [
                        'key' => $setting->key,
                        'name' => $setting->name,
                        'value' => $setting->type === 'boolean'
                            ? ($setting->value == '1' || $setting->value === 1 || $setting->value === true || $setting->value === 'true')
                            : $setting->value,
                    ];
                })->values();
            });

            $response = [
                'success' => true,
                'data' => $settings,
                'message' => 'All settings retrieved successfully'
            ];

            return response()->json($response, 200);
         
    }
}
