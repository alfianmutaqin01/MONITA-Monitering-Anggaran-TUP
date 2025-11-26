<?php

namespace App\Models;

class LaporanModel
{
    public $no;
    public $kode_besar;
    public $unit;
    public $tipe;
    public $drk_tup;
    public $akun;
    public $nama_akun;
    public $uraian;
    public $anggaran;
    public $realisasi;
    public $saldo;

    public function __construct($data = [])
    {
        $this->no = $data['no'] ?? null;
        $this->kode_besar = $data['kode_besar'] ?? '';
        $this->unit = $data['unit'] ?? '';
        $this->tipe = $data['tipe'] ?? '';
        $this->drk_tup = $data['drk_tup'] ?? '';
        $this->akun = $data['akun'] ?? '';
        $this->nama_akun = $data['nama_akun'] ?? '';
        $this->uraian = $data['uraian'] ?? '';
        $this->anggaran = $data['anggaran'] ?? 0;
        $this->realisasi = $data['realisasi'] ?? 0;
        $this->saldo = $data['saldo'] ?? 0;
    }
}
