<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;

class DashboardController extends Controller
{
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
    }

    private function getGoogleSheetService()
    {
        $client = new Google_Client();
        $client->setApplicationName('MONITA - Dashboard');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new Google_Service_Sheets($client);
    }
    public function index()
{
    $service = $this->getGoogleSheetService();

    $saldoTW1 = $this->getCellValue($service, 'SUMMARY TW I!H39');
    $saldoTW2 = $this->getCellValue($service, 'SUMMARY TW II!H39');
    $saldoTW3 = $this->getCellValue($service, 'SUMMARY TW III!H39');
    $saldoTW4 = $this->getCellValue($service, 'SUMMARY TW IV!H39');

    return view('main.dashboard', compact('saldoTW1', 'saldoTW2', 'saldoTW3', 'saldoTW4'));
}

private function getCellValue($service, $range)
{
    $params = ['valueRenderOption' => 'UNFORMATTED_VALUE'];
    $response = $service->spreadsheets_values->get($this->spreadsheetId, $range, $params);

    $value = $response->getValues()[0][0] ?? 0;

    // Jika tetap string berformat ribuan, bersihkan
    if (!is_numeric($value)) {
        $value = preg_replace('/[^0-9.-]/', '', $value);
    }

    return (float) $value;
}



}
