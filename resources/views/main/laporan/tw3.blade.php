@extends('layouts.app')

@section('content')
    @php
        // 🚩 ASUMSI: Fungsi helper ini sudah tersedia secara global/trait
        function formatRupiah($n) {
            $n = (float) $n;
            $rupiah = number_format(abs($n), 0, ',', '.');
            return ($n < 0) ? '(Rp ' . $rupiah . ')' : 'Rp ' . $rupiah;
        }
        function formatPercentCell($s) {
            $s = trim((string) $s);
            if ($s === '') return '-';
            return $s; // Diasumsikan sudah dalam format persen atau kita tidak perlu menambahkan % di sini.
        }
    @endphp

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan RAW Data Lengkap Triwulan {{ $tw }}</h5>
                <div class="header-action d-flex align-items-center">
                    <a href="{{ route('export.laporan-triwulan', ['tw' => $tw, 'unit' => request('unit'), 'type' => request('type')]) }}"
                        target="_blank" class="btn btn-secondary">
                        <i class="bi bi-filetype-pdf me-1"></i> Ekspor PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    {{-- Form ini akan di-submit ulang ke route laporan.triwulan --}}
                    <form method="GET" action="{{ route('laporan.triwulan', $tw) }}" id="filterForm"
                        class="d-flex gap-2 align-items-center">
                        <select name="unit" class="form-select form-select-sm"
                            style="font-size:0.9rem; padding:.5rem .8rem; min-width:180px;">
                            <option value="">Semua Unit</option>
                            @foreach($units as $u)
                                <option value="{{ $u }}" {{ (isset($filterUnit) && $filterUnit === $u) ? 'selected' : '' }}>
                                    {{ $u }}
                                </option>
                            @endforeach
                        </select>
                        <select name="type" class="form-select form-select-sm" 
                            style="font-size:0.9rem; padding:.5rem .8rem; min-width:180px;">
                            <option value="all" {{ ($filterType ?? 'all') == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="operasional" {{ $filterType == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="remun" {{ $filterType == 'remun' ? 'selected' : '' }}>Remun</option>
                            <option value="bang" {{ $filterType == 'bang' ? 'selected' : '' }}>Bang</option>
                            <option value="ntf" {{ $filterType == 'ntf' ? 'selected' : '' }}>NTF</option>
                        </select>
                    </form>
                </div>

            </div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const form = document.getElementById("filterForm");
                    const selects = document.querySelectorAll('#filterForm select');
                    
                    // Otomatis submit saat perubahan filter
                    selects.forEach(sel => {
                        sel.addEventListener('change', () => {
                            document.dispatchEvent(new Event('monita:loading:start'));
                            form.submit();
                        });
                    });
                });
            </script>

            {{-- 🚩 PERBAIKAN: Menghapus div .card yang redundan di sini --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr class="text-center align-middle text-nowrap">
                            <th class="text-white" style="min-width: 50px;">NO</th>
                            <th class="text-white" style="min-width: 100px;">KODE BESAR</th>
                            <th class="text-white" style="min-width: 80px;">UNIT</th>
                            <th class="text-white" style="min-width: 80px;">TIPE</th>
                            <th class="text-white" style="min-width: 100px;">DRK TUP</th>
                            <th class="text-white" style="min-width: 100px;">AKUN</th>
                            <th class="text-white" style="min-width: 200px;">NAMA AKUN</th>
                            <th class="text-white" style="min-width: 300px;">URAIAN</th>
                            <th class="text-white" style="min-width: 120px;">ANGGARAN</th>
                            <th class="text-white" style="min-width: 120px;">REALISASI</th>
                            <th class="text-white" style="min-width: 120px;">SALDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="text-center align-middle">{{ $row['no'] }}</td>
                                <td class="align-middle text-nowrap">{{ $row['kode_besar'] }}</td>
                                <td class="align-middle text-nowrap">{{ $row['unit'] }}</td>
                                <td class="align-middle text-nowrap">{{ $row['tipe'] }}</td>
                                <td class="align-middle text-nowrap">{{ $row['drk_tup'] }}</td>
                                <td class="align-middle text-nowrap">{{ $row['akun'] }}</td>
                                
                                {{-- Nama akun & uraian diatur agar wrap (turun baris) --}}
                                <td class="align-middle text-wrap" style="max-width: 220px; white-space: normal;">
                                    {{ $row['nama_akun'] }}
                                </td>
                                <td class="align-middle text-wrap" style="max-width: 300px; white-space: normal;">
                                    {{ $row['uraian'] }}
                                </td>

                                <td class="text-end align-middle">{{ formatRupiah($row['anggaran']) }}</td>
                                <td class="text-end align-middle">{{ formatRupiah($row['realisasi']) }}</td>
                                <td class="text-end align-middle">{{ formatRupiah($row['saldo']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
