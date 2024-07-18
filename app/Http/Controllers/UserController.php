<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\UsersModel;
use Helper\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{

    public function register(RegisterRequest $request)
    {

        $user = UsersModel::create([
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "password" => Hash::make($request->input("password")),
        ]);

        if ($user) {
            $message = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email
            ];
            Redis::publish("updated_user", json_encode($message));
            return ResponseHelper::SuccessReponse($user, true, "Registrasi Berhasil", "REGISTRATION_USER_SUCCESS");
        } else {
            return ResponseHelper::BadRequestResponse("REGISTRATION_USER_FAILED", false, "Registrasi Gagal");
        }
    }

    public function login(LoginRequest $request)
    {

        $email = $request->input("email");
        $password = $request->input("password");

        $user = UsersModel::where("email", $email)->first();

        if (Hash::check($password, $user->password)) {
            $user["token"] = $user->createToken("access_token")->accessToken;

            // $data = json_decode($user, true);
            $accessToken = $user['token'];

            $key = 'passport:token:' . $accessToken;

            Redis::set($key, $user);
            return ResponseHelper::SuccessReponse($user, true, "Login Berhasil", "USER_LOGIN_SUCCESS");
        }

        return ResponseHelper::BadRequestResponse("WRONG_PASSWORD", false, "Password Salah");
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $accessToken->revoke();

        $accessToken = $request->bearerToken();
        $key = 'passport:token:' . $accessToken;

        Redis::del($key);
        return ResponseHelper::SuccessReponse(null, true, "Logout Berhasil", "USER_LOGOUT_SUCCESS");
    }
}
