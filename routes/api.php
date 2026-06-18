<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NetworksController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

// Public auth endpoints.
Route::post('register', [AuthController::class, 'register'])->middleware('throttle:6,1');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:6,1');

// Authenticated endpoints (Sanctum bearer token).
Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('email/verify', [EmailVerificationController::class, 'verify'])->middleware('throttle:6,1');
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1');

    Route::get('networks', NetworksController::class);

    Route::put('wallet', [WalletController::class, 'update']);

    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('tickets', [TicketController::class, 'store'])->middleware('throttle:10,1');
});
