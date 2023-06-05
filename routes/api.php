<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);
Route::post("check_phone_number", [UserController::class, "checkPhoneNumber"]);
Route::put("update_google_id/{id}", [UserController::class, "updateGoogleId"]);
Route::post("check_google_id", [UserController::class, "checkGoogleId"]);

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get("logout", [UserController::class, "logout"]);
    Route::post("login_with_token", [UserController::class, "loginWithToken"]);

    Route::put("update_phone_number/{id}", [
        UserController::class,
        "updatePhoneNumber",
    ]);
    Route::put("update_profile_image_url/{id}", [
        UserController::class,
        "updateProfileImageUrl",
    ]);
    Route::get("check_user_phone_number/{id}", [
        UserController::class,
        "checkUserPhoneNumber",
    ]);

    Route::post("upload_profile_image", [
        UserController::class,
        "uploadProfileImage",
    ]);

    Route::put("update_name/{id}", [UserController::class, "updateName"]);
});
