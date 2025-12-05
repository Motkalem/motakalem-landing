<?php
namespace App\CPU;

class Helpers{

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $str));
    }

    public static function t($key)
    {


        if(config('app.env') != 'production'){
            $local = app()->getLocale();
            try {
                $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
                $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
                $key = Helpers::remove_invalid_charcaters($key);
                if (!array_key_exists($key, $lang_array)) {
                    $lang_array[$key] = $processed_key;
                    $str = "<?php return " . var_export($lang_array, true) . ";";
                    file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
                    $result = $processed_key;
                } else {
                    $result = __('messages.' . $key);
                }
            } catch (\Exception $exception) {
                $result = __('messages.' . $key);
            }

            return $result;
        }else{
            return __('messages.' . $key);
        }
    }

}

function translate($key)
{
   return 'gg';
}
