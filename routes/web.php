<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ManagementController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login & logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (pastikan middleware 'auth.spreadsheet' sudah terdaftar di Kernel)
Route::middleware(['auth.spreadsheet'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/unit/{kode}', [UnitController::class, 'show'])->name('unit.show');
    Route::get('/summary/triwulan/{triwulan}', [SummaryController::class, 'show'])->name('summary.triwulan');

    Route::get('/management', [ManagementController::class, 'index'])->name('management');
    Route::post('/management/store', [ManagementController::class, 'store'])->name('management.store');
    // Tombol aksi
    Route::get('/management/view/{row}', [ManagementController::class, 'view'])->name('management.view');
    Route::post('/management/update/{row}', [ManagementController::class, 'update'])->name('management.update');
    Route::delete('/management/delete/{row}', [ManagementController::class, 'delete'])->name('management.delete');

    Route::get('/pengaturan', function () {
        return view('main.pengaturan');
    })->name('pengaturan');
});
