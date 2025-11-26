<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    private $service;
    private $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '');
        
        try {
            $client = new Client();
            $client->setApplicationName('Monita System');
            $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
            $client->setAuthConfig(storage_path('app/credentials/service-account.json'));
            
            $this->service = new Sheets($client);
        } catch (\Exception $e) {
            Log::error('Google Sheets Service initialization failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ambil data units dari Google Sheets - tab users kolom B dan C mulai dari B3
     */
    public function getUnitsFromSheet()
    {
        return Cache::remember('sidebar_units_data', 3600, function () { // Cache 1 jam
            try {
                if (empty($this->spreadsheetId)) {
                    Log::warning('GOOGLE_SPREADSHEET_ID tidak ditemukan di .env');
                    return config('units', []);
                }

                // Ambil data dari tab "users", kolom B3 sampai C (akhir data)
                // Kolom B = kode_pp, Kolom C = nama_pp
                $range = 'users!B3:C';
                $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
                $values = $response->getValues();

                $units = [];
                
                if (!empty($values)) {
                    foreach ($values as $row) {
                        // Pastikan row memiliki minimal 2 kolom (B = kode_pp, C = nama_pp)
                        // Skip row jika ada kolom yang kosong
                        if (count($row) >= 2 && !empty(trim($row[0])) && !empty(trim($row[1]))) {
                            $code = trim($row[0]);  // Kolom B - kode_pp
                            $name = trim($row[1]);  // Kolom C - nama_pp
                            $units[$code] = $name;
                        }
                    }
                }

                Log::info('Berhasil mengambil data units dari Google Sheets. Total: ' . count($units));
                return $units;

            } catch (\Exception $e) {
                Log::error('Error fetching units from Google Sheets: ' . $e->getMessage());
                
                // Fallback ke config file jika Google Sheets error
                return config('units', []);
            }
        });
    }

    /**
     * Ambil semua data users (untuk kompatibilitas dengan AuthController)
     */
    public function getUsersFromSheet()
    {
        try {
            if (empty($this->spreadsheetId)) {
                return [];
            }

            $range = 'users!A2:F'; // Sesuai dengan range di AuthController
            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues();

            $users = [];
            if (empty($values)) {
                return $users;
            }

            foreach ($values as $row) {
                $users[] = [
                    'no'        => $row[0] ?? '',
                    'kode_pp'   => $row[1] ?? '',
                    'nama_pp'   => $row[2] ?? '',
                    'username'  => $row[3] ?? '',
                    'password'  => $row[4] ?? '',
                    'role'      => strtolower($row[5] ?? 'user'),
                ];
            }

            return $users;

        } catch (\Exception $e) {
            Log::error('Error fetching users from Google Sheets: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear cache units
     */
    public function clearUnitsCache()
    {
        return Cache::forget('sidebar_units_data');
    }

    use \App\Traits\FormatDataTrait;

    /**
     * Ambil data Laporan (RAW Data) berdasarkan Triwulan
     * Mengembalikan array of LaporanModel
     */
    public function getLaporan($tw)
    {
        $tw = max(1, min(4, (int)$tw));
        
        $sheetMap = [
            1 => 'RAW Data TW I',
            2 => 'RAW Data TW II',
            3 => 'RAW Data TW III',
            4 => 'RAW Data TW IV',
        ];

        $sheetName = $sheetMap[intval($tw)] ?? $sheetMap[1];
        $range = "{$sheetName}!A2:J700";

        try {
            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues() ?? [];

            $data = [];
            $no = 1;

            foreach ($values as $row) {
                $row = array_pad($row, 10, '');
                if (count(array_filter($row)) === 0) continue;

                $data[] = new \App\Models\LaporanModel([
                    'no'         => $no++,
                    'kode_besar' => $row[0] ?? '',
                    'unit'       => $row[1] ?? '',
                    'tipe'       => $row[2] ?? '',
                    'drk_tup'    => $row[3] ?? '',
                    'akun'       => $row[4] ?? '',
                    'nama_akun'  => $row[5] ?? '',
                    'uraian'     => $row[6] ?? '',
                    'anggaran'   => $this->parseNumber($row[7] ?? ''),
                    'realisasi'  => $this->parseNumber($row[8] ?? ''),
                    'saldo'      => $this->parseNumber($row[9] ?? ''),
                ]);
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('Error fetching Laporan: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ambil data Summary berdasarkan Triwulan
     * Mengembalikan array of SummaryModel
     */
    public function getSummary($tw)
    {
        $tw = max(1, min(4, (int)$tw));
        $sheetName = 'SUMMARY TW ' . $this->toRoman($tw);
        $range = "{$sheetName}!C6:T39";

        try {
            // Cek ketersediaan sheet
            $sheets = $this->service->spreadsheets->get($this->spreadsheetId)->getSheets();
            $sheetNames = collect($sheets)->pluck('properties.title')->toArray();

            if (!in_array($sheetName, $sheetNames)) {
                throw new \Exception("Tab '{$sheetName}' tidak ditemukan.");
            }

            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues() ?? [];

            $data = [];
            foreach ($values as $i => $r) {
                if (empty(array_filter($r))) continue;
                $r = array_pad($r, 22, '');

                $data[] = new \App\Models\SummaryModel([
                    'no'            => $i + 1,
                    'kode_pp'       => $r[0],
                    'nama_pp'       => $r[1],
                    'bidang'        => $r[2],
                    'anggaran_tw'   => $this->parseNumber($r[3]),
                    'realisasi_tw'  => $this->parseNumber($r[4]),
                    'saldo_tw'      => $this->parseNumber($r[5]),
                    'serapan_all'   => trim($r[6]),
                    'rka_operasi'   => $this->parseNumber($r[7]),
                    'real_operasi'  => $this->parseNumber($r[8]),
                    'saldo_operasi' => $this->parseNumber($r[9]),
                    'serapan_oper'  => trim($r[10]),
                    'rka_bang'      => $this->parseNumber($r[11]),
                    'real_bang'     => $this->parseNumber($r[12]),
                    'sisa_bang'     => $this->parseNumber($r[13]),
                    'serapan_bang'  => trim($r[14]),
                    'rkm_operasi'   => $this->parseNumber($r[15]),
                    'real_rkm'      => $this->parseNumber($r[16]),
                    'persen_rkm'    => trim($r[17]),
                ]);
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('Error fetching Summary: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Test koneksi ke Google Sheets
     */
    public function testConnection()
    {
        try {
            $range = 'users!B3:C';
            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues();
            
            return [
                'success' => true,
                'data_count' => count($values),
                'message' => 'Koneksi berhasil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}