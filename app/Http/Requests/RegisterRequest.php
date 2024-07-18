<?php

namespace App\Http\Requests;

use Helper\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

    protected function failedValidation(Validator $validator){
        $type = '';
        $message = '';
        foreach ($validator->errors()->messages() as $key => $value) {
            $type = "VALIDATION_ERROR_ON_".strtoupper($key);
            $message = $value;
        }

        return ResponseHelper::UnprocessableEntityReponse($type, true, $message);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => ["required", "string"],
            "email" => ["required", "email", "unique:users"],
            "password" => ["required", "string", "min:6"]
        ];
    }

    public function attributes(){
        return [
            "name" => "Nama",
            "email" => "Email",
            "password" => "Password"
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Wajib menginputkan :attribute.',
            "string" => ":attribute harus bertipe teks.",
            "unique" => ":attribute sudah terdaftar.",
            "min" => ":attribute minimal :min karakter.",
            "email" => ":attribute tidak valid."
        ];
    }
}
