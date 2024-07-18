<?php

namespace App\Http\Requests;

use Helper\ResponseHelper;
use App\Models\UserModel;
use App\Models\UsersModel;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            "email" => [
                "required", 
                "exists:users",
                "email"
            ],
            "password" => ["required"]
        ];
    }

    public function attributes(){
        return [
            "email" => "Email",
            "password" => "Password"
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Wajib menginputkan :attribute.',
            "exists" => ":attribute tidak terdaftar."
        ];
    }
}
