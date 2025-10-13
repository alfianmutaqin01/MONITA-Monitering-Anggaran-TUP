@extends('layouts.app')

@section('content')
    <div class="row">
        {{-- Card besar: Total Keseluruhan --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card dashnum-card text-dark overflow-hidden">
                <span class="round bg-secondary small"></span>
                <span class="round bg-secondary big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg bg-light-danger">
                                <i class="ti ti-report-money"></i>
                            </div>
                        </div>
                    </div>

                    {{-- totalAll diasumsikan sudah berformat "Rp ..." oleh controller --}}
                    <span class="text-dark d-block f-34 f-w-500 my-2">
                        {{ $totalAll ?? 'Rp 0' }}
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Saldo Saat Ini</p>
                </div>
            </div>
        </div>

        {{-- Kolom tengah: dua kartu kecil (Operasional + Bang) --}}
        <div class="col-xl-4 col-md-12 mb-3">
            <div class="card dashnum-card dashnum-card-small overflow-hidden mb-3">
                <span class="round bg-secondary small"></span>
                <span class="round bg-secondary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-danger me-3">
                            <i class="ti ti-report-money text-danger"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $sumByType['Operasional'] ?? 'Rp 0' }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Saldo Operasional</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-success small"></span>
                <span class="round bg-success big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-success me-3">
                            <i class="text-success ti ti-building"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $sumByType['Bang'] ?? 'Rp 0' }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Saldo Bang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: dua kartu kecil (Remun + NTF) --}}
        <div class="col-xl-4 col-md-12 mb-3">
            <div class="card dashnum-card dashnum-card-small overflow-hidden mb-3">
                <span class="round bg-primary small"></span>
                <span class="round bg-primary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-primary me-3">
                            <i class="ti ti-wallet text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $sumByType['Remun'] ?? 'Rp 0' }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Saldo Remun</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-warning small"></span>
                <span class="round bg-warning big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-warning me-3">
                            <i class="text-warning ti ti-report-money"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $sumByType['NTF'] ?? 'Rp 0' }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Saldo NTF</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Tabel Anggaran --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Detail Anggaran {{ $namaUnit }}</h5>
                <div class="header-action d-flex align-items-center">
                    <button id="btnExport" class="btn btn-secondary">
                        <i class="ti ti-database-export me-1"></i> Ekspor Data
                    </button>
                </div>
            </div>
        </div>

        <div class="card-header">
            <div class="row mb-3 align-items-center">
                <form id="filterForm" method="get" class="d-flex align-items-center gap-2">
                    {{-- Filter Triwulan --}}
                    <select name="tw" id="filterTw" class="form-select form-select-sm"
                        style="font-size:0.9rem; padding:.5rem .8rem; min-width:150px;">
                        <option value="1" {{ $currentTw == 1 ? 'selected' : '' }}>Triwulan I</option>
                        <option value="2" {{ $currentTw == 2 ? 'selected' : '' }}>Triwulan II</option>
                        <option value="3" {{ $currentTw == 3 ? 'selected' : '' }}>Triwulan III</option>
                        <option value="4" {{ $currentTw == 4 ? 'selected' : '' }}>Triwulan IV</option>
                    </select>

                    {{-- Filter Jenis Anggaran --}}
                    <select name="type" id="filterType" class="form-select form-select-sm"
                        style="font-size:0.9rem; padding:.5rem .8rem; min-width:150px;">
                        <option value="all" {{ $currentType == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                        <option value="operasional" {{ $currentType == 'operasional' ? 'selected' : '' }}>Operasional
                        </option>
                        <option value="remun" {{ $currentType == 'remun' ? 'selected' : '' }}>Remun</option>
                        <option value="bang" {{ $currentType == 'bang' ? 'selected' : '' }}>Bang</option>
                        <option value="ntf" {{ $currentType == 'ntf' ? 'selected' : '' }}>NTF</option>
                    </select>
                </form>
            </div>
            @if(!empty($errorMessage))
                <div class="alert alert-danger">{{ $errorMessage }}</div>
            @endif

            <div class="table-responsive card">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="bg-secondary text-center">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white">Unit</th>
                            <th class="text-white">Tipe</th>
                            <th class="text-white">DRK TUP</th>
                            <th class="text-white">Akun</th>
                            <th class="text-white">Nama Akun</th>
                            <th class="text-white">Uraian</th>
                            <th class="text-white">Anggaran</th>
                            <th class="text-white">Realisasi</th>
                            <th class="text-white">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($filtered as $row)
                            <tr>
                                <td>{{ $row['no'] }}</td>
                                <td>{{ $row['unit'] }}</td>
                                <td>{{ $row['tipe'] }}</td>
                                <td>{{ $row['drk_tup'] }}</td>
                                <td>{{ $row['akun'] }}</td>
                                <td>{{ $row['nama_akun'] }}</td>

                                {{-- Uraian: wrap agar bisa turun baris --}}
                                <td style="max-width: 350px; white-space: normal; word-wrap: break-word;">
                                    {{ $row['uraian'] }}
                                </td>

                                <td class="text-end">Rp {{ number_format($row['anggaran'] ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($row['realisasi'] ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($row['saldo'] ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data untuk unit ini pada triwulan
                                    {{ $currentTw }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("filterForm");
            const tw = document.getElementById("filterTw");
            const type = document.getElementById("filterType");

            // Saat user ganti triwulan atau jenis, kirim form GET
            tw.addEventListener("change", () => {
                document.dispatchEvent(new Event('monita:loading:start'));
                form.submit();
            });

            type.addEventListener("change", () => {
                document.dispatchEvent(new Event('monita:loading:start'));
                form.submit();
            });
        });
    </script>
@endsection