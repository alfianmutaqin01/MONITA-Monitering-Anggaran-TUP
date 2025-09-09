<?php
namespace App\Http\Controllers;

public function show($name)
{
    $user = session('user_data');

    // jika bukan admin dan bukan unit yang sesuai
    if ($user['role'] !== 'admin' && $user['nama_pp'] !== $name) {
        abort(403, 'Anda tidak memiliki akses ke unit ini.');
    }

    return view('main.unit', compact('name'));
}
