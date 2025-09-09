<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SpreadsheetAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // cek apakah user sudah login dari Google Sheets
        if (!Session::get('user_authenticated')) {
            return redirect()->route('login')->withErrors(['login' => 'Anda harus login terlebih dahulu.']);
        }

        return $next($request);
    }
}
