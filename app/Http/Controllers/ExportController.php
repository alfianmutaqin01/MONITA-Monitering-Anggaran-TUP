<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Google_Client;
use Google_Service_Sheets;
use Carbon\Carbon;
use Exception;

class ExportController extends Controller
{
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    /* ======================== 🔧 UTILITAS ======================== */
    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Export PDF');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));
        return new Google_Service_Sheets($client);
    }

    private function parseNumber($v)
    {
        if ($v === null || $v === '') return 0;
        $v = trim((string)$v);
        $clean = preg_replace('/[^0-9\.\,-]/', '', $v);
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);
        return (float)$clean;
    }

    private function toRoman($num)
    {
        return [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'][$num] ?? 'I';
    }

    /* ======================== 📄 LAPORAN TRIWULAN ======================== */
    public function laporanTriwulan(Request $request, $tw)
    {
        $service = $this->getGoogleSheetService();

        $sheetMap = [
            1 => 'RAW Data TW I',
            2 => 'RAW Data TW II',
            3 => 'RAW Data TW III',
            4 => 'RAW Data TW IV',
        ];
        $sheetName = $sheetMap[intval($tw)] ?? $sheetMap[1];
        $range = "{$sheetName}!A2:J700";

        try {
            $resp = $service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $resp->getValues() ?? [];
        } catch (Exception $e) {
            abort(500, 'Gagal membaca data Google Sheets: ' . $e->getMessage());
        }

        /* ---- Filter aktif (unit & type) ---- */
        $filterUnit = $request->query('unit', '');
        $filterType = $request->query('type', 'all');

        $rawData = [];
        foreach ($values as $r) {
            $r = array_pad($r, 10, '');
            if (empty(array_filter($r))) continue;

            $unit = trim($r[1]);
            $type = strtoupper(trim($r[2]));

            // Filter berdasarkan unit (jika ada)
            if ($filterUnit && strcasecmp($unit, $filterUnit) !== 0) continue;

            // Filter berdasarkan tipe anggaran (operasional, remun, bang, ntf)
            if ($filterType !== 'all') {
                if (
                    ($filterType === 'operasional' && !str_contains($type, 'OPER')) ||
                    ($filterType === 'remun' && !str_contains($type, 'REMUN')) ||
                    ($filterType === 'bang' && !str_contains($type, 'BANG')) ||
                    ($filterType === 'ntf' && !str_contains($type, 'NTF'))
                ) continue;
            }

            $rawData[] = [
                'kode_besar' => $r[0],
                'unit'       => $r[1],
                'tipe'       => $r[2],
                'drk_tup'    => $r[3],
                'akun'       => $r[4],
                'nama_akun'  => $r[5],
                'uraian'     => $r[6],
                'anggaran'   => $this->parseNumber($r[7]),
                'realisasi'  => $this->parseNumber($r[8]),
                'saldo'      => $this->parseNumber($r[9]),
            ];
        }

        // Reset nomor urut dari 1
        foreach ($rawData as $i => &$r) $r['no'] = $i + 1;

        $unitLabel = $filterUnit ?: 'Semua Unit';
        $date = Carbon::now()->translatedFormat('d F Y H:i');

        $pdf = Pdf::loadView('exports.laporan-triwulan', [
            'data' => $rawData,
            'tw' => $tw,
            'unit' => $unitLabel,
            'date' => $date,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Triwulan_{$tw}_{$unitLabel}.pdf");
    }

    /* ======================== 📊 SUMMARY EXPORT ======================== */
    public function summary($tw, $type = 'all')
{
    $service = $this->getGoogleSheetService();

    $sheetMap = [
        1 => 'SUMMARY TW I',
        2 => 'SUMMARY TW II',
        3 => 'SUMMARY TW III',
        4 => 'SUMMARY TW IV',
    ];
    $sheetName = $sheetMap[intval($tw)] ?? $sheetMap[1];
    $range = "{$sheetName}!C6:T39";

    try {
        $resp = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $resp->getValues() ?? [];
    } catch (Exception $e) {
        abort(500, 'Gagal membaca data Google Sheets: ' . $e->getMessage());
    }

    $data = [];
    foreach ($values as $r) {
        $r = array_pad($r, 22, '');
        if (empty(array_filter($r))) continue;

        $data[] = [
            'no'            => count($data) + 1,
            'kode_pp'       => $r[0] ?? '',
            'nama_pp'       => $r[1] ?? '',
            'bidang'        => $r[2] ?? '',
            'anggaran_tw'   => $this->parseNumber($r[3] ?? ''),
            'realisasi_tw'  => $this->parseNumber($r[4] ?? ''),
            'saldo_tw'      => $this->parseNumber($r[5] ?? ''),
            'serapan_all'   => trim($r[6] ?? ''),
            'rka_operasi'   => $this->parseNumber($r[7] ?? ''),
            'real_operasi'  => $this->parseNumber($r[8] ?? ''),
            'saldo_operasi' => $this->parseNumber($r[9] ?? ''),
            'serapan_oper'  => trim($r[10] ?? ''),
            'rka_bang'      => $this->parseNumber($r[11] ?? ''),
            'real_bang'     => $this->parseNumber($r[12] ?? ''),
            'sisa_bang'     => $this->parseNumber($r[13] ?? ''),
            'serapan_bang'  => trim($r[14] ?? ''),
            'rkm_operasi'   => $this->parseNumber($r[15] ?? ''),
            'real_rkm'      => $this->parseNumber($r[16] ?? ''),
            'persen_rkm'    => trim($r[17] ?? ''),
        ];
    }

    // Pastikan semua view memiliki struktur data lengkap
    $view = match (strtolower($type)) {
        'rka' => 'exports.summary-rka',
        'rkm' => 'exports.summary-rkm',
        'pengembangan' => 'exports.summary-pengembangan',
        default => 'exports.summary-all',
    };

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, [
        'data' => $data,
        'tw'   => $tw,
    ])->setPaper('a4', 'portrait');

    return $pdf->download("Summary_TW{$tw}_{$type}.pdf");
}


    /* ======================== 🏢 DETAIL UNIT EXPORT ======================== */
    public function detailUnit(Request $request, $kode)
{
    $service = $this->getGoogleSheetService();

    $month = Carbon::now()->month;
    $defaultTw = ($month <= 3) ? 1 : (($month <= 6) ? 2 : (($month <= 9) ? 3 : 4));
    $currentTw = (int)$request->query('tw', $defaultTw);
    $typeFilter = $request->query('type', 'all');

    $sheetName = "RAW Data TW " . $this->toRoman($currentTw);
    $range = "{$sheetName}!B2:J700";

    try {
        $resp = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $resp->getValues() ?? [];
    } catch (Exception $e) {
        abort(500, 'Gagal membaca data Google Sheets: ' . $e->getMessage());
    }

    $data = [];
    foreach ($values as $r) {
        $r = array_pad($r, 9, '');
        if (strtoupper(trim($r[0])) !== strtoupper($kode)) continue;

        $type = strtoupper(trim($r[1] ?? ''));
        if ($typeFilter !== 'all') {
            if (
                ($typeFilter === 'operasional' && !str_contains($type, 'OPER')) ||
                ($typeFilter === 'remun' && !str_contains($type, 'REMUN')) ||
                ($typeFilter === 'bang' && !str_contains($type, 'BANG')) ||
                ($typeFilter === 'ntf' && !str_contains($type, 'NTF'))
            ) continue;
        }

        $data[] = [
            'tipe' => $r[1],
            'drk_tup' => $r[2],
            'akun' => $r[3],
            'nama_akun' => $r[4],
            'uraian' => $r[5],
            'anggaran' => $this->parseNumber($r[6]),
            'realisasi' => $this->parseNumber($r[7]),
            'saldo' => $this->parseNumber($r[8]),
        ];
    }

    // 🔹 Reset nomor & total
    $totalAnggaran = $totalRealisasi = $totalSaldo = 0;
    foreach ($data as $i => &$row) {
        $row['no'] = $i + 1;
        $totalAnggaran += $row['anggaran'];
        $totalRealisasi += $row['realisasi'];
        $totalSaldo += $row['saldo'];
    }

    // 🔹 Kirim ke view export
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.unit-detail', [
        'kode' => $kode,
        'data' => $data,
        'currentTw' => $currentTw,
        'totalAnggaran' => $totalAnggaran,
        'totalRealisasi' => $totalRealisasi,
        'totalSaldo' => $totalSaldo,
    ])->setPaper('a4', 'landscape');

    // 🔹 Download PDF
    return $pdf->download("Detail_Unit_{$kode}_TW{$currentTw}.pdf");
}

}
