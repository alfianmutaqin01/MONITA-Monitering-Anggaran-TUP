<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Carbon\Carbon;
use App\Traits\FormatDataTrait; // Pastikan Trait ini ada dan berisi parseNumber/toRoman

class UnitController extends Controller
{
    use FormatDataTrait;

    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '');
    }

    /**
     * Inisialisasi Google Sheets client (readonly)
     */
    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Detail Anggaran Unit');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));
        return new Google_Service_Sheets($client);
    }

    /**
     * Ambil daftar unit (kode => nama) dari tab Users kolom B:C
     */
    private function getUnitsFromSheet($service)
    {
        try {
            $resp = $service->spreadsheets_values->get(
                $this->spreadsheetId,
                'Users!B3:C100',
                ['valueRenderOption' => 'UNFORMATTED_VALUE']
            );
            $vals = $resp->getValues() ?? [];
            $map = [];
            foreach ($vals as $r) {
                $kode = isset($r[0]) ? trim((string)$r[0]) : '';
                $nama = isset($r[1]) ? trim((string)$r[1]) : '';
                if ($kode !== '') $map[$kode] = $nama;
            }
            return $map;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Tampilkan halaman detail unit
     */
    public function show(Request $request, $kode)
    {
        $service = $this->getGoogleSheetService();

        // === Cek Akses ===
        $user = session('user_data', null);
        if (!$user) {
            abort(403, 'User tidak terautentikasi (session user_data hilang).');
        }
        if (($user['role'] ?? '') !== 'admin' && (($user['kode_pp'] ?? '') !== $kode)) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        // ambil daftar unit
        $units = $this->getUnitsFromSheet($service);
        $namaUnit = $units[$kode] ?? strtoupper($kode);

        // === Tentukan Triwulan (Filter Waktu) ===
        $month = Carbon::now()->month;
        $defaultTw = ($month <= 3) ? 1 : (($month <= 6) ? 2 : (($month <= 9) ? 3 : 4));
        $currentTw = (int)$request->query('tw', $defaultTw);
        $currentTw = max(1, min(4, $currentTw));

        $currentType = $request->query('type', 'all'); // Filter Jenis Anggaran

        $sheetName = "RAW Data TW " . $this->toRoman($currentTw);
        $startRow = 2;
        $endRow = 700;
        $range = "{$sheetName}!B{$startRow}:J{$endRow}";

        try {
            $resp = $service->spreadsheets_values->get(
                $this->spreadsheetId,
                $range,
                ['valueRenderOption' => 'UNFORMATTED_VALUE']
            );
            $values = $resp->getValues() ?? [];
        } catch (\Exception $e) {
            // Return view error dengan data nol
            return view('main.unit.dynamic', [
                'kode' => $kode,
                'namaUnit' => $namaUnit,
                'filtered' => [],
                'sumByType' => ['Operasional' => 'Rp 0', 'Remun' => 'Rp 0', 'NTF' => 'Rp 0', 'Bang' => 'Rp 0'],
                'totalAll' => 'Rp 0',
                'currentTw' => $currentTw,
                'currentType' => $currentType,
                'errorMessage' => 'Gagal terhubung ke Google Sheets: ' . $e->getMessage()
            ]);
        }

        // === TAHAP 1: EKSTRAKSI DATA HANYA BERDASARKAN KODE UNIT DAN HITUNG TOTAL UNTUK CARD ===
        $rawUnitData = [];
        $totalAll = 0;
        $sumByType = ['Operasional' => 0, 'Remun' => 0, 'NTF' => 0, 'Bang' => 0];

        foreach ($values as $r) {
            $r = array_pad($r, 9, '');
            $unitCol = strtoupper(trim($r[0] ?? ''));
            if ($unitCol !== strtoupper($kode)) continue;

            // Parsing nilai untuk perhitungan total
            $tipe = trim($r[1] ?? '');
            $saldo = $this->parseNumber($r[8] ?? '');
            
            // Simpan data mentah unit
            $rawUnitData[] = [
                'unit' => $unitCol,
                'tipe' => $tipe,
                'drk_tup' => trim($r[2] ?? ''),
                'akun' => trim($r[3] ?? ''),
                'nama_akun' => trim($r[4] ?? ''),
                'uraian' => trim($r[5] ?? ''),
                'anggaran' => $this->parseNumber($r[6] ?? ''),
                'realisasi' => $this->parseNumber($r[7] ?? ''),
                'saldo' => $saldo, // Sudah berupa float
            ];

            // Hitung Total Saldo untuk Card (Logika ini TIDAK boleh terpengaruh filter $currentType)
            $totalAll += $saldo;
            $label = strtoupper($tipe);
            if (strpos($label, 'OPER') !== false) $sumByType['Operasional'] += $saldo;
            elseif (strpos($label, 'REMUN') !== false) $sumByType['Remun'] += $saldo;
            elseif (strpos($label, 'NTF') !== false) $sumByType['NTF'] += $saldo;
            elseif (strpos($label, 'BANG') !== false) $sumByType['Bang'] += $saldo;
        }

        // Format Total Saldo untuk Card
        $sumByTypeFormatted = array_map(fn($v) => 'Rp ' . number_format($v, 0, ',', '.'), $sumByType);
        $totalAllFormatted = 'Rp ' . number_format($totalAll, 0, ',', '.');


        // === TAHAP 2: FILTER DATA RINCI UNTUK TABEL (Memerlukan $currentType) ===
        $filtered = [];
        $typeFilterMatch = fn($typeUpper, $filter) => match ($filter) {
            'operasional' => strpos($typeUpper, 'OPER') !== false,
            'remun' => strpos($typeUpper, 'REMUN') !== false,
            'bang' => strpos($typeUpper, 'BANG') !== false,
            'ntf' => strpos($typeUpper, 'NTF') !== false,
            default => true, // 'all'
        };

        foreach ($rawUnitData as $r) {
            $tipeUpper = strtoupper($r['tipe']);
            
            if ($currentType === 'all' || $typeFilterMatch($tipeUpper, $currentType)) {
                // Beri nomor sebelum dimasukkan ke filtered
                $r['no'] = count($filtered) + 1;
                $filtered[] = $r;
            }
        }

        // === TAHAP AKHIR: Kirim Data ke View ===
        return view('main.unit.dynamic', [
            'kode' => $kode,
            'namaUnit' => $namaUnit,
            'filtered' => $filtered, // Data rinci yang sudah difilter
            'sumByType' => $sumByTypeFormatted, // Data kartu ringkasan (TOTAL, tidak terfilter jenis)
            'totalAll' => $totalAllFormatted,   // Data kartu ringkasan (TOTAL, tidak terfilter jenis)
            'currentTw' => $currentTw,
            'currentType' => $currentType,
        ]);
    }
} 
