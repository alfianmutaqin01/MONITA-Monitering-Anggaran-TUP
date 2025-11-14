<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Carbon\Carbon;
use Exception;

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

    private function getCellValue($service, $range)
    {
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range, [
            'valueRenderOption' => 'UNFORMATTED_VALUE'
        ]);
        $value = $response->getValues()[0][0] ?? 0;
        if (!is_numeric($value)) $value = preg_replace('/[^0-9.\-]/', '', (string)$value);
        return (float) $value;
    }

    /**
     * Helper untuk mengkonversi nilai mentah menjadi persentase float (0-100).
     */
    protected function normalizePercent($raw)
    {
        if ($raw === '' || $raw === null) return 0.0;

        $num = 0.0;
        if (is_numeric($raw)) {
            $num = (float)$raw;
        } else {
            $s = str_replace(['%', ',', ' '], ['', '.', ''], (string)$raw);
            $num = (float)preg_replace('/[^0-9.\-]/', '', $s);
        }

        // Jika nilai kecil (misalnya 0.8), konversikan ke 80%
        if ($num > 0 && $num <= 2) $num *= 100.0;

        return round($num, 2);
    }

    /**
     * Helper untuk konversi angka ke Romawi.
     */
    protected function toRoman($number)
    {
        return [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'][$number] ?? 'I';
    }


    public function index(Request $request)
{
    try {
        $service = $this->getGoogleSheetService();

        $saldoTW1 = $this->getCellValue($service, 'SUMMARY TW I!H39');
        $saldoTW2 = $this->getCellValue($service, 'SUMMARY TW II!H39');
        $saldoTW3 = $this->getCellValue($service, 'SUMMARY TW III!H39');
        $saldoTW4 = $this->getCellValue($service, 'SUMMARY TW IV!H39');

        $month = Carbon::now()->month;
        $defaultTw = match (true) {
            $month >= 1 && $month <= 3 => 1,
            $month >= 4 && $month <= 6 => 2,
            $month >= 7 && $month <= 9 => 3,
            default => 4,
        };

        $currentTw = (int) $request->query('tw', 0);

        if ($currentTw === 0) {
            return redirect()->route('dashboard', ['tw' => $defaultTw]);
        }
        
        $currentTw = max(1, min(4, $currentTw));


        $sheetName = "SUMMARY TW " . $this->toRoman($currentTw);

        $startRow = 6;
        $endRow = 55;
        $ranges = [
            "{$sheetName}!C{$startRow}:C{$endRow}",  // Kode PP
            "{$sheetName}!T{$startRow}:T{$endRow}",  // Serapan All
            "{$sheetName}!M{$startRow}:M{$endRow}",  // RKA Operasi
            "{$sheetName}!Q{$startRow}:Q{$endRow}",  // Real Operasional
        ];

        // Ambil batch data
        $batch = $service->spreadsheets_values->batchGet($this->spreadsheetId, [
            'ranges' => $ranges,
            'valueRenderOption' => 'UNFORMATTED_VALUE'
        ]);

        $kodeValues = $batch->getValueRanges()[0]->getValues() ?? [];
        $serapanValues = $batch->getValueRanges()[1]->getValues() ?? [];
        $rkaValues = $batch->getValueRanges()[2]->getValues() ?? [];
        $operasionalValues = $batch->getValueRanges()[3]->getValues() ?? [];

        if (empty($kodeValues)) {
            return redirect()
                ->route('settings.index')
                ->with('warning', 'Data Kode PP di Google Sheet saat ini kosong. Mohon lengkapi dahulu sebelum membuka dashboard.');
        }

        $get = fn($arr, $i) => isset($arr[$i][0]) ? $arr[$i][0] : '';

        $chartData = [];
        foreach ($kodeValues as $i => $kodeRow) {
            $kode = trim((string)($kodeRow[0] ?? ''));
            if ($kode === '') continue;

            $chartData[] = [
                'kode_pp' => $kode,
                'serapan' => $this->normalizePercent($get($serapanValues, $i)),
                'rka' => $this->normalizePercent($get($rkaValues, $i)),
                'operasional' => $this->normalizePercent($get($operasionalValues, $i)),
            ];
        }

        return view('main.dashboard', [
            'saldoTW1' => $saldoTW1,
            'saldoTW2' => $saldoTW2,
            'saldoTW3' => $saldoTW3,
            'saldoTW4' => $saldoTW4,
            'labels' => array_column($chartData, 'kode_pp'),
            'dataSerapan' => array_column($chartData, 'serapan'),
            'dataRka' => array_column($chartData, 'rka'),
            'dataOperasional' => array_column($chartData, 'operasional'),
            'currentTw' => $currentTw
        ]);
    } catch (\Google\Service\Exception $e) {
        //Tangkap error dari Google API
        return redirect()
            ->route('settings.index')
            ->with('error', 'Terjadi kesalahan saat mengambil data dari Google Sheets. 
                Pastikan file spreadsheet tahun aktif sudah lengkap untuk sheet: ' . $this->spreadsheetId);
    } catch (\Exception $e) {
        //Tangkap error umum
        return redirect()
            ->route('settings.index')
            ->with('error', 'Dashboard gagal dimuat: ' . $e->getMessage());
    }
}


}
