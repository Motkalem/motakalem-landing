<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $table = 'general_settings';

    // Payment Button Settings Constants
    const SHOW_ONETIME_PAYMENT = 'show_onetime_payment';
    const SHOW_RECURRING_PAYMENT = 'show_recurring_payment';
    // Group Constants
    const GROUP_PAYMENT_BUTTONS = 'payment_buttons';
    const GROUP_GENERAL = 'general';

    protected $fillable = [
        'key',
        'name',
        'description',
        'value',
        'type',
        'options',
        'group',
        'sort_order',
        'is_active',
        'is_required',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get active settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get settings by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllAsArray()
    {
        return static::active()->pluck('value', 'key')->toArray();
    }

    /**
     * Get settings grouped by group name
     */
    public static function getGrouped()
    {
        return static::active()->ordered()->get()->groupBy('group');
    }
}
