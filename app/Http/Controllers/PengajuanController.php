<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengajuanController extends Controller
{
    public function daftar()
    {
        if (Session::get('user_role') !== 'keuangan') {
            abort(403, 'Akses ditolak.');
        }

        return view('main.pengajuan.daftar');
    }

    public function create()
    {
        return view('main.pengajuan.buat');
    }

    public function riwayat()
    {
        return view('main.pengajuan.riwayat');
    }
}