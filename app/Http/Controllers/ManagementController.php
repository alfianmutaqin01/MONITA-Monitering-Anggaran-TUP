<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;

class ManagementController extends Controller
{
    protected $spreadsheetId = '';

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID'); // ambil dari .env
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Management Akun');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }

    public function index()
    {
        $service = $this->getGoogleSheetService();

        // ambil data Users!A2:F35
        $range = 'Users!A3:F35';
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
}
