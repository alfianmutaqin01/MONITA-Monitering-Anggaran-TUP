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
    // Tambah akun
    Route::post('/management/store', [App\Http\Controllers\ManagementController::class, 'store'])->name('management.store');
    // Tombol aksi
    Route::get('/management/show/{row}', [\App\Http\Controllers\ManagementController::class, 'show'])->name('management.show');
    Route::post('/management/update/{row}', [\App\Http\Controllers\ManagementController::class, 'update'])->name('management.update');
    Route::delete('/management/delete/{row}', [\App\Http\Controllers\ManagementController::class, 'destroy'])->name('management.destroy');


    Route::get('/pengaturan', function () {
        return view('main.pengaturan');
    })->name('pengaturan');
});
