<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Exception;

class AuthController extends Controller
{
    protected $spreadsheetId;
    protected $range = 'users!A2:F'; 

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SPREADSHEET_ID', '');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Inisialisasi Google Sheets service menggunakan service account JSON
     */
    private function getGoogleSheetService()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Monita System');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(storage_path('app/credentials/service-account.json'));

        return new \Google_Service_Sheets($client);
    }

    /**
     * Load semua user dari sheet Users
     * return array of users
     */
    private function loadUsers()
{
    $service = $this->getGoogleSheetService();

    $response = $service->spreadsheets_values->get(
    $this->spreadsheetId,
    $this->range
);

    $values = $response->getValues();

    $users = [];
    if (empty($values)) {
        return $users;
    }

    foreach ($values as $row) {
        $users[] = [
            'no'       => $row[0] ?? '',
            'kode_pp'  => $row[1] ?? '',
            'nama_pp'  => $row[2] ?? '',
            'username' => $row[3] ?? '',
            'password' => $row[4] ?? '',
            'role'     => strtolower($row[5] ?? 'user'),
        ];
    }

    return $users;
}


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            $users = $this->loadUsers();
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'login' => 'Gagal terhubung ke Google Sheets. Pesan: ' . $e->getMessage()
            ])->withInput();
        }

        foreach ($users as $user) {
            if (
                isset($user['username']) &&
                isset($user['password']) &&
                $user['username'] === $request->username &&
                $user['password'] === $request->password
            ) {
                // Login berhasil -> simpan di session
                Session::put('user_authenticated', true);
                Session::put('user_data', $user);
                Session::put('user_role', $user['role']);


                return redirect()->route('dashboard');
            }
        }

        return redirect()->back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
    }

    public function logout()
    {
        Session::forget('user_authenticated');
        Session::forget('user_data');
        return redirect()->route('login');
    }

    public function dashboard()
{
    if (!Session::get('user_authenticated')) {
        return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
    }

    // Ambil data user dari session
    $user = Session::get('user_data');

    return view('main.dashboard', compact('user'));
}

}
