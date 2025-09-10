<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummaryController extends Controller
{
    /**
     * Tampilkan ringkasan anggaran per triwulan.
     */
    public function show($triwulan)
    {
        // validasi input (hanya 1-4 yang boleh)
        if (!in_array($triwulan, [1,2,3,4])) {
            abort(404, "Halaman tidak ditemukan");
        }

        $summaryData = [
            'triwulan' => $triwulan,
            'total_anggaran' => 10000000 * $triwulan, 
            'total_realisasi' => 7500000 * $triwulan,
        ];

        return view('main.summary', $summaryData);
    }
}
