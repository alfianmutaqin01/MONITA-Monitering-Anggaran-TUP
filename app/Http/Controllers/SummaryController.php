<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Exception;
use App\Traits\FormatDataTrait; 
use App\Services\GoogleSheetService; 

class SummaryController extends Controller
{
    use FormatDataTrait; 

    protected $googleSheetService;

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function index($tw)
    {
        try {
            $data = $this->googleSheetService->getSummary($tw);
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'tidak ditemukan')) {
                return redirect()
                    ->route('settings.index')
                    ->with('warning', $e->getMessage());
            }
            return redirect()
                ->route('settings.index')
                ->with('error', 'Tidak dapat mengakses data Google Sheet. Periksa koneksi atau kredensial service account.');
        }

        if (empty($data)) {
            return redirect()
                ->route('settings.index')
                ->with('warning', "Data summary belum tersedia atau masih kosong.");
        }

        return view("main.summary.tw{$tw}", compact('data', 'tw'));
    }
}
