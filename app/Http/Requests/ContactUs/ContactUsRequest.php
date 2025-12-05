<?php

namespace App\Http\Requests\ContactUs;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'], // الاسم *
            'phone' => ['required', 'numeric'], // الهاتف *
            'email' => ['required', 'string', 'email', 'max:255'], // البريد الالكتروني *
            "message" => ['required', 'string']
        ];
    }
}
