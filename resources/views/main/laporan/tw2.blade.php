@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Laporan RAW Data Lengkap Triwulan {{ $tw }}</h5>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('laporan.triwulan', $tw) }}" class="mb-3">
            <div class="d-flex gap-2 align-items-center">
                <select name="unit" class="form-select" style="width:450px;">
                    <option value="">Semua Unit</option>
                    @foreach($units as $u)
                        <option value="{{ $u }}" {{ (isset($filterUnit) && $filterUnit === $u) ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
                <button class="btn btn-secondary" type="submit"><i class="ti ti-filter me-1"></i> Terapkan Filter</button>
                <a href="{{ route('laporan.triwulan', $tw) }}" class="btn btn-secondary" style="width: 150px; padding: 9px;">Reset</a>
            </div>
        </form>

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
                        <th class="text-white text-end">ANGGARAN</th>
                        <th class="text-white text-end">REALISASI</th>
                        <th class="text-white text-end">SALDO</th>
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
                            <td class="align-middle text-wrap" style="max-width: 220px; white-space: normal;">{{ $row['nama_akun'] }}</td>
                            <td class="align-middle text-wrap" style="max-width: 300px; white-space: normal;">{{ $row['uraian'] }}</td>

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
