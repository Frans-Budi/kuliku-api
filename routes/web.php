<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
    return view("welcome");
});

Route::get("auth/google/redirect", [
    GoogleController::class,
    "redirectToGoogle",
]);
Route::get("/auth/google/callback", [
    GoogleController::class,
    "handleGoogleCallback",
]);

// Auth::routes(["verify" => true]);
