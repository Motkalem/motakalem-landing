<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;

class Helper
{

    public static function tryDelete($row): bool
    {
        try {
            $row->delete();

            notify()->error(__('Deleted successfully'));
        } catch (\Throwable  $e) {

            notify()->error(__('Can not deleted'));
            return false;
        }
        return true;
    }

    public static function tryForceDelete($row): bool
    {
        try {
            $row->forceDelete();
            notify()->error(__('Deleted successfully'));
        } catch (\Throwable  $e) {
            notify()->error(__('Can not deleted'));
            return false;
        }
        return true;
    }

    /*
    * $key_type=[
    *  'month_index'
    * ]
   */
    public static function getArabicDate($key, $key_type)
    {
        $months = ["Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر"];
        $days = ["Sat" => "السبت", "Sun" => "الأحد", "Mon" => "الإثنين", "Tue" => "الثلاثاء", "Wed" => "الأربعاء", "Thu" => "الخميس", "Fri" => "الجمعة"];
        $day_of_week = ["الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"];
        $am_pm = ['AM' => 'صباحاً', 'PM' => 'مساءً'];

        if ($key_type == 'month_index') {
            return array_values($months)[$key];
        }
        if ($key_type == 'get_days') {
            return $days;
        }
        if ($key_type == 'day_key') {
            return data_get($days, $key);
        }
        if ($key_type == 'day_of_week_index') {
            return $day_of_week[$key];
        }
        return '';
    }

    public static function updateModelMorphCreatedBy($model): void
    {
        if (Auth::check()) {
            $model->update([
                'created_by_id' => Auth::id(),
                'created_by_type' => get_class(Auth::user()),
            ], ['timestamps' => false]);
        }
    }

    public static function updateModelMorphUpdatedBy($model): void
    {
        if (Auth::check()) {
            $model->update([
                'updated_by_id' => Auth::id(),
                'updated_by_type' => get_class(Auth::user()),
            ]);
        }
    }

    public static function unsetGet(&$item, $key)
    {
        $value = data_get($item, $key);
        unset($item[$key]);
        return $value;
    }

    public static function getSelectDays(): array
    {
        return [
            ['id' => 6, 'name' => __('saturday')],
            ['id' => 0, 'name' => __('sunday')],
            ['id' => 1, 'name' =>  __('monday')],
            ['id' => 2, 'name' =>  __('tuesday')],
            ['id' => 3, 'name' =>  __('wednesday')],
            ['id' => 4, 'name' =>  __('thursday')],
            ['id' => 5, 'name' =>  __('friday')],
        ];
    }

    public static function addHourToTime($time,$added_hour): string//time format H:i
    {
        $spit=explode(':',$time);
        return ($spit[0]+$added_hour).':'.$spit[1];
    }
}
