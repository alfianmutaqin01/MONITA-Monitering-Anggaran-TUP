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

    /**
     * Inisialisasi Google Sheets client
     */
    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Summary Anggaran');
        // READONLY sudah cukup untuk menampilkan summary
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }

    /**
     * Parse angka dari cell (meng-handle titik ribuan, koma desimal, dan tanda kurung untuk negatif)
     */
    private function parseNumber($v)
    {
        if ($v === null || $v === '') return 0;
        $v = trim((string) $v);

        // jika format (1.234) => negatif
        $negative = false;
        if (preg_match('/^\((.*)\)$/', $v, $m)) {
            $negative = true;
            $v = $m[1];
        }

        // bersihkan karakter selain digit, koma dan titik dan minus
        $clean = preg_replace('/[^0-9\.\,-]/', '', $v);
        // beberapa cell menggunakan koma sebagai pemisah desimal, tapi di data kita umumnya menggunakan titik sebagai ribuan
        // langkah: hapus titik (ribuan), ubah koma ke titik jika ada
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);

        if ($clean === '' || !is_numeric($clean)) return 0;

        $num = (float) $clean;
        return $negative ? -$num : $num;
    }

    /**
     * index: muat data untuk triwulan {1..4}
     */
    public function index($tw)
    {
        $tw = intval($tw);
        if ($tw < 1 || $tw > 4) $tw = 1;

        // Pastikan nama sheet sama persis dengan tab di spreadsheet Anda
        $sheetNames = [
            1 => 'SUMMARY TW I',
            2 => 'SUMMARY TW II',
            3 => 'SUMMARY TW III',
            4 => 'SUMMARY TW IV',
        ];
        $sheetName = $sheetNames[$tw] ?? $sheetNames[1];

        // Range sesuai instruksi: C6 sampai T39
        $range = "{$sheetName}!C6:T39";

        try {
            $service = $this->getGoogleSheetService();
            $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues() ?? [];
        } catch (Exception $e) {
            // Kembalikan pesan error yang jelas agar mudah ditangani
            return back()->withErrors(['gsheet' => 'Gagal memuat data summary: ' . $e->getMessage()]);
        }

        $data = [];
        $no = 1;

        foreach ($values as $row) {
            // jika seluruh sel baris kosong, skip
            if (empty(array_filter($row))) continue;

            // pastikan ada 22 elemen (pad kalau kurang)
            $row = array_pad($row, 22, '');

            $data[] = [
                'no'            => $no++,
                'kode_pp'       => $row[0] ?? '',
                'nama_pp'       => $row[1] ?? '',
                'bidang'        => $row[2] ?? '',
                'anggaran_tw'   => $this->parseNumber($row[3] ?? ''),
                'realisasi_tw'  => $this->parseNumber($row[4] ?? ''),
                'saldo_tw'      => $this->parseNumber($row[5] ?? ''),
                'serapan_all'   => trim($row[6] ?? ''),
                'rka_operasi'   => $this->parseNumber($row[7] ?? ''),
                'real_operasi'  => $this->parseNumber($row[8] ?? ''),
                'saldo_operasi' => $this->parseNumber($row[9] ?? ''),
                'serapan_oper'  => trim($row[10] ?? ''),
                'rka_bang'      => $this->parseNumber($row[11] ?? ''),
                'real_bang'     => $this->parseNumber($row[12] ?? ''),
                'sisa_bang'     => $this->parseNumber($row[13] ?? ''),
                'serapan_bang'  => trim($row[14] ?? ''),
                'rkm_operasi'   => $this->parseNumber($row[15] ?? ''),
                'real_rkm'      => $this->parseNumber($row[16] ?? ''),
                'persen_rkm'    => trim($row[17] ?? ''),
            ];
        }

        $viewName = "main.summary.tw{$tw}";
        if (!view()->exists($viewName)) {
            $viewName = 'main.summary';
        }

        return view($viewName, compact('data', 'tw'));
    }
}
