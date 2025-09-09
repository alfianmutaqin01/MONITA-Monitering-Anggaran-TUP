<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnitController;

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
});

// Dashboard, semua user bisa akses
Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth.spreadsheet')
    ->name('dashboard');

// Unit page, hanya akses sesuai role
Route::get('/unit/{name}', [UnitController::class, 'show'])
    ->middleware('auth.spreadsheet')
    ->name('unit');

// Summary (hanya admin)
Route::get('/trivulan/{id}', [UnitController::class, 'showSummary'])
    ->middleware('auth.spreadsheet:admin')
    ->name('trivulan');


// Route untuk testing Google Sheets (debug)
Route::get('/test-sheets', function () {
    try {
        // Inisialisasi Google Client (pakai fully-qualified names — jangan pakai `use Google_Client;`)
        $client = new \Google_Client();
        $client->setApplicationName('Monita Test');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        $service = new \Google_Service_Sheets($client);

        // Ganti dengan ID Google Sheets Anda, atau simpan di .env dan panggil env('GOOGLE_SPREADSHEET_ID')
        $spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '1uW-PcFMYdWrb30kcFKwxi3UpMnt8CSUx673PQgVcDao');
        $range = 'users!A1:F5'; // baca 5 baris pertama

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            return response('Tidak ada data yang ditemukan di Google Sheets.', 200);
        }

        // Untuk debugging, tampilkan json (lebih aman daripada dd di production route)
        return response()->json($values);
    } catch (\Exception $e) {
        return response('Error saat membaca Google Sheets: ' . $e->getMessage(), 500);
    }
});
