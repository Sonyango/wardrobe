<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\GenderController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ItemController;


Route::post('register/send-code', [RegistrationController::class, 'sendCode']);
Route::post('register/verify', [RegistrationController::class, 'verifyCode']);


Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('genders', GenderController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('items', ItemController::class);
});


