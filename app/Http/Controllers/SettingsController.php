<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\LoginActivity;
use Exception;

class SettingsController extends Controller
{
    private function getEnvContent()
    {
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            throw new Exception("File .env tidak ditemukan.");
        }
        return File::get($envPath);
    }

    /**
     * Helper: Mengganti atau menambahkan variabel di file .env dengan quote yang aman.
     */
    private function setEnvValue($key, $value, $envContent)
    {
        // Bersihkan nilai dari quote dan spasi
        $value = trim($value);
        $value = str_replace(['"', "'"], '', $value);
        
        // Escape karakter khusus
        $value = preg_replace('/[\r\n]/', '', $value);
        
        $newValue = "{$key}=\"{$value}\"";
        
        // Cari baris yang mengandung key tersebut (dengan atau tanpa quote)
        $pattern = "/^{$key}=.*$/m";
        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $newValue, $envContent);
        } else {
            $envContent .= "\n" . $newValue;
        }
        
        return $envContent;
    }

    /**
     * Ambil semua tahun yang sudah tersimpan dari ENV
     */
    private function getExistingYears($envContent)
    {
        preg_match_all('/GOOGLE_SPREADSHEET_ID_YEAR_(\d+)="?([^"\s]+)"?/', $envContent, $matches);
        $years = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $year) {
                $years[] = $year;
            }
        }
        return $years;
    }

    public function index()
    {
        try {
            $envContent = $this->getEnvContent();
        } catch (Exception $e) {
            return view('main.settings', [
                'sheetYears' => [], 
                'ttdData' => $this->getDefaultTTDData(), 
                'activeYear' => null, 
                'error' => $e->getMessage()
            ]);
        }

        // Ambil semua entri spreadsheet tahunan (dengan atau tanpa quote)
        preg_match_all('/GOOGLE_SPREADSHEET_ID_YEAR_(\d+)="?([^"\s]+)"?/', $envContent, $matches);
        $sheetYears = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $i => $year) {
                $sheetYears[$year] = trim($matches[2][$i], '"');
            }
        }
        
        $ttdData = $this->getTTDDataFromEnv($envContent);
        
        return view('main.settings', [
            'sheetYears' => $sheetYears,
            'ttdData' => $ttdData,
            'activeYear' => env('ACTIVE_YEAR'),
            'existingYears' => array_keys($sheetYears), 
            'loginLogs' => LoginActivity::orderBy('login_time', 'desc')->limit(10)->get(),

        ]);
    }

    /**
     * Ambil data TTD dari konten ENV secara langsung
     */
    private function getTTDDataFromEnv($envContent)
    {
        $data = [];
        $ttdKeys = [
            'TTD_JABATAN_1', 'TTD_NAMA_1', 'TTD_NIP_1',
            'TTD_JABATAN_2', 'TTD_NAMA_2', 'TTD_NIP_2'
        ];
        
        foreach ($ttdKeys as $key) {
            if (preg_match("/^{$key}=\"?(.*?)\"?$/m", $envContent, $matches)) {
                $data[strtolower($key)] = trim($matches[1], '"');
            } else {
                $data[strtolower($key)] = '';
            }
        }
        
        return $data;
    }

    /**
     * Data TTD default
     */
    private function getDefaultTTDData()
    {
        return [
            'ttd_jabatan_1' => '',
            'ttd_nama_1' => '',
            'ttd_nip_1' => '',
            'ttd_jabatan_2' => '',
            'ttd_nama_2' => '',
            'ttd_nip_2' => '',
        ];
    }

    public function update(Request $request)
{
    // Pastikan respon selalu JSON jika diminta
    if (!$request->wantsJson() && !$request->isJson()) {
        return redirect()->route('settings.index'); 
    }

    try {
        $envContent = $this->getEnvContent();
    } catch (Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }

    // --- MODE 1: TAMBAH/UPDATE SPREADSHEET ---
    if ($request->has('sheet_link') && $request->has('year')) {
        $validator = \Validator::make($request->all(), [
            'year' => 'required|numeric|digits:4|min:2020|max:2030',
            'sheet_link' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        
        $link = trim($request->input('sheet_link'));
        $year = trim($request->input('year'));

        // Ekstrak ID dari URL
        if (!preg_match('/https:\/\/docs\.google\.com\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $link, $m)) {
            return response()->json(['success' => false, 'message' => 'Link tidak valid. Harus mengandung /spreadsheets/d/ID'], 400);
        }
        $key = $m[1];
        
        $envVar = "GOOGLE_SPREADSHEET_ID_YEAR_{$year}";
        
        // Cek apakah tahun sudah ada
        $existingYears = $this->getExistingYears($envContent);
        $isYearExists = in_array($year, $existingYears);
        
        // JIKA TAHUN ADA DAN BELUM DIKONFIRMASI (confirmed_override dikirim dari JS)
        if ($isYearExists && $request->input('confirmed_override') != '1') {
            return response()->json([
                'success' => false, 
                'needs_override' => true, // Flag untuk JS memunculkan SweetAlert
                'message' => "Tahun {$year} sudah ada. Timpa data?",
                'year' => $year,
                'key' => $key // Kirim key baru untuk preview
            ]);
        }
        
        // Simpan Data
        $envContent = $this->setEnvValue($envVar, $key, $envContent);
        
        try {
            File::put(base_path('.env'), $envContent);
            
            return response()->json([
                'success' => true, 
                'message' => "Spreadsheet tahun {$year} berhasil disimpan.",
                'data' => [
                    'year' => $year,
                    'key' => $key,
                    'link' => "https://docs.google.com/spreadsheets/d/{$key}"
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => "Gagal tulis .env: " . $e->getMessage()], 500);
        }
    }

    // --- MODE 2: AKTIVASI TAHUN (Sudah AJAX di kode lama, kita rapikan) ---
    if ($request->has('active_year')) {
        $year = $request->input('active_year');
        $key = $request->input('spreadsheet_key');
        
        $envContent = $this->setEnvValue('ACTIVE_YEAR', $year, $envContent);
        $envContent = $this->setEnvValue('GOOGLE_SPREADSHEET_ID', $key, $envContent); // Opsional jika app pakai ini

        File::put(base_path('.env'), $envContent);
        return response()->json(['success' => true, 'message' => "Tahun aktif diubah ke {$year}."]);
    }
    
    // --- MODE 3: UPDATE TTD (Data Penanda Tangan) ---
    // Kita cek input dari JS (mapping nama field disesuaikan di JS nanti)
    if ($request->has('ttd_jabatan_1')) {
        $fields = ['ttd_jabatan_1', 'ttd_nama_1', 'ttd_nip_1', 'ttd_jabatan_2', 'ttd_nama_2', 'ttd_nip_2'];
        
        foreach ($fields as $field) {
            // Ubah key request (lowercase) menjadi key ENV (UPPERCASE)
            $envKey = strtoupper($field); 
            $value = $request->input($field, '');
            $envContent = $this->setEnvValue($envKey, $value, $envContent);
        }
        
        File::put(base_path('.env'), $envContent);
        return response()->json(['success' => true, 'message' => 'Data TTD berhasil diperbarui.']);
    }

    return response()->json(['success' => false, 'message' => 'Request tidak dikenali.'], 400);
}}