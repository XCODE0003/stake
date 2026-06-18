<?php

use App\Http\Controllers\Auth\EmailVerificationCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

// Email confirmation by one-time code (user is logged in but not yet verified).
Route::middleware('auth')->group(function () {
    Route::post('email/verify-code', [EmailVerificationCodeController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.code.store');

    Route::post('email/verify-code/resend', [EmailVerificationCodeController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.code.resend');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::put('wallet', [WalletController::class, 'update'])->name('wallet.update');
});

require __DIR__.'/settings.php';
