<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Dashboard');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }

    /**
     * Ambil nilai satu sel (mis. SUMMARY TW I!H39)
     */
    private function getCellValue($service, $range)
    {
        $params = ['valueRenderOption' => 'UNFORMATTED_VALUE'];
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range, $params);
        $value = $response->getValues()[0][0] ?? 0;

        // Jika string berformat ribuan (1.695.062,00) → bersihkan
        if (!is_numeric($value)) {
            $value = preg_replace('/[^0-9.\-]/', '', (string)$value);
        }

        return (float) $value;
    }

    /**
     * Dashboard utama -> saldo + chart serapan + chart realisasi
     */
    public function index(Request $request)
    {
        $service = $this->getGoogleSheetService();

        // --- Ambil total saldo dari tiap triwulan
        $saldoTW1 = $this->getCellValue($service, 'SUMMARY TW I!H39');
        $saldoTW2 = $this->getCellValue($service, 'SUMMARY TW II!H39');
        $saldoTW3 = $this->getCellValue($service, 'SUMMARY TW III!H39');
        $saldoTW4 = $this->getCellValue($service, 'SUMMARY TW IV!H39');

        // --- Tentukan triwulan aktif
        $month = Carbon::now()->month;
        if ($month <= 3) $defaultTw = 1;
        elseif ($month <= 6) $defaultTw = 2;
        elseif ($month <= 9) $defaultTw = 3;
        else $defaultTw = 4;

        $currentTw = (int) $request->query('tw', $defaultTw);
        if ($currentTw < 1 || $currentTw > 4) $currentTw = $defaultTw;

        $sheetName = "SUMMARY TW " . $this->toRoman($currentTw);

        // --- Range pembacaan data
        $startRow = 6;
        $maxRows = 50;
        $endRow = $startRow + $maxRows - 1;

        $rangeKode = "{$sheetName}!C{$startRow}:C{$endRow}";
        $rangeRealisasi = "{$sheetName}!Q{$startRow}:Q{$endRow}";
        $rangeSerapan = "{$sheetName}!T{$startRow}:T{$endRow}";

        // --- Ambil tiga kolom sekaligus
        $batch = $service->spreadsheets_values->batchGet(
            $this->spreadsheetId,
            ['ranges' => [$rangeKode, $rangeRealisasi, $rangeSerapan], 'valueRenderOption' => 'UNFORMATTED_VALUE']
        );

        $valueRanges = $batch->getValueRanges();
        $kodeValues = $valueRanges[0]->getValues() ?? [];
        $realisasiValues = $valueRanges[1]->getValues() ?? [];
        $serapanValues = $valueRanges[2]->getValues() ?? [];

        $getCell = fn($arr, $i) => isset($arr[$i][0]) ? $arr[$i][0] : '';

        // Cari index terakhir yang punya kode PP
        $lastIndex = -1;
        for ($i = 0; $i < $maxRows; $i++) {
            $kodeCell = trim((string)$getCell($kodeValues, $i));
            if ($kodeCell !== '') $lastIndex = $i;
        }

        $chartData = [];
        for ($i = 0; $i <= max(0, $lastIndex); $i++) {
            $kodePP = trim((string)$getCell($kodeValues, $i));
            if ($kodePP === '') continue;

            $rawSerapan = $getCell($serapanValues, $i);
            $rawRealisasi = $getCell($realisasiValues, $i);

            $serapan = $this->normalizePercent($rawSerapan);
            $realisasi = $this->normalizePercent($rawRealisasi);

            $chartData[] = [
                'kode_pp' => $kodePP,
                'serapan' => $serapan,
                'realisasi' => $realisasi
            ];
        }

        $labels = array_column($chartData, 'kode_pp');
        $dataSerapan = array_column($chartData, 'serapan');
        $dataRealisasi = array_column($chartData, 'realisasi');

        return view('main.dashboard', compact(
            'saldoTW1', 'saldoTW2', 'saldoTW3', 'saldoTW4',
            'labels', 'dataSerapan', 'dataRealisasi', 'currentTw'
        ));
    }

    /**
     * Normalisasi nilai persen agar tetap aman (0–100)
     */
    private function normalizePercent($raw)
    {
        $num = 0.0;
        if ($raw === '' || $raw === null) return 0.0;

        if (is_numeric($raw)) {
            $num = (float)$raw;
        } else {
            $s = trim((string)$raw);
            if (strpos($s, '%') !== false) {
                $s = str_replace('%', '', $s);
            }
            $s = str_replace(',', '.', $s);
            $s = preg_replace('/[^0-9.\-]/', '', $s);
            $num = ($s === '') ? 0.0 : (float)$s;
        }

        // Jika fraction (0–1) ubah ke persen
        if ($num > 0 && $num <= 1) $num *= 100.0;
        if ($num < 0) $num = 0.0;
        if ($num > 100) $num = 100.0;

        return round($num, 2);
    }

    private function toRoman($number)
    {
        $map = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
        return $map[$number] ?? 'I';
    }
}
