<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

trait HelperTrait
{
    public function formatMobile(string $mobile): string
    {
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




}
