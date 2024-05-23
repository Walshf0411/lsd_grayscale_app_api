<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix("screenConfigs")->group(function() {
    Route::post("/", [App\Http\Controllers\ScreenConfig\GetScreenConfigsController::class, "router"]);

    Route::post("/create", [App\Http\Controllers\ScreenConfig\CreateScreenConfigsController::class, "router"]);
});

Route::prefix("auth")->group(function() {

    Route::prefix("/login")->group(function () {
        Route::post("/emailPassword", [App\Http\Controllers\Auth\Login\EmailPasswordLoginController::class, "router"]);
        Route::post("/otp", [App\Http\Controllers\Auth\Login\OtpLoginController::class, "router"]);
        Route::post("/otp/verify", [App\Http\Controllers\Auth\Login\VerifyOtpController::class, "router"]);
    });

    Route::prefix("/signup")->group(function() {
        Route::post("/emailPassword", [App\Http\Controllers\Auth\Signup\EmailPasswordSignupController::class, "router"]);
    });

    Route::middleware('auth:sanctum')->post("/logout", [App\Http\Requests\Auth\LogoutController::class, "router"]);
});

Route::middleware("auth:sanctum")->prefix("users/details")->group(function() {
    Route::get("/", [App\Http\Controllers\UserDetails\GetUserDetailsController::class, "router"]);

    Route::post("/update", [App\Http\Controllers\UserDetails\UpdateUserDetailsController::class, "router"]);
});
