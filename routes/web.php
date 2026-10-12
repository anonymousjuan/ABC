<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::view('/', 'welcome');

Route::view('/bsit3a', 'testing');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::view('/profile', 'profile')
        ->name('profile');

});

require __DIR__.'/auth.php';