<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        
        // Ambil data TTD dari ENV
        $ttdData = $this->getTTDDataFromEnv($envContent);
        
        return view('main.settings', [
            'sheetYears' => $sheetYears,
            'ttdData' => $ttdData,
            'activeYear' => env('ACTIVE_YEAR'),
            'existingYears' => array_keys($sheetYears), // Kirim data tahun existing ke view
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
        try {
            $envContent = $this->getEnvContent();
        } catch (Exception $e) {
            if ($request->isJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->route('settings.index')->with('error', $e->getMessage());
        }

        // --- MODE 1: TAMBAH/UBAH SPREADSHEET BARU ---
        if ($request->filled('sheet_link') && $request->filled('year')) {
            $request->validate([
                'year' => 'required|numeric|digits:4|min:2020|max:2030',
                'sheet_link' => 'required|url',
            ]);
            
            $link = trim($request->input('sheet_link'));
            $year = trim($request->input('year'));

            // Ekstrak key dari URL
            $pattern = '/https:\/\/docs\.google\.com\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/';
            if (!preg_match($pattern, $link, $m)) {
                return redirect()
                    ->route('settings.index')
                    ->with('error', 'Format link Google Sheet tidak valid. Pastikan link mengandung /spreadsheets/d/<ID>.')
                    ->withInput();
            }
            $key = $m[1];
            
            $envVar = "GOOGLE_SPREADSHEET_ID_YEAR_{$year}";
            
            // Cek apakah tahun sudah ada
            $existingYears = $this->getExistingYears($envContent);
            $isYearExists = in_array($year, $existingYears);
            
            // Jika tahun sudah ada, simpan data sementara di session untuk konfirmasi
            if ($isYearExists && !$request->has('confirmed_override')) {
                $request->session()->put('pending_spreadsheet', [
                    'year' => $year,
                    'key' => $key,
                    'link' => $link
                ]);
                
                return redirect()
                    ->route('settings.index')
                    ->with('warning', "Tahun {$year} sudah ada. Apakah Anda yakin ingin menimpa ID Spreadsheet?")
                    ->with('show_override_modal', true);
            }
            
            // Jika konfirmasi diterima atau tahun baru, simpan data
            $envContent = $this->setEnvValue($envVar, $key, $envContent);
            
            try {
                File::put(base_path('.env'), $envContent);
                
                // Hapus data pending dari session
                $request->session()->forget('pending_spreadsheet');
                
                $message = $isYearExists 
                    ? "Spreadsheet tahun {$year} berhasil diupdate." 
                    : "Spreadsheet tahun {$year} berhasil disimpan.";
                    
                return redirect()
                    ->route('settings.index')
                    ->with('success', $message);
            } catch (Exception $e) {
                return redirect()
                    ->route('settings.index')
                    ->with('error', "Gagal menyimpan ke file .env: " . $e->getMessage());
            }
        }
        
        // --- MODE 2: KONFIRMASI TIMPA DATA (Form khusus untuk konfirmasi) ---
        if ($request->filled('confirm_override') && $request->filled('override_year')) {
            $year = $request->input('override_year');
            $key = $request->input('override_key');
            
            $envVar = "GOOGLE_SPREADSHEET_ID_YEAR_{$year}";
            $envContent = $this->setEnvValue($envVar, $key, $envContent);
            
            try {
                File::put(base_path('.env'), $envContent);
                $request->session()->forget('pending_spreadsheet');
                
                return redirect()
                    ->route('settings.index')
                    ->with('success', "Spreadsheet tahun {$year} berhasil diupdate.");
            } catch (Exception $e) {
                return redirect()
                    ->route('settings.index')
                    ->with('error', "Gagal mengupdate spreadsheet: " . $e->getMessage());
            }
        }
        
        // --- MODE 3: BATAL TIMPA DATA ---
        if ($request->filled('cancel_override')) {
            $request->session()->forget('pending_spreadsheet');
            return redirect()
                ->route('settings.index')
                ->with('info', 'Proses penimpaan spreadsheet dibatalkan.');
        }
        
        // --- MODE 4: AKTIVASI TAHUN TERTENTU (AJAX) ---
        if ($request->isJson() && $request->has('active_year')) {
            $year = $request->json('active_year');
            $key = $request->json('spreadsheet_key');
            
            if (!$year || !$key) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tahun atau kunci spreadsheet tidak lengkap.'
                ], 400);
            }
            
            // Update ACTIVE_YEAR dan GOOGLE_SPREADSHEET_ID
            $envContent = $this->setEnvValue('ACTIVE_YEAR', $year, $envContent);
            $envContent = $this->setEnvValue('GOOGLE_SPREADSHEET_ID', $key, $envContent);

            try {
                File::put(base_path('.env'), $envContent);
                return response()->json([
                    'success' => true,
                    'message' => 'Tahun aktif berhasil diperbarui.'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage()
                ], 500);
            }
        }
        
        // --- MODE 5: UPDATE DATA PENANDA TANGAN (TTD) ---
        if ($request->isMethod('post') && $request->has('ttd_jabatan_1_input')) {
            $request->validate([
                'ttd_jabatan_1_input' => 'nullable|string|max:100',
                'ttd_nama_1_input' => 'nullable|string|max:100',
                'ttd_nip_1_input' => 'nullable|string|max:50',
                'ttd_jabatan_2_input' => 'nullable|string|max:100',
                'ttd_nama_2_input' => 'nullable|string|max:100',
                'ttd_nip_2_input' => 'nullable|string|max:50',
            ]);

            // Update semua variabel TTD
            $envContent = $this->setEnvValue('TTD_JABATAN_1', $request->ttd_jabatan_1_input, $envContent);
            $envContent = $this->setEnvValue('TTD_NAMA_1', $request->ttd_nama_1_input, $envContent);
            $envContent = $this->setEnvValue('TTD_NIP_1', $request->ttd_nip_1_input, $envContent);
            $envContent = $this->setEnvValue('TTD_JABATAN_2', $request->ttd_jabatan_2_input, $envContent);
            $envContent = $this->setEnvValue('TTD_NAMA_2', $request->ttd_nama_2_input, $envContent);
            $envContent = $this->setEnvValue('TTD_NIP_2', $request->ttd_nip_2_input, $envContent);
            
            try {
        File::put(base_path('.env'), $envContent);
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data Penanda Tangan berhasil diperbarui.']);
        }
        return redirect()->route('settings.index')->with('success', 'Data Penanda Tangan berhasil diperbarui.');
    } catch (Exception $e) {
        if ($request->wantsJson()) {
             return response()->json(['success' => false, 'message' => 'Gagal menyimpan data TTD: ' . $e->getMessage()], 500);
        }
        return redirect()->route('settings.index')->with('error', 'Gagal menyimpan data TTD: ' . $e->getMessage());
    }
        }

        return redirect()
            ->route('settings.index')
            ->with('error', 'Permintaan tidak dikenali.');
    }
}