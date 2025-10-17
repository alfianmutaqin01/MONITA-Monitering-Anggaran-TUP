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
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-filetype-pdf me-1"></i> Ekspor PDF
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'rka']) }}"
                                    target="_blank">Realisasi RKA</a></li>
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'rkm']) }}"
                                    target="_blank">Realisasi RKM</a></li>
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'pengembangan']) }}"
                                    target="_blank">Realisasi RKA Pengembangan</a></li>
                            <li><a class="dropdown-item" href="{{ route('export.summary', [$tw, 'all']) }}"
                                    target="_blank">Cetak Semua</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body position-relative card">
            <div class="table-responsive card">
                {{-- Wrapper tabel utama --}}
                <div id="scrollWrapper" style="overflow-x: auto; max-height: 75vh;">
                    <table class="table table-bordered table-striped mb-0">
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
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const wrapper = document.querySelector(".table-sticky-wrapper");
            const scrollbar = document.querySelector(".table-scrollbar");

            if (wrapper && scrollbar) {
                const syncScroll = (src, dest) => {
                    dest.scrollLeft = src.scrollLeft;
                };
                wrapper.addEventListener("scroll", () => syncScroll(wrapper, scrollbar));
                scrollbar.addEventListener("scroll", () => syncScroll(scrollbar, wrapper));
            }
        });
    </script>

    <style>
        .table-sticky-wrapper {
            max-height: 75vh;
            overflow-y: auto;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .table-scrollbar {
            position: sticky;
            bottom: 0;
            z-index: 50;
        }
    </style>
@endpush