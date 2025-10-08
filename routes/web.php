<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/unit/{kode}', [UnitController::class, 'show'])->name('unit.show');
    Route::get('/summary/triwulan/{tw}', [SummaryController::class, 'index'])
        ->where('tw', '[1-4]')
        ->name('summary.triwulan');

    Route::get('/laporan/triwulan/{tw}', [LaporanController::class, 'index'])
        ->name('laporan.triwulan');

    Route::get('/management', [ManagementController::class, 'index'])->name('management');
    // Tambah akun
    Route::post('/management/store', [ManagementController::class, 'store'])->name('management.store');
    // Tombol aksi
    Route::get('/management/show/{row}', [ManagementController::class, 'show'])->name('management.show');
    Route::post('/management/update/{row}', [ManagementController::class, 'update'])->name('management.update');
    Route::delete('/management/delete/{row}', [ManagementController::class, 'destroy'])->name('management.destroy');


    Route::get('/pengaturan', function () {
        return view('main.pengaturan');
    })->name('pengaturan');
});
