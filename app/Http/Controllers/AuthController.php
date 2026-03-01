<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Exception;
use App\Services\GoogleSheetService;
use App\Models\LoginActivity;

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
     * Load semua user dari Google Sheet
     */
    private function loadUsers()
    {
        return $this->googleSheetService->getUsersFromSheet();
    }

    public function login(Request $request)
    {
        // ================= VALIDASI DASAR =================
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ================= RATE LIMITER =================
        $rateKey = 'login-attempt:' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            return back()->withErrors([
                'login' => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.'
            ])->withInput();
        }

        RateLimiter::hit($rateKey, 60);

        // ================= VALIDASI RECAPTCHA =================
        if (config('services.recaptcha.enabled')) {

            $captchaResponse = $request->input('g-recaptcha-response');

            if (!$captchaResponse) {
                return back()
                    ->withErrors(['captcha' => 'Silakan centang CAPTCHA terlebih dahulu.'])
                    ->withInput();
            }

            try {
                $verify = Http::asForm()
                    ->timeout(5)
                    ->post(
                        'https://www.google.com/recaptcha/api/siteverify',
                        [
                            'secret'   => config('services.recaptcha.secret_key'),
                            'response' => $captchaResponse,
                            'remoteip' => $request->ip(),
                        ]
                    );

                if (!$verify->ok() || !$verify->json('success')) {
                    return back()
                        ->withErrors(['captcha' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'])
                        ->withInput();
                }

            } catch (\Exception $e) {
                return back()
                    ->withErrors(['captcha' => 'Gagal memverifikasi CAPTCHA. Coba lagi nanti.'])
                    ->withInput();
            }
        }

        // ================= LOAD USER =================
        try {
            $users = $this->loadUsers();
        } catch (Exception $e) {

            LoginActivity::create([
                'username'   => $request->username,
                'role'       => '-',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'login_time' => now(),
                'status'     => 'failed',
            ]);

            return back()->withErrors([
                'login' => 'Gagal terhubung ke Google Sheets.'
            ])->withInput();
        }

        $inputUsername = trim($request->username);

        foreach ($users as $user) {

            if (isset($user['username']) && trim($user['username']) === $inputUsername) {

                $inputPassword  = $request->password;
                $storedPassword = $user['password'];

                $isHashed = Hash::info($storedPassword)['algoName'] !== 'unknown';
                $loggedIn = false;

                // ===== Password Hashed =====
                if ($isHashed) {
                    $passwordWithSalt = $inputPassword . $inputUsername;
                    if (Hash::check($passwordWithSalt, $storedPassword)) {
                        $loggedIn = true;
                    }
                }

                // ===== Plain Text (Legacy Support) =====
                if (!$isHashed && $storedPassword === $inputPassword) {
                    $loggedIn = true;
                }

                if ($loggedIn) {

                    RateLimiter::clear($rateKey);

                    LoginActivity::create([
                        'username'   => $user['username'],
                        'role'       => $user['role'],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('User-Agent'),
                        'login_time' => now(),
                        'status'     => 'success',
                    ]);

                    Session::put('user_authenticated', true);
                    Session::put('user_data', $user);
                    Session::put('user_role', $user['role']);

                    $this->googleSheetService->clearUnitsCache();

                    return redirect()->route('dashboard');
                }
            }
        }

        // ================= LOGIN FAILED =================
        LoginActivity::create([
            'username'   => $request->username,
            'role'       => '-',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'login_time' => now(),
            'status'     => 'failed',
        ]);

        return back()->withErrors([
            'login' => 'Username atau password salah.'
        ])->withInput();
    }

    public function logout()
    {
        Session::forget('user_authenticated');
        Session::forget('user_data');
        Session::forget('user_role');

        return redirect()->route('login');
    }
}