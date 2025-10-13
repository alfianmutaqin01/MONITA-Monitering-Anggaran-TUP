<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_ClearValuesRequest;

class ManagementController extends Controller
{
    protected $spreadsheetId;
    protected $sheetName = 'Users';
    protected $startRow = 3; // Data mulai A3
    private $service = null; // cache instance Google Service

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '');
    }

    /**
     * Optimized Google Sheets service initialization (cached instance)
     */
    private function getGoogleSheetService()
    {
        if ($this->service) {
            return $this->service;
        }

        $client = new Google_Client();
        $client->setApplicationName('MONITA - Management Akun');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return $this->service = new Google_Service_Sheets($client);
    }

    /**
     * Helper: Ambil semua data user dari Google Sheet (cached)
     */
    private function getCachedUsers($service)
    {
        return Cache::remember('sheet_users_data', 60, function () use ($service) {
            $range = "{$this->sheetName}!A{$this->startRow}:F";
            $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
            return $response->getValues() ?? [];
        });
    }

    /**
     * Helper: Cari baris berdasarkan kode_pp
     */
    private function findRowByKode($service, $kode)
    {
        $values = $this->getCachedUsers($service);
        foreach ($values as $i => $row) {
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
     * Helper: Reindex nomor urut kolom A (optimasi, hanya bila perlu)
     */
    private function reindexNumbers($service)
    {
        $range = "{$this->sheetName}!A{$this->startRow}:F";
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $rows = $response->getValues() ?? [];

        $nonEmpty = [];
        foreach ($rows as $row) {
            $row = array_pad($row, 6, '');
            if (count(array_filter(array_slice($row, 1, 5))) > 0) {
                $nonEmpty[] = [$row[1], $row[2], $row[3], $row[4], $row[5]];
            }
        }

        // Kosongkan area lama
        $clear = new Google_Service_Sheets_ClearValuesRequest([]);
        $service->spreadsheets_values->clear($this->spreadsheetId, $range, $clear);

        if (empty($nonEmpty)) {
            Cache::forget('sheet_users_data');
            return;
        }

        $final = [];
        $no = 1;
        foreach ($nonEmpty as $r) {
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

        Cache::forget('sheet_users_data'); // refresh cache
    }

    /**
     * Menampilkan daftar akun
     */
    public function index()
    {
        $service = $this->getGoogleSheetService();
        $values = $this->getCachedUsers($service);

        $users = [];
        foreach ($values as $i => $row) {
            $row = array_pad($row, 6, '');
            if (count(array_filter(array_slice($row, 1, 5))) === 0) continue;

            $users[] = [
                'sheet_row' => $this->startRow + $i,
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
     * Menambahkan akun baru
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
        $rows = $this->getCachedUsers($service);

        foreach ($rows as $row) {
            if (strcasecmp(trim($row[1] ?? ''), trim($request->kode_pp)) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode PP sudah terpakai. Gunakan kode lain.'
                ], 422);
            }
        }

        $nonEmpty = collect($rows)->filter(fn($r) =>
            count(array_filter(array_slice(array_pad($r, 6, ''), 1, 5))) > 0
        )->count();
        $newNo = $nonEmpty + 1;

        $body = new Google_Service_Sheets_ValueRange([
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
            ['valueInputOption' => 'USER_ENTERED', 'insertDataOption' => 'INSERT_ROWS']
        );

        $this->reindexNumbers($service);

        return response()->json(['success' => true, 'message' => 'Akun baru berhasil ditambahkan.']);
    }

    /**
     * Menampilkan detail akun (View/Edit)
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
     * Update akun (optimasi: tidak reindex kecuali kode berubah)
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

        // reindex hanya bila kode_pp berubah
        if ($request->kode_pp !== $kode) {
            $this->reindexNumbers($service);
        } else {
            Cache::forget('sheet_users_data');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Hapus akun (dengan reindex otomatis)
     */
    public function destroy($kode)
    {
        $service = $this->getGoogleSheetService();
        $found = $this->findRowByKode($service, $kode);

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $sheetRow = $found['sheetRow'];
        $empty = new Google_Service_Sheets_ValueRange(['values' => [['', '', '', '', '', '']]]);
        $service->spreadsheets_values->update(
            $this->spreadsheetId,
            "{$this->sheetName}!A{$sheetRow}:F{$sheetRow}",
            $empty,
            ['valueInputOption' => 'USER_ENTERED']
        );

        $this->reindexNumbers($service);
        return response()->json(['success' => true]);
    }
}
