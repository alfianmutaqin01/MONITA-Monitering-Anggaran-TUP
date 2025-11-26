<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Exception;
use App\Traits\FormatDataTrait; 
use App\Services\GoogleSheetService;

class LaporanController extends Controller
{
    use FormatDataTrait; // Implementasi Trait

    protected $googleSheetService;

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    /**
     * Menampilkan data Laporan (RAW Data) berdasarkan Triwulan, Unit, dan Tipe.
     */
    public function index(Request $request, $tw = 1)
    {
        $tw = max(1, min(4, (int)$tw));
        try {
            $data = $this->googleSheetService->getLaporan($tw);
        } catch (Exception $e) {
             if (str_contains($e->getMessage(), 'Unable to parse range') ||
                str_contains($e->getMessage(), 'Requested entity was not found')) {
                return redirect()
                    ->route('settings.index')
                    ->with('warning', "Tab untuk TW {$tw} tidak ditemukan pada spreadsheet aktif.");
            }
            return redirect()
                ->route('settings.index')
                ->with('error', 'Gagal memuat data laporan: ' . $e->getMessage());
        }

        if (empty($data)) {
            return redirect()
                ->route('settings.index')
                ->with('warning', "Data pada tab TW {$tw} tidak ditemukan atau kosong.");
        }

        // Ambil list unit dari service (menggunakan data yang sudah ada di service)
        // Kita bisa menggunakan getUnitsFromSheet() yang sudah ada di service
        // Namun getUnitsFromSheet mengembalikan array key-value [code => name]
        // Sedangkan LaporanController sebelumnya mengambil list code saja.
        // Kita pakai array_keys dari getUnitsFromSheet()
        $unitsMap = $this->googleSheetService->getUnitsFromSheet();
        $units = array_keys($unitsMap);

        $filterUnit = $request->query('unit');
        $filterType = $request->query('type', 'all');

        // Filtering pada Collection of Objects
        if ($filterUnit) {
            $data = array_values(array_filter($data, fn($d) => strcasecmp(trim($d->unit), trim($filterUnit)) === 0));
        }

        if ($filterType !== 'all') {
            $data = array_values(array_filter($data, function ($d) use ($filterType) {
                $type = strtoupper($d->tipe ?? '');
                return match ($filterType) { 
                    'operasional' => str_contains($type, 'OPER'),
                    'remun'       => str_contains($type, 'REMUN'),
                    'bang'        => str_contains($type, 'BANG'),
                    'ntf'         => str_contains($type, 'NTF'),
                    default       => true,
                };
            }));
        }

        // Re-index number
        foreach ($data as $i => $row) {
            $row->no = $i + 1;
        }
        
        $viewName = "main.laporan.tw{$tw}";
        return view($viewName, compact('data', 'tw', 'units', 'filterUnit', 'filterType'));
    }
}
