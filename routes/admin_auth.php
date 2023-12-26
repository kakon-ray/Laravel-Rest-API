<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminRegistationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ForgetController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('register', [AdminRegistationController::class, 'create'])->name('register');
    Route::post('register', [AdminRegistationController::class, 'store']);
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    // admin password reset
    Route::get('password-reset', [ForgetController::class,'password_reset'])->name('password.reset');
    Route::post('password-reset-submit', [ForgetController::class,'reset_password_submit'])->name('password.reset.submit');
    Route::get('reset-password/{token}', [ForgetController::class, 'show_reset_password_form'])->name('reset.password.form');
    Route::post('new-password-submit', [ForgetController::class, 'new_password_submit'])->name('new.password.submit');
    
    
    Route::middleware(['AdminAuth'])->group(function (){

        Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    });

});