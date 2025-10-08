<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Exception;

class LaporanController extends Controller
{
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Laporan');
        // hanya read, kalau butuh write ubah scope
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }

    /**
     * Index: tampilkan RAW Data TW {1..4}
     * Query param optional: ?unit=AKA (untuk filter)
     */
    public function index(Request $request, $tw = 1)
    {
        $service = $this->getGoogleSheetService();

        // map triwulan ke nama sheet — sesuaikan jika nama sheet berbeda persis
        $sheetMap = [
            1 => 'RAW Data TW I',
            2 => 'RAW Data TW II',
            3 => 'RAW Data TW III',
            4 => 'RAW Data TW IV',
        ];

        $sheetName = $sheetMap[intval($tw)] ?? $sheetMap[1];

        // range data RAW: kolom A..J, baris 2..700
        $range = "{$sheetName}!A2:J700";

        try {
            $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues() ?? [];
        } catch (Exception $e) {
            // kirim error ke view (atau redirect dengan pesan)
            return back()->withErrors(['gs_error' => $e->getMessage()]);
        }

        $data = [];
        $no = 1;

        foreach ($values as $row) {
            // pastikan array panjang minimal 10 kolom
            $row = array_pad($row, 10, '');

            // skip jika seluruh baris kosong
            if (count(array_filter($row)) === 0) {
                continue;
            }

            $data[] = [
    'no'         => $no++,
    'kode_besar' => $row[0] ?? '',
    'unit'       => $row[1] ?? '',
    'tipe'       => $row[2] ?? '',
    'drk_tup'    => $row[3] ?? '',
    'akun'       => $row[4] ?? '',
    'nama_akun'  => $row[5] ?? '',
    'uraian'     => $row[6] ?? '',
    // Gunakan parseNumber() yang diperbaiki di bawah
    'anggaran'   => $this->parseNumber($row[7] ?? ''),
    'realisasi'  => $this->parseNumber($row[8] ?? ''),
    'saldo'      => $this->parseNumber($row[9] ?? ''),
];
        }

        // ambil daftar unit dari sheet Users!B3:B100 untuk dropdown
        $units = [];
        try {
            $unitResp = $service->spreadsheets_values->get($this->spreadsheetId, 'Users!B3:B100');
            $unitVals = $unitResp->getValues() ?? [];
            foreach ($unitVals as $r) {
                if (!empty(trim((string)($r[0] ?? '')))) {
                    $units[] = trim((string)$r[0]);
                }
            }
            // unikkan urutkan
            $units = array_values(array_unique($units));
        } catch (Exception $e) {
            // ignore, units tetap kosong
            $units = [];
        }

        // optional filter berdasarkan unit (GET param)
        $filterUnit = $request->query('unit');
        if ($filterUnit) {
            $data = array_values(array_filter($data, function ($d) use ($filterUnit) {
                return strcasecmp(trim($d['unit']), trim($filterUnit)) === 0;
            }));
        }

        // kirim ke view yang sesuai: resources/views/main/laporan/tw{n}.blade.php
        $viewName = "main.laporan.tw{$tw}";

        return view($viewName, [
            'data' => $data,
            'tw' => (int)$tw,
            'units' => $units,
            'filterUnit' => $filterUnit,
        ]);
    }

    /**
     * Bersihkan dan konversi angka "Rp 1.234.567" -> float 1234567.0
     */
    private function parseNumber($val)
{
    // ubah ke string
    $val = (string)$val;
    if ($val === '' || strtolower($val) === 'null') return 0;

    // hilangkan semua karakter non-digit kecuali koma, titik, minus
    $clean = preg_replace('/[^\d\-,\.]/u', '', $val);

    // hapus spasi & NBSP
    $clean = str_replace(["\u{00A0}", ' '], '', $clean);

    // Jika ada koma DAN titik → anggap titik sebagai pemisah ribuan
    if (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
        // hapus semua titik (ribuan) lalu ganti koma → titik (desimal)
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);
    } else {
        // jika hanya titik atau hanya koma, hapus koma & titik ribuan
        $clean = str_replace([',', '.'], '', $clean);
    }

    // hasil akhir konversi ke float/int
    return $clean === '' ? 0 : (float)$clean;
}
}
