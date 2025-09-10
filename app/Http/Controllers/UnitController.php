<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function show($kode)
    {
        $user = session('user_data');

        // Cek akses: admin boleh semua, user hanya unit sendiri
        if ($user['role'] !== 'admin' && $user['kode_pp'] !== $kode) {
            abort(403, 'Anda tidak memiliki akses ke unit ini.');
        }

        return view('main.unit', [
            'kode' => $kode,
            'nama' => $user['nama_pp'],
        ]);
    }
}
