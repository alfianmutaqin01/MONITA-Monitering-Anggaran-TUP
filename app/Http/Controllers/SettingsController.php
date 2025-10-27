<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        $envPath = base_path('.env');
        $envContent = File::exists($envPath) ? File::get($envPath) : '';

        // Ambil semua entri spreadsheet tahunan
        preg_match_all('/GOOGLE_SPREADSHEET_ID_YEAR_(\d+)=([^\s]+)/', $envContent, $matches);
        $sheetYears = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $i => $year) {
                $sheetYears[$year] = $matches[2][$i];
            }
        }

        return view('main.settings', compact('sheetYears'));
    }

    /**
     * Tambah link spreadsheet baru atau ubah tahun aktif.
     */
    public function update(Request $request)
    {
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            return response()->json(['success' => false, 'message' => '.env file tidak ditemukan.']);
        }

        $envContent = File::get($envPath);

        /**
         * 1️⃣ MODE 1 — Tambah spreadsheet baru dari form (sheet_link + year)
         */
        if ($request->filled('sheet_link') && $request->filled('year')) {
            $link = trim($request->input('sheet_link'));
            $year = trim($request->input('year'));

            // Ekstrak key dari URL
            // ✅ Validasi link Google Sheets
            $pattern = '/https:\/\/docs\.google\.com\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/';
            if (!preg_match($pattern, $link, $m)) {
                return redirect()
                    ->route('settings.index')
                    ->with('error', 'Format link Google Sheet tidak valid. Pastikan link mengandung /spreadsheets/d/<ID>.');
            }

            $key = $m[1];


            $envVar = "GOOGLE_SPREADSHEET_ID_YEAR_{$year}";

            // Ganti jika sudah ada, kalau belum tambahkan
            if (preg_match("/^{$envVar}=.*$/m", $envContent)) {
                $envContent = preg_replace("/^{$envVar}=.*$/m", "{$envVar}={$key}", $envContent);
            } else {
                $envContent .= "\n{$envVar}={$key}";
            }

            File::put($envPath, $envContent);

            return redirect()->route('settings.index')->with('success', "Spreadsheet tahun {$year} berhasil disimpan.");
        }

        /**
         * 2️⃣ MODE 2 — AJAX: Aktifkan tahun tertentu
         */
        if ($request->isJson()) {
            $data = $request->json()->all();
            $year = $data['active_year'] ?? null;
            $key = $data['spreadsheet_key'] ?? null;

            if (!$year || !$key) {
                return response()->json(['success' => false, 'message' => 'Data tidak lengkap.']);
            }

            // Update / tambah GOOGLE_SPREADSHEET_ID & ACTIVE_YEAR
            $envContent = preg_replace('/^GOOGLE_SPREADSHEET_ID=.*$/m', "GOOGLE_SPREADSHEET_ID={$key}", $envContent);
            if (!str_contains($envContent, 'GOOGLE_SPREADSHEET_ID=')) {
                $envContent .= "\nGOOGLE_SPREADSHEET_ID={$key}";
            }

            $envContent = preg_replace('/^ACTIVE_YEAR=.*$/m', "ACTIVE_YEAR={$year}", $envContent);
            if (!str_contains($envContent, 'ACTIVE_YEAR=')) {
                $envContent .= "\nACTIVE_YEAR={$year}";
            }

            File::put($envPath, $envContent);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Permintaan tidak valid.']);
    }
}
