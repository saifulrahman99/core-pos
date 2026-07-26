<?php

use App\Http\Controllers\Auth\MfaResetRequestController;
use App\Http\Controllers\Auth\MfaResetVerifyController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

// MFA Reset Routes (no auth required)
Route::get('auth/mfa-reset', [MfaResetRequestController::class, 'show'])
    ->name('mfa.reset.show');
Route::post('auth/mfa-reset', [MfaResetRequestController::class, 'store'])
    ->name('mfa.reset.store');
Route::get('auth/mfa-reset/verify/{token}', [MfaResetVerifyController::class, 'show'])
    ->name('mfa.reset.verify');
Route::post('auth/mfa-reset/verify/{token}', [MfaResetVerifyController::class, 'store'])
    ->name('mfa.reset.verify.store');
Route::get('auth/mfa-reset/success', [MfaResetVerifyController::class, 'success'])
    ->name('mfa.reset.success');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
