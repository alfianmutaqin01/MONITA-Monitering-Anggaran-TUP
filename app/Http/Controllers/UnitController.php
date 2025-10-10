<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Carbon\Carbon;

class UnitController extends Controller
{
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
     * Konversi angka (1..4) ke Romawi
     */
    private function toRoman($number)
    {
        return [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'][$number] ?? 'I';
    }

    /**
     * Robust parsing number dari string Excel / Google Sheets
     * Meng-handle: "Rp 1.234.567", "(1.234.567)", "1.234.567,00", "66,67%", "0", dll
     * Mengembalikan float.
     */
    private function parseNumber($val)
    {
        if ($val === null || $val === '') return 0.0;
        if (is_numeric($val)) return (float)$val;

        $s = trim((string)$val);

        // tanda negatif dalam kurung: (1.234) => -1234
        $negative = false;
        if (preg_match('/^\((.*)\)$/', $s, $m)) {
            $s = $m[1];
            $negative = true;
        }

        // hilangkan rp / persen / spasi / NBSP
        $s = str_ireplace(['rp', '%', ' ', "\xc2\xa0"], '', $s);

        // jika ada titik dan koma -> anggap titik ribuan, koma desimal
        if (strpos($s, '.') !== false && strpos($s, ',') !== false) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif (strpos($s, ',') !== false && strpos($s, '.') === false) {
            // hanya koma -> koma sebagai desimal
            $s = str_replace(',', '.', $s);
        } elseif (strpos($s, '.') !== false && strpos($s, ',') === false) {
            // hanya titik -> titik kemungkinan ribuan -> hapus titik
            $s = str_replace('.', '', $s);
        }

        // hapus karakter selain digit, minus dan titik
        $s = preg_replace('/[^0-9\.\-]/', '', $s);

        if ($s === '' || $s === '.' || $s === '-') return 0.0;

        $num = (float)$s;
        if ($negative) $num = -$num;

        return $num;
    }

    /**
     * Ambil daftar unit (kode => nama) dari tab Users kolom B:C
     * safe: jika error (permission atau sheet tidak ada) kembalikan array kosong
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
            // jangan menimbulkan error fatal saat Users tidak bisa dibaca
            return [];
        }
    }

    /**
     * Tampilkan halaman detail unit
     * Route harus mengirimkan session('user_data') yang berisi minimal ['role' => 'admin'|'user', 'kode_pp' => 'LAB'...]
     */
    public function show(Request $request, $kode)
    {
        $service = $this->getGoogleSheetService();

        // cek session user_data terlebih dahulu
        $user = session('user_data', null);
        if (!$user) {
            abort(403, 'User tidak terautentikasi (session user_data hilang).');
        }

        // admin boleh akses semua, user hanya unit sendiri
        if (($user['role'] ?? '') !== 'admin' && (($user['kode_pp'] ?? '') !== $kode)) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        // ambil daftar unit (fallback jika gagal)
        $units = $this->getUnitsFromSheet($service);
        $namaUnit = $units[$kode] ?? strtoupper($kode);

        // triwulan dari qparam atau default bulan sekarang
        $month = Carbon::now()->month;
        $defaultTw = ($month <= 3) ? 1 : (($month <= 6) ? 2 : (($month <= 9) ? 3 : 4));
        $currentTw = (int)$request->query('tw', $defaultTw);
        $currentTw = max(1, min(4, $currentTw));

        $sheetName = "RAW Data TW " . $this->toRoman($currentTw);

        // ambil B2:J700 (B..J sesuai spesifikasi)
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
            // jika gagal ambil data, tampilkan halaman dengan pesan tapi tidak crash
            $filtered = [];
            $sumByType = [
                'Operasional' => 0,
                'Remun' => 0,
                'NTF' => 0,
                'Bangunan' => 0
            ];
            $totalAll = 0;

            return view('main.unit.dynamic', [
                'kode' => $kode,
                'namaUnit' => $namaUnit,
                'filtered' => $filtered,
                'sumByType' => array_map(fn($v) => 'Rp ' . number_format($v, 0, ',', '.'), $sumByType),
                'totalAll' => 'Rp ' . number_format($totalAll, 0, ',', '.'),
                'currentTw' => $currentTw,
                'errorMessage' => 'Gagal terhubung ke Google Sheets: ' . $e->getMessage()
            ]);
        }

        // filter baris unit
        $filtered = [];
        foreach ($values as $r) {
            $r = array_pad($r, 9, '');
            $unitCol = strtoupper(trim($r[0] ?? ''));
            if ($unitCol === strtoupper($kode)) {
                $tipe = trim($r[1] ?? '');
                $drk_tup = trim($r[2] ?? '');
                $akun = trim($r[3] ?? '');
                $nama_akun = trim($r[4] ?? '');
                $uraian = trim($r[5] ?? '');
                $anggaran = $this->parseNumber($r[6] ?? '');
                $realisasi = $this->parseNumber($r[7] ?? '');
                $saldo = $this->parseNumber($r[8] ?? '');

                $filtered[] = [
                    'unit' => $unitCol,
                    'tipe' => $tipe,
                    'drk_tup' => $drk_tup,
                    'akun' => $akun,
                    'nama_akun' => $nama_akun,
                    'uraian' => $uraian,
                    'anggaran' => $anggaran,
                    'realisasi' => $realisasi,
                    'saldo' => $saldo,
                ];
            }
        }

        // beri nomor (backend)
        foreach ($filtered as $i => &$row) {
            $row['no'] = $i + 1;
        }
        unset($row);

        // hitung total per tipe (menggunakan pencocokan robust)
        $totalAll = 0;
        $operasi = 0;
        $remun = 0;
        $ntf = 0;
        $bang = 0;

        foreach ($filtered as $r) {
            $s = (float) $r['saldo'];
            $totalAll += $s;
            $label = strtoupper(trim($r['tipe'] ?? ''));
            if (strpos($label, 'OPER') !== false) $operasi += $s;
            elseif (strpos($label, 'REMUN') !== false) $remun += $s;
            elseif (strpos($label, 'NTF') !== false) $ntf += $s;
            elseif (strpos($label, 'BANG') !== false || strpos($label, 'BANGUN') !== false) $bang += $s;
        }

        // format tampil (Rp)
        $sumByTypeFormatted = [
            'Operasional' => 'Rp ' . number_format($operasi, 0, ',', '.'),
            'Remun'       => 'Rp ' . number_format($remun, 0, ',', '.'),
            'NTF'         => 'Rp ' . number_format($ntf, 0, ',', '.'),
            'Bangunan'    => 'Rp ' . number_format($bang, 0, ',', '.'),
        ];

        $totalAllFormatted = 'Rp ' . number_format($totalAll, 0, ',', '.');

        // kembalikan view (dynamic per unit)
        return view('main.unit.dynamic', [
            'kode' => $kode,
            'namaUnit' => $namaUnit,
            'filtered' => $filtered,
            'sumByType' => $sumByTypeFormatted,
            'totalAll' => $totalAllFormatted,
            'currentTw' => $currentTw,
        ]);
    }
}
