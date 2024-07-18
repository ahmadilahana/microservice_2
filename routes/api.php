<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(UserController::class)->group(function () {
    Route::post("/login", "login");
    Route::post("/register", "register");
    Route::post("/logout", "logout")->middleware('auth:api');
});

Route::get('/check-pub', function () {
    var_dump("test");
    $message = [
        "id" => 1,
        "name" => "test user",
        "email" => "test user"
    ];
    Redis::publish("updated_user", json_encode($message));
});
