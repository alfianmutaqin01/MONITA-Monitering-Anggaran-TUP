<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Exception;
use App\Traits\FormatDataTrait; 

class LaporanController extends Controller
{
    use FormatDataTrait; // Implementasi Trait

    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Laporan');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json')); 
        return new Google_Service_Sheets($client);
    }

    /**
     * Menampilkan data Laporan (RAW Data) berdasarkan Triwulan, Unit, dan Tipe.
     */
    public function index(Request $request, $tw = 1)
    {
        $tw = max(1, min(4, (int)$tw));
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
            $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues() ?? [];

        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Unable to parse range') ||
                str_contains($e->getMessage(), 'Requested entity was not found')) {
                return redirect()
                    ->route('settings.index')
                    ->with('warning', "Tab {$sheetName} tidak ditemukan pada spreadsheet aktif. Pastikan file Google Sheet memiliki tab dengan nama tersebut.");
            }
            return redirect()
                ->route('settings.index')
                ->with('error', 'Gagal memuat data laporan: ' . $e->getMessage());
        }

        // Jika tab ditemukan tapi kosong
        if (empty($values)) {
            return redirect()
                ->route('settings.index')
                ->with('warning', "Data pada tab <strong>{$sheetName}</strong> tidak ditemukan atau kosong. Silakan periksa kelengkapan data Google Sheet Anda.");
        }

        $data = [];
        $no = 1;
        foreach ($values as $row) {
            $row = array_pad($row, 10, '');
            if (count(array_filter($row)) === 0) continue;
            
            // parseNumber dari Trait
            $data[] = [
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
            ];
        }

        $units = $this->getUnitList($service); 

        $filterUnit = $request->query('unit');
        $filterType = $request->query('type', 'all');

        if ($filterUnit) {
            $data = array_values(array_filter($data, fn($d) => strcasecmp(trim($d['unit']), trim($filterUnit)) === 0));
        }

        if ($filterType !== 'all') {
            $data = array_values(array_filter($data, function ($d) use ($filterType) {
                $type = strtoupper($d['tipe'] ?? '');
                return match ($filterType) { 
                    'operasional' => str_contains($type, 'OPER'),
                    'remun'       => str_contains($type, 'REMUN'),
                    'bang'        => str_contains($type, 'BANG'),
                    'ntf'         => str_contains($type, 'NTF'),
                    default       => true,
                };
            }));
        }

        foreach ($data as $i => &$row) $row['no'] = $i + 1;
        
        $viewName = "main.laporan.tw{$tw}";
        return view($viewName, compact('data', 'tw', 'units', 'filterUnit', 'filterType'));
    }
    
    private function getUnitList($service)
    {
        try {
            $unitResp = $service->spreadsheets_values->get($this->spreadsheetId, 'Users!B3:B100');
            $unitVals = $unitResp->getValues() ?? [];
            $units = [];
            foreach ($unitVals as $r) {
                if (!empty(trim((string)($r[0] ?? '')))) {
                    $units[] = trim((string)$r[0]);
                }
            }
            return array_values(array_unique($units));
        } catch (Exception $e) {
            return [];
        }
    }
}
