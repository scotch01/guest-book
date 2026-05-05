<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
    Route::get('/guests/{guest}', [GuestController::class, 'show'])->name('guests.show');

    Route::post('/assign', [AssignmentController::class, 'store'])->name('assign.store');

    Route::get('/report/monthly', [ReportController::class, 'monthly'])->name('report.monthly');

    Route::get('/report/monthly/pdf', [ReportController::class, 'monthlyPdf'])->name('report.monthly.pdf');

    Route::post('/sync/guests', [SyncController::class, 'run'])
    ->middleware(['auth'])
    ->name('sync.guests');

    Route::resource('employees', EmployeeController::class);

    Route::patch('/employees/{employee}/activate', [EmployeeController::class, 'activate'])
    ->name('employees.activate');
});

require __DIR__.'/auth.php';
