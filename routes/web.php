<?php

use App\Http\Controllers\User\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::name('user.')->prefix('user')->group(function () {

    Route::middleware('auth','verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
 
    });
    
});

require __DIR__.'/auth.php';
require __DIR__.'/admin_auth.php';
