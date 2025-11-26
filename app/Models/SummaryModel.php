<?php

namespace App\Models;

class SummaryModel
{
    public $no;
    public $kode_pp;
    public $nama_pp;
    public $bidang;
    public $anggaran_tw;
    public $realisasi_tw;
    public $saldo_tw;
    public $serapan_all;
    public $rka_operasi;
    public $real_operasi;
    public $saldo_operasi;
    public $serapan_oper;
    public $rka_bang;
    public $real_bang;
    public $sisa_bang;
    public $serapan_bang;
    public $rkm_operasi;
    public $real_rkm;
    public $persen_rkm;

    public function __construct($data = [])
    {
        $this->no = $data['no'] ?? null;
        $this->kode_pp = $data['kode_pp'] ?? '';
        $this->nama_pp = $data['nama_pp'] ?? '';
        $this->bidang = $data['bidang'] ?? '';
        $this->anggaran_tw = $data['anggaran_tw'] ?? 0;
        $this->realisasi_tw = $data['realisasi_tw'] ?? 0;
        $this->saldo_tw = $data['saldo_tw'] ?? 0;
        $this->serapan_all = $data['serapan_all'] ?? '';
        $this->rka_operasi = $data['rka_operasi'] ?? 0;
        $this->real_operasi = $data['real_operasi'] ?? 0;
        $this->saldo_operasi = $data['saldo_operasi'] ?? 0;
        $this->serapan_oper = $data['serapan_oper'] ?? '';
        $this->rka_bang = $data['rka_bang'] ?? 0;
        $this->real_bang = $data['real_bang'] ?? 0;
        $this->sisa_bang = $data['sisa_bang'] ?? 0;
        $this->serapan_bang = $data['serapan_bang'] ?? '';
        $this->rkm_operasi = $data['rkm_operasi'] ?? 0;
        $this->real_rkm = $data['real_rkm'] ?? 0;
        $this->persen_rkm = $data['persen_rkm'] ?? '';
    }
}
