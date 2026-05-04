<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
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
});

require __DIR__.'/auth.php';
