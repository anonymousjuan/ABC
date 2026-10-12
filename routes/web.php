<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

Route::view('/', 'welcome');

Route::view('/bsit3a', 'testing');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::view('/profile', 'profile')
        ->name('profile');

    Route::get('/reports/patients/export', [
    ReportController::class,
    'exportPatients'
    ])->name('reports.patients.export');

    Route::get('/reports/appointments/export', [
    ReportController::class,
    'exportAppointments'
])->name('reports.appointments.export');

});
Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

Route::get('/reports/encounters/export', [
    ReportController::class,
    'exportEncounters'
])->name('reports.encounters.export');

require __DIR__.'/auth.php';