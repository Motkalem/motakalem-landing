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

    function sanitizeUsername(string $username): string
    {
        if (function_exists('transliterator_transliterate')) {
            $transliterated = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove',
                $username
            );
        } else {
            $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $username);
            $transliterated = preg_replace('/[^A-Za-z0-9]/', '', $transliterated);
        }

        return strtolower($transliterated) . '@email.com';
    }
}
