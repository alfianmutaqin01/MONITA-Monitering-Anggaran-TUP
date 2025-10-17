<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Exception;

class SummaryController extends Controller
{
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

    /** 💡 Parse angka dari cell */
    private function parseNumber($v): float
    {
        if (!$v) return 0;
        $v = trim((string)$v);
        $negative = false;

        // format (1234)
        if (preg_match('/^\((.*)\)$/', $v, $m)) {
            $negative = true;
            $v = $m[1];
        }

        $v = str_replace(['.', ','], ['', '.'], $v);
        $num = is_numeric($v) ? (float)$v : 0;
        return $negative ? -$num : $num;
    }

    /** 📄 Tampilkan summary TW */
    public function index($tw)
    {
        $tw = max(1, min(4, (int)$tw)); // clamp 1–4

        $sheetName = match ($tw) {
            1 => 'SUMMARY TW I',
            2 => 'SUMMARY TW II',
            3 => 'SUMMARY TW III',
            4 => 'SUMMARY TW IV',
        };

        $range = "{$sheetName}!C6:T39";

        try {
            $values = $this->getGoogleSheetService()
                ->spreadsheets_values
                ->get($this->spreadsheetId, $range)
                ->getValues() ?? [];
        } catch (Exception $e) {
            return back()->withErrors(['gsheet' => 'Gagal memuat data summary: ' . $e->getMessage()]);
        }

        $data = [];
        foreach ($values as $i => $r) {
            if (empty(array_filter($r))) continue;
            $r = array_pad($r, 22, '');

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
    }
}
