<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordRecovery\RequestController;

Route::redirect('/', '/reset-senha');

Route::middleware('corporate.network')
    ->prefix('reset-senha')
    ->group(function () {
        Route::get('/', [RequestController::class, 'show'])->name('password-recovery.show');

        Route::post('/', [RequestController::class, 'store'])
            ->middleware('throttle:password-reset')
            ->name('password-recovery.store');
    });