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

Route::get('test-mail', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email from Wardrobe production app', function ($message) {
            $message->to('stephenonyango82@gmail.com')
                ->subject('Waedrobe Mail Test');
        });
        return response()->json(['message' => 'Email sent successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Email failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('ping', function () {
    return response()->json([
        'status' => 'ok',
        'routes' => collect(Route::getRoutes())
            ->map(fn($r) => $r->uri())
            ->values()
    ]);
});

Route::get('/internal/cleanup', function () {
    $secret = request()->query('secret');

    if ($secret !== config('app.cleanup_secret')) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $deleted = \App\Models\PendingRegistration::whereNotNull('expires_at')
        ->where('expires_at', '<', now())
        ->delete();

    return response()->json([
        'message' => "Deleted {$deleted} expired pending registrations."
    ]);
});

// Route::get('debug-cors', function () {
//     return response()->json([
//         'cors_paths' => config('cors.paths'),
//         'cors_origins' => config('cors.allowed_origins'),
//         'cors_credentials' => config('cors.supports_credentials'),
//         'app_env' => config('app.env'),
//         'frontend_url' => env('FRONTEND_URL'),
//     ]);
// });


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


