<?php

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

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('profile', [\App\Http\Controllers\Api\v1\Auth\ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [\App\Http\Controllers\Api\v1\Auth\ProfileController::class, 'update'])->name('profile.update');
        Route::put('password', \App\Http\Controllers\Api\v1\Auth\PasswordUpdateController::class)->name('update.password');
        Route::post('logout', \App\Http\Controllers\Api\v1\Auth\LogoutController::class);
    });

});

Route::post('auth/register', \App\Http\Controllers\Api\v1\Auth\RegisterController::class)->name('user.register');

Route::post('auth/login', \App\Http\Controllers\Api\v1\Auth\LoginController::class)->name('user.login');
