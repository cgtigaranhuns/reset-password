<?php

use Illuminate\Support\Facades\Route;

Route::middleware('corporate.network')
    ->prefix('reset-senha')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\PasswordRecovery\RequestController::class, 'show'])
            ->name('password-recovery.show');

        Route::post('/', [\App\Http\Controllers\PasswordRecovery\RequestController::class, 'store'])
            ->middleware('throttle:password-reset')
            ->name('password-recovery.store');
    });