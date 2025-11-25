<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login-check', [LoginController::class, 'login'])->name('admin.login');

    Route::view('/verify-otp', 'otp_verify')->name('otp.page');
    Route::post('/verify-otp-check', [RegisterController::class, 'otpVerification'])->name('otp.verify');
});

Route::get('/admin/register', function () {
    return view('admin.register');
})->name('admin.register');

Route::get('/customer/register', function () {
    return view('customer.register');
})->name('customer.register');

Route::view('/verify-otp', 'otp_verify')->name('otp.page');

Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
Route::view('/thank-you', 'thank_you');


Route::group(['middleware' => ['auth:web', 'isAdmin', 'PreventBackHistoryAdmin']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

