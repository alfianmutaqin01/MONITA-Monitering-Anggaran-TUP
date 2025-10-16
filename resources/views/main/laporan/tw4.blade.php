@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Laporan RAW Data Lengkap Triwulan {{ $tw }}</h5>
                <div class="header-action d-flex align-items-center">
                    <button id="btnExport" class="btn btn-secondary">
                        <i class="bi bi-filetype-pdf me-1"></i> Ekspor PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
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
                    </form>
                </div>

                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <select name="type" class="form-select form-select-sm d-inline-block"
                        style="font-size:0.9rem; padding:.5rem .8rem; min-width:180px;" form="filterForm">
                        <option value="all" {{ ($filterType ?? 'all') == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                        <option value="operasional" {{ $filterType == 'operasional' ? 'selected' : '' }}>Operasional</option>
                        <option value="remun" {{ $filterType == 'remun' ? 'selected' : '' }}>Remun</option>
                        <option value="bang" {{ $filterType == 'bang' ? 'selected' : '' }}>Bang</option>
                        <option value="ntf" {{ $filterType == 'ntf' ? 'selected' : '' }}>NTF</option>
                    </select>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const selects = document.querySelectorAll('#filterForm select, select[form="filterForm"]');
                    selects.forEach(sel => {
                        sel.addEventListener('change', () => {
                            document.dispatchEvent(new Event('monita:loading:start'));
                            document.getElementById('filterForm').submit();
                        });
                    });
                });
            </script>

            <div class="table-responsive card">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr class="text-center align-middle">
                            <th class="text-white">NO</th>
                            <th class="text-white">KODE BESAR</th>
                            <th class="text-white">UNIT</th>
                            <th class="text-white">TIPE</th>
                            <th class="text-white">DRK TUP</th>
                            <th class="text-white">AKUN</th>
                            <th class="text-white">NAMA AKUN</th>
                            <th class="text-white">URAIAN</th>
                            <th class="text-white">ANGGARAN</th>
                            <th class="text-white">REALISASI</th>
                            <th class="text-white">SALDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="text-center align-middle">{{ $row['no'] }}</td>
                                <td class="align-middle">{{ $row['kode_besar'] }}</td>
                                <td class="align-middle">{{ $row['unit'] }}</td>
                                <td class="align-middle">{{ $row['tipe'] }}</td>
                                <td class="align-middle">{{ $row['drk_tup'] }}</td>
                                <td class="align-middle">{{ $row['akun'] }}</td>
                                <!-- Nama akun & uraian diatur agar wrap (turun baris) -->
                                <td class="align-middle text-wrap" style="max-width: 220px; white-space: normal;">
                                    {{ $row['nama_akun'] }}
                                </td>
                                <td class="align-middle text-wrap" style="max-width: 300px; white-space: normal;">
                                    {{ $row['uraian'] }}
                                </td>

                                <td class="text-end align-middle">
                                    {{ 'Rp ' . number_format($row['anggaran'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-end align-middle">
                                    {{ 'Rp ' . number_format($row['realisasi'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-end align-middle">
                                    {{ 'Rp ' . number_format($row['saldo'] ?? 0, 0, ',', '.') }}
                                </td>
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