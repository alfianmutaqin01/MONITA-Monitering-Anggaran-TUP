<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_ClearValuesRequest;
use Exception;

class ManagementController extends Controller
{
    protected $spreadsheetId;
    protected $sheetName = 'Users';
    protected $startRow = 3; // data mulai A3

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '');
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Management Akun');
        // SCOPE yang cukup untuk read/write/update/append/clear
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));
        return new Google_Service_Sheets($client);
    }

    /**
     * Helper: cari baris fisik berdasarkan kode_pp
     * return ['sheetRow' => int, 'row' => array] atau null
     */
    private function findRowByKode($service, $kode)
    {
        $range = "{$this->sheetName}!A{$this->startRow}:F";
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues() ?? [];

        foreach ($values as $i => $row) {
            // pastikan index 1 (kolom B) ada
            $cellKode = isset($row[1]) ? trim((string)$row[1]) : '';
            if (strcasecmp($cellKode, $kode) === 0) {
                return [
                    'sheetRow' => $this->startRow + $i,
                    'row' => $row
                ];
            }
        }
        return null;
    }

    /**
     * Reindex nomor urut di kolom A dan compact sheet (buang baris kosong)
     */
    private function reindexNumbers($service)
    {
        $range = "{$this->sheetName}!A{$this->startRow}:F";
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $rows = $response->getValues() ?? [];

        // Ambil hanya baris yang memiliki data (kolom B-F ada isinya)
        $nonEmpty = [];
        foreach ($rows as $row) {
            $row = array_pad($row, 6, '');
            $bToF = array_slice($row, 1, 5);
            if (count(array_filter($bToF)) > 0) {
                // simpan B-F, nomor akan ditambahkan kemudian
                $nonEmpty[] = [$row[1], $row[2], $row[3], $row[4], $row[5]];
            }
        }

        // Clear existing range supaya tidak ada sisa
        $clearRequest = new Google_Service_Sheets_ClearValuesRequest([]);
        $service->spreadsheets_values->clear($this->spreadsheetId, $range, $clearRequest);

        if (empty($nonEmpty)) {
            return;
        }

        // Build final rows with nomor di kolom A
        $final = [];
        $no = 1;
        foreach ($nonEmpty as $r) {
            // A,B,C,D,E,F => nomor + (B..F)
            $final[] = [$no, $r[0], $r[1], $r[2], $r[3], $r[4]];
            $no++;
        }

        $body = new Google_Service_Sheets_ValueRange(['values' => $final]);
        $service->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $body,
            ['valueInputOption' => 'USER_ENTERED']
        );
    }

    /**
     * Index -> list akun (ambil A3:F terakhir)
     */
    public function index()
    {
        $service = $this->getGoogleSheetService();
        $range = "{$this->sheetName}!A{$this->startRow}:F";
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues() ?? [];

        $users = [];
        foreach ($values as $i => $row) {
            $sheetRow = $this->startRow + $i;
            $row = array_pad($row, 6, '');
            // consider row non-empty if any of B-F has content
            if (count(array_filter(array_slice($row, 1, 5))) === 0) {
                // skip fully-empty rows
                continue;
            }

            $users[] = [
                'sheet_row' => $sheetRow,
                'no'        => $row[0] ?? '',
                'kode_pp'   => $row[1] ?? '',
                'nama_pp'   => $row[2] ?? '',
                'username'  => $row[3] ?? '',
                'password'  => $row[4] ?? '',
                'role'      => ucfirst(strtolower($row[5] ?? 'user')),
            ];
        }

        return view('main.management', compact('users'));
    }

    /**
     * Store -> tambah akun (append)
     */
    public function store(Request $request)
{
    $request->validate([
        'kode_pp'  => 'required|string|max:10',
        'nama_pp'  => 'required|string|max:150',
        'username' => 'required|string|max:50',
        'password' => 'required|string|max:50',
        'role'     => 'required|in:admin,user',
    ]);

    $service = $this->getGoogleSheetService();

    // --- CEK KODE DUPLIKAT (lihat poin 3 di bawah)
    $range = "{$this->sheetName}!A{$this->startRow}:F";
    $res = $service->spreadsheets_values->get($this->spreadsheetId, $range);
    $rows = $res->getValues() ?? [];
    foreach ($rows as $row) {
        if (strcasecmp(trim($row[1] ?? ''), trim($request->kode_pp)) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kode PP sudah terpakai. Gunakan kode lain.'
            ], 422);
        }
    }

    // hitung nomor baru
    $nonEmpty = 0;
    foreach ($rows as $r) {
        if (count(array_filter(array_slice($r, 1, 5))) > 0) $nonEmpty++;
    }
    $newNo = $nonEmpty + 1;

    $body = new \Google_Service_Sheets_ValueRange([
        'values' => [[
            $newNo,
            $request->kode_pp,
            $request->nama_pp,
            $request->username,
            $request->password,
            $request->role
        ]]
    ]);

    $service->spreadsheets_values->append(
        $this->spreadsheetId,
        "{$this->sheetName}!A:F",
        $body,
        [
            'valueInputOption' => 'USER_ENTERED',
            'insertDataOption' => 'INSERT_ROWS'
        ]
    );

    $this->reindexNumbers($service);

    return response()->json(['success' => true, 'message' => 'Akun baru berhasil ditambahkan.']);
}


    /**
     * Show detail berdasarkan kode_pp (lebih aman daripada row index)
     */
    public function show($kode)
    {
        $service = $this->getGoogleSheetService();
        $found = $this->findRowByKode($service, $kode);

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $row = array_pad($found['row'], 6, '');
        $akun = [
            'sheet_row' => $found['sheetRow'],
            'no'        => $row[0] ?? '',
            'kode_pp'   => $row[1] ?? '',
            'nama_pp'   => $row[2] ?? '',
            'username'  => $row[3] ?? '',
            'password'  => $row[4] ?? '',
            'role'      => $row[5] ?? 'user',
        ];

        return response()->json(['success' => true, 'akun' => $akun]);
    }

    /**
     * Update berdasarkan kode_pp
     */
    public function update(Request $request, $kode)
    {
        $request->validate([
            'kode_pp'  => 'required|string|max:10',
            'nama_pp'  => 'required|string|max:150',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:50',
            'role'     => 'required|in:admin,user',
        ]);

        $service = $this->getGoogleSheetService();
        $found = $this->findRowByKode($service, $kode);

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $sheetRow = $found['sheetRow'];

        // update B..F pada baris tersebut (biarkan kolom A nomor tetap)
        $range = "{$this->sheetName}!B{$sheetRow}:F{$sheetRow}";
        $body = new Google_Service_Sheets_ValueRange([
            'values' => [[
                $request->kode_pp,
                $request->nama_pp,
                $request->username,
                $request->password,
                $request->role
            ]]
        ]);

        $service->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $body,
            ['valueInputOption' => 'USER_ENTERED']
        );

        // Setelah update, reindex untuk memperbaiki nomor urut jika diperlukan
        $this->reindexNumbers($service);

        return response()->json(['success' => true]);
    }

    /**
     * Destroy berdasarkan kode_pp (kosongkan kemudian compact & reindex)
     */
    public function destroy($kode)
    {
        $service = $this->getGoogleSheetService();
        $found = $this->findRowByKode($service, $kode);

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $sheetRow = $found['sheetRow'];

        // Kosongkan baris (A..F)
        $emptyBody = new Google_Service_Sheets_ValueRange(['values' => [['', '', '', '', '', '']]]);
        $service->spreadsheets_values->update(
            $this->spreadsheetId,
            "{$this->sheetName}!A{$sheetRow}:F{$sheetRow}",
            $emptyBody,
            ['valueInputOption' => 'USER_ENTERED']
        );

        // Compact (hapus baris kosong & renumber)
        $this->reindexNumbers($service);

        return response()->json(['success' => true]);
    }
}
