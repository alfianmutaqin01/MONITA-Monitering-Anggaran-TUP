@extends('layouts.app')

@section('content')
    @php
        function formatRupiah($n)
        {
            $n = (float) $n;
            $rupiah = number_format(abs($n), 0, ',', '.');
            return ($n < 0) ? '(Rp ' . $rupiah . ')' : 'Rp ' . $rupiah;
        }
        function formatPercentCell($s)
        {
            $s = trim((string) $s);
            if ($s === '')
                return '-';
            return (strpos($s, '%') === false) ? ($s . '%') : $s;
        }

        //UNTUK MENGHINDARI DUPLIKASI
        $dataForBody = $data; // Default: Semua data
        $totalRow = null;

        if (!empty($data) && count($data) > 0) {
            $totalRow = end($data);
            $dataForBody = array_slice($data, 0, count($data) - 1);
        }
    @endphp


    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Summary Anggaran Triwulan {{ $tw }}</h5>
                <div class="header-action d-flex align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-filetype-pdf me-1"></i> Ekspor PDF
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'rka']) }}"
                                    target="_blank">Realisasi RKA</a></li>
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'rkm']) }}"
                                    target="_blank">Realisasi RKM</a></li>
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'bang']) }}"
                                    target="_blank">Realisasi Bang</a></li>
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'all']) }}"
                                    target="_blank">Cetak Semua</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive" style="max-height: 75vh;">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="bg-secondary text-white">
                        <tr class="text-center text-nowrap">
                            <th class="text-white sticky-top" style="min-width: 60px;">NO</th>
                            <th class="text-white sticky-top" style="min-width: 100px;">KODE PP</th>
                            <th class="text-white sticky-top" style="min-width: 200px;">NAMA PP</th>
                            <th class="text-white sticky-top" style="min-width: 100px;">BIDANG</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">ANGGARAN TW {{ $tw }}</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">REALISASI TW {{ $tw }}</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">SALDO TW {{ $tw }}</th>
                            <th class="text-white sticky-top" style="min-width: 120px;">% SERAPAN ALL</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">RKA OPERASI</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">REAL OPERASI</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">SALDO OPERASI</th>
                            <th class="text-white sticky-top" style="min-width: 120px;">% SERAPAN OPERASI</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">RKA BANG</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">REAL BANG</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">SISA BANG</th>
                            <th class="text-white sticky-top" style="min-width: 120px;">% SERAPAN BANG</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">RKM OPERASI</th>
                            <th class="text-white sticky-top" style="min-width: 150px;">REAL RKM</th>
                            <th class="text-white sticky-top" style="min-width: 100px;">% RKM</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOP HANYA PADA DATA NORMAL (TANPA BARIS TOTAL) --}}
                        @forelse($dataForBody as $row)
                            <tr class="text-nowrap">
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
                                <td class="text-end">{{($row['rkm_operasi']) }}</td>
                                <td class="text-end">{{($row['real_rkm']) }}</td>
                                <td class="text-center">{{ formatPercentCell($row['persen_rkm']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="19" class="text-center">Tidak ada data unit yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($totalRow)
                        <tfoot>
                            <tr class="fw-bold text-nowrap" style="background-color: #d7d7d7;">
                                {{-- Merge 4 Kolom Pertama --}}
                                <td colspan="4" class="text-center">TOTAL KESELURUHAN</td>

                                {{-- Tampilkan Nilai Total dari $totalRow --}}
                                <td class="text-end">{{ formatRupiah($totalRow['anggaran_tw']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['realisasi_tw']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['saldo_tw']) }}</td>
                                <td class="text-center">{{ formatPercentCell($totalRow['serapan_all']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['rka_operasi']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['real_operasi']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['saldo_operasi']) }}</td>
                                <td class="text-center">{{ formatPercentCell($totalRow['serapan_oper']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['rka_bang']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['real_bang']) }}</td>
                                <td class="text-end">{{ formatRupiah($totalRow['sisa_bang']) }}</td>
                                <td class="text-center">{{ formatPercentCell($totalRow['serapan_bang']) }}</td>
                                <td class="text-end">{{($totalRow['rkm_operasi']) }}</td>
                                <td class="text-end">{{($totalRow['real_rkm']) }}</td>
                                <td class="text-center">{{ formatPercentCell($totalRow['persen_rkm']) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
@endpush