<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Exception;
use App\Traits\FormatDataTrait; 

class SummaryController extends Controller
{
    use FormatDataTrait; 

    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    /** 🔧 Inisialisasi Google Sheets client */
    private function getGoogleSheetService(): Google_Service_Sheets
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Summary Anggaran');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }

    public function index($tw)
{
    $tw = max(1, min(4, (int)$tw)); // clamp 1–4

    // Menggunakan toRoman dari Trait
    $sheetName = 'SUMMARY TW ' . $this->toRoman($tw);

    $range = "{$sheetName}!C6:T39";

    try {
        $service = $this->getGoogleSheetService();

        // Cek apakah tab sheet tersedia di Google Sheets
        $sheets = $service->spreadsheets->get($this->spreadsheetId)->getSheets();
        $sheetNames = collect($sheets)->pluck('properties.title')->toArray();

        if (!in_array($sheetName, $sheetNames)) {
            return redirect()
                ->route('settings.index')
                ->with('warning', "Spreadsheet tahun ini belum memiliki tab '{$sheetName}'. Silakan tambahkan tab tersebut terlebih dahulu di Google Sheets.");
        }

        // Ambil data summary
        $values = $service->spreadsheets_values
            ->get($this->spreadsheetId, $range)
            ->getValues() ?? [];

        // Jika sheet ditemukan tapi datanya kosong → arahkan ke settings
        if (empty($values) || count(array_filter($values, fn($r) => !empty(array_filter($r)))) === 0) {
            return redirect()
                ->route('settings.index')
                ->with('warning', "Data summary untuk '{$sheetName}' belum tersedia atau masih kosong. Harap lengkapi data di Google Sheets terlebih dahulu.");
        }

        // Parsing data
        $data = [];
        foreach ($values as $i => $r) {
            if (empty(array_filter($r))) continue;
            $r = array_pad($r, 22, '');

            // Menggunakan parseNumber dari Trait
            $data[] = [
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
            ];
        }

        return view("main.summary.tw{$tw}", compact('data', 'tw'));
    } catch (Exception $e) {
        return redirect()
            ->route('settings.index')
            ->with('error', 'Tidak dapat mengakses data Google Sheet. Periksa koneksi atau kredensial service account.');
    }
}
}
