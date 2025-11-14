<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Services\GoogleSheetService;

class AuthController extends Controller
{
    protected $googleSheetService;

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Load semua user dari sheet Users menggunakan service
     */
    private function loadUsers()
    {
        return $this->googleSheetService->getUsersFromSheet();
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

        $inputUsername = trim($request->username);
        foreach ($users as $user) {
            if (isset($user['username']) && trim($user['username']) === $inputUsername) {
                
                $inputPassword = $request->password;
                $storedPassword = $user['password'];
                $isHashed = Hash::info($storedPassword)['algoName'] !== 'unknown';
                
                $loggedIn = false;

                // HASHED
                if ($isHashed) {
                    $passwordWithSalt = $inputPassword . $inputUsername;
                    if (Hash::check($passwordWithSalt, $storedPassword)) {
                        $loggedIn = true;
                    }
                }
                
                // PLAIN TEXT 
                if (!$isHashed && $storedPassword === $inputPassword) {
                    $loggedIn = true;
                }
            
                if ($loggedIn) {
                    Session::put('user_authenticated', true);
                    Session::put('user_data', $user);
                    Session::put('user_role', $user['role']);
                    
                    $this->googleSheetService->clearUnitsCache();
                    
                    return redirect()->route('dashboard');
                }
            }
        }

        return redirect()->back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
    }

    public function logout()
    {
        Session::forget('user_authenticated');
        Session::forget('user_data');
        Session::forget('user_role');
        return redirect()->route('login');
    }

    public function dashboard()
    {
        if (!Session::get('user_authenticated')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $user = Session::get('user_data');
        return view('main.dashboard', compact('user'));
    }
}