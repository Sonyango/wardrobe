<?php

use App\Http\Controllers\API\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\GenderController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\UserController;


Route::post('register/send-code', [RegistrationController::class, 'sendCode']);
Route::post('register/verify', [RegistrationController::class, 'verifyCode']);

Route::post('password-reset/send-code', [PasswordResetController::class, 'sendResetCode']);
Route::post('password-reset/verify-code', [PasswordResetController::class, 'verifyCode']);
Route::post('password-reset/reset', [PasswordResetController::class, 'resetPassword']);

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('genders', GenderController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('items', ItemController::class);

    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/category-stats', [DashboardController::class, 'categoryStats']);
    Route::put('user/profile', [UserController::class, 'update']);
});


