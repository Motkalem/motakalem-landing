<?php

namespace App\Traits;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

trait HelperTrait
{
    public function formatMobile(string $mobile): string
    {

        if (substr($mobile, 0, 1) != "+" && substr($mobile, 0, 1) != "0" && substr($mobile, 0, 3) != "966") {
            return "+966" . $mobile;
        } elseif (substr($mobile, 0, 1) != "+" && substr($mobile, 0, 1) == "0" && substr($mobile, 0, 3) != "966") {
            return "+966" . substr($mobile, 1);
        } elseif (substr($mobile, 0, 1) != "+" && substr($mobile, 0, 1) != "0" && substr($mobile, 0, 3) == "966") {
            return "+" . $mobile;
        } elseif (substr($mobile, 0, 1) == "+" && substr($mobile, 0, 4) == "+966" && substr($mobile, 0, 1) != "0" && substr($mobile, 0, 3) != "966") {
            return $mobile;
        }

        if (strlen($mobile) == 10) {
            return "+966" . $mobile;
        }

        if (strlen($mobile) == 13) {

            if (strpos($mobile, "9") == 0) {
                return "+" . $mobile;
            }

            $validator = Validator::make(['mobile' => $mobile], [
                'mobile' => ['required', 'regex:/^966[0-9]{9}$/']
            ]);
            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'mobile' => ['يجب ان يبدا رقم الهاتف ب 966  ']
                ]);
            }
        }

        return $mobile;
    }

    public function encrypt(string $str)
    {
       return Crypt::encrypt($str);
    }

    public function encryptString(string $str)
    {
        return Crypt::encryptString($str);
    }

    public function decrypt(  $str)
    {
        try {

            return Crypt::decrypt($str);
        } catch (DecryptException $e) {
            abort(403, 'Invalid or tampered INFO.');
        }
    }

    public function decryptString(  $str)
    {
        try {

            return Crypt::decryptString($str);
        } catch (DecryptException $e) {
            abort(403, 'Invalid or tampered INFO.');
        }
    }

}
