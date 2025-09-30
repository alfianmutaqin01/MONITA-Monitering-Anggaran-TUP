<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;

class ManagementController extends Controller
{
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Management Akun');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);   // pakai full scopes
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }

    public function index()
    {
        $service = $this->getGoogleSheetService();

        $range = 'Users!A3:F';                     // ambil semua dari baris 3 ke bawah
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();

        $users = [];
        foreach ($values as $row) {
            if (!empty($row[0])) {
                $users[] = [
                    'no'       => $row[0] ?? '',
                    'kode_pp'  => $row[1] ?? '',
                    'nama_pp'  => $row[2] ?? '',
                    'username' => $row[3] ?? '',
                    'password' => $row[4] ?? '',
                    'role'     => ucfirst(strtolower($row[5] ?? 'user')),
                ];
            }
        }

        return view('main.management', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pp'  => 'required|string|max:10',
            'nama_pp'  => 'required|string|max:100',
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:50',
            'role'     => 'required|in:admin,user',
        ]);

        $service = $this->getGoogleSheetService();

        // hitung no urut berdasarkan jumlah data
        $response = $service->spreadsheets_values->get($this->spreadsheetId, 'Users!A3:F');
        $values = $response->getValues();
        $newNo = count($values) + 1;      // next number

        // append langsung ke sheet Users
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
            'Users',                               // biarkan append otomatis di baris terakhir
            $body,
            ['valueInputOption' => 'USER_ENTERED']
        );

        // balikan JSON untuk AJAX
        return response()->json([
            'success'  => true,
            'no'       => $newNo,
            'kode'     => $request->kode_pp,
            'nama'     => $request->nama_pp,
            'username' => $request->username,
            'password' => $request->password,
            'role'     => ucfirst(strtolower($request->role)),
        ]);
    }
}
