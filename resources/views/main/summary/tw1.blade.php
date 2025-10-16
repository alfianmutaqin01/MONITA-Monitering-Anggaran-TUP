@extends('layouts.app')

@section('content')
    @php
        function formatRupiah($n)
        {
            $n = (float) $n;
            if ($n < 0)
                return '(' . 'Rp ' . number_format(abs($n), 0, ',', '.') . ')';
            return 'Rp ' . number_format($n, 0, ',', '.');
        }
        function formatPercentCell($s)
        {
            $s = trim((string) $s);
            if ($s === '')
                return '-';
            // jika sudah ada %, tampilkan apa adanya; jika tidak, tambahkan %
            return (strpos($s, '%') === false) ? ($s . '%') : $s;
        }
    @endphp


    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Summary Anggaran Triwulan {{ $tw }}</h5>
                <div class="header-action d-flex align-items-center">
                    <button id="btnExport" class="btn btn-secondary">
                        <i class="bi bi-filetype-pdf me-1"></i> Ekspor PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive card">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr class="text-center">
                            <th class="text-white">NO</th>
                            <th class="text-white">KODE PP</th>
                            <th class="text-white">NAMA PP</th>
                            <th class="text-white">BIDANG</th>
                            <th class="text-white">ANGGARAN TW {{ $tw }}</th>
                            <th class="text-white">REALISASI TW {{ $tw }}</th>
                            <th class="text-white">SALDO TW {{ $tw }}</th>
                            <th class="text-white">% SERAPAN ALL</th>
                            <th class="text-white">RKA OPERASI</th>
                            <th class="text-white">REAL OPERASI</th>
                            <th class="text-white">SALDO OPERASI</th>
                            <th class="text-white">% SERAPAN OPERASI</th>
                            <th class="text-white">RKA BANG</th>
                            <th class="text-white">REAL BANG</th>
                            <th class="text-white">SISA BANG</th>
                            <th class="text-white">% SERAPAN BANG</th>
                            <th class="text-white">RKM OPERASI</th>
                            <th class="text-white">REAL RKM</th>
                            <th class="text-white">% RKM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr class="{{ $loop->last ? 'fw-bold' : '' }} text-nowrap">
                                <td class="text-center">{{ $row['no'] }}</td>
                                <td>{{ $row['kode_pp'] }}</td>
                                <td>{{ $row['nama_pp'] }}</td>
                                <td>{{ $row['bidang'] }}</td>
                                <td class="text-end">{{ formatRupiah($row['anggaran_tw']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['realisasi_tw']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['saldo_tw']) }}</td>
                                <td class="text-center">{{ formatPercentCell($row['serapan_all']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['rka_operasi']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['real_operasi']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['saldo_operasi']) }}</td>
                                <td class="text-center">{{ formatPercentCell($row['serapan_oper']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['rka_bang']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['real_bang']) }}</td>
                                <td class="text-end">{{ formatRupiah($row['sisa_bang']) }}</td>
                                <td class="text-center">{{ formatPercentCell($row['serapan_bang']) }}</td>
                                <td class="text-end">{{ ($row['rkm_operasi']) }}</td>
                                <td class="text-end">{{ ($row['real_rkm']) }}</td>
                                <td class="text-center">{{ formatPercentCell($row['persen_rkm']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="23" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection