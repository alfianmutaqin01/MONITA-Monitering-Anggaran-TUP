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

        // Jika string berformat (contoh "1.695.062.027" atau "1.695.062.027,00"), buang non-digit
        if (!is_numeric($value)) {
            $value = preg_replace('/[^0-9\.\-]/', '', (string)$value);
        }

        return (float) $value;
    }

    /**
     * Dashboard utama -> kartu saldo + chart Serapan %
     */
    public function index(Request $request)
    {
        $service = $this->getGoogleSheetService();

        // Ambil saldo summary untuk 4 triwulan (H39)
        $saldoTW1 = $this->getCellValue($service, 'SUMMARY TW I!H39');
        $saldoTW2 = $this->getCellValue($service, 'SUMMARY TW II!H39');
        $saldoTW3 = $this->getCellValue($service, 'SUMMARY TW III!H39');
        $saldoTW4 = $this->getCellValue($service, 'SUMMARY TW IV!H39');

        // Triwulan default = triwulan saat ini, atau dari query param ?tw=
        $month = Carbon::now()->month;
        if ($month <= 3) $defaultTw = 1;
        elseif ($month <= 6) $defaultTw = 2;
        elseif ($month <= 9) $defaultTw = 3;
        else $defaultTw = 4;

        $currentTw = (int) $request->query('tw', $defaultTw);
        if ($currentTw < 1 || $currentTw > 4) $currentTw = $defaultTw;
        $sheetName = "SUMMARY TW " . $this->toRoman($currentTw);

        // Batas aman: C6..C{end} dan T6..T{end}
        $startRow = 6;
        $maxRows  = 50; // ubah jika diperlukan
        $endRow   = $startRow + $maxRows - 1;

        $rangeKode    = "{$sheetName}!C{$startRow}:C{$endRow}";
        $rangeSerapan = "{$sheetName}!T{$startRow}:T{$endRow}";

        // Ambil dua range sekaligus (batchGet)
        $batch = $service->spreadsheets_values->batchGet(
            $this->spreadsheetId,
            ['ranges' => [$rangeKode, $rangeSerapan], 'valueRenderOption' => 'UNFORMATTED_VALUE']
        );

        $valueRanges = $batch->getValueRanges();
        $kodeValues  = isset($valueRanges[0]) ? $valueRanges[0]->getValues() : [];
        $serapValues = isset($valueRanges[1]) ? $valueRanges[1]->getValues() : [];

        // helper aman baca cell
        $getCell = function($arr, $i) {
            return (isset($arr[$i]) && isset($arr[$i][0])) ? $arr[$i][0] : '';
        };

        // cari index terakhir yang memiliki kode_pp (kolom C)
        $lastIndex = -1;
        for ($i = 0; $i < $maxRows; $i++) {
            $kodeCell = trim((string)$getCell($kodeValues, $i));
            if ($kodeCell !== '') $lastIndex = $i;
        }

        $chartData = [];
        for ($i = 0; $i <= max(0, $lastIndex); $i++) {
            $kodeCell = trim((string)$getCell($kodeValues, $i));
            if ($kodeCell === '') continue;

            $raw = $getCell($serapValues, $i);

            // Normalisasi robust:
            // - bisa jadi angka (0.666666), atau string "66,67%" atau "66.67" atau "66,67"
            $num = 0.0;
            if ($raw === '' || $raw === null) {
                $num = 0.0;
            } elseif (is_numeric($raw)) {
                // numeric: bisa 0.6667 (fraction) atau 66.67 (percent)
                $num = (float)$raw;
            } else {
                $s = trim((string)$raw);
                // jika ada persen sign
                if (strpos($s, '%') !== false) {
                    $s = str_replace('%', '', $s);
                    $s = str_replace(',', '.', $s);
                    $s = preg_replace('/[^0-9.\-]/', '', $s);
                    $num = ($s === '') ? 0.0 : (float)$s;
                } else {
                    // ubah koma desimal ke titik, bersihkan karakter lain
                    $s = str_replace(',', '.', $s);
                    $s = preg_replace('/[^0-9.\-]/', '', $s);
                    $num = ($s === '') ? 0.0 : (float)$s;
                }
            }

            // Jika angka berada di rentang (0,1] berarti Google mengembalikan fraction -> konversi ke persen
            if ($num > 0 && $num <= 1) {
                $num = $num * 100.0;
            }

            // Pastikan 0..100
            if (!is_finite($num) || $num < 0) $num = 0.0;
            if ($num > 100) $num = min(100.0, $num); // clamp ke 100 jika ekstrim

            $chartData[] = [
                'kode_pp' => $kodeCell,
                'serapan' => round($num, 2)
            ];
        }

        $labels = array_column($chartData, 'kode_pp');
        $data   = array_column($chartData, 'serapan');

        return view('main.dashboard', compact(
            'saldoTW1', 'saldoTW2', 'saldoTW3', 'saldoTW4',
            'labels', 'data', 'currentTw'
        ));
    }

    private function toRoman($number)
    {
        $map = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
        return $map[$number] ?? 'I';
    }
}
