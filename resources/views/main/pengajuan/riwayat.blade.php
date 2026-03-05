@extends('layouts.app')

@section('content')

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">Riwayat Pengajuan Anggaran</h5>

            <input type="text" class="form-control form-control-sm" placeholder="Cari pengajuan..." style="width:200px">

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="bg-secondary text-white">

                        <tr class="text-center">

                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Triwulan</th>
                            <th>DRK</th>
                            <th>Akun</th>
                            <th>Uraian</th>
                            <th>Nominal</th>
                            <th>Dokumen</th>
                            <th>Approval 1</th>
                            <th>Approval 2</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>


                        {{-- ===================== --}}
                        {{-- DATA 1 - DIPROSES --}}
                        {{-- ===================== --}}

                        <tr>

                            <td class="text-center">1</td>

                            <td>10 Mar 2026</td>

                            <td class="text-center">TW 1</td>

                            <td>TTS16</td>

                            <td>5121105</td>

                            <td>Honorarium Dosen Luar Biasa</td>

                            <td class="text-end">Rp 2.000.000</td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm">
                                    <i class="ti ti-file"></i>
                                </button>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-warning">
                                    Pending
                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-warning">
                                    Diproses
                                </span>

                            </td>

                            <td>-</td>

                            <td class="text-center">-</td>

                        </tr>



                        {{-- ===================== --}}
                        {{-- DATA 2 - DISETUJUI --}}
                        {{-- ===================== --}}

                        <tr>

                            <td class="text-center">2</td>

                            <td>08 Mar 2026</td>

                            <td class="text-center">TW 1</td>

                            <td>INF20</td>

                            <td>5121105</td>

                            <td>Honorarium Dosen Luar Biasa</td>

                            <td class="text-end">Rp 5.000.000</td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm">
                                    <i class="ti ti-file"></i>
                                </button>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-success">
                                    Approved
                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-success">
                                    Disetujui
                                </span>

                            </td>

                            <td>-</td>

                            <td class="text-center">-</td>

                        </tr>



                        {{-- ===================== --}}
                        {{-- DATA 3 - DITOLAK --}}
                        {{-- ===================== --}}

                        <tr>

                            <td class="text-center">3</td>

                            <td>05 Mar 2026</td>

                            <td class="text-center">TW 1</td>

                            <td>DSA21</td>

                            <td>5121105</td>

                            <td>Honorarium Dosen Luar Biasa</td>

                            <td class="text-end">Rp 3.000.000</td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm">
                                    <i class="ti ti-file"></i>
                                </button>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-danger">
                                    Ditolak
                                </span>

                            </td>

                            <td class="text-center">-</td>

                            <td class="text-center">

                                <span class="badge bg-danger">
                                    Ditolak
                                </span>

                            </td>

                            <td>

                                Saldo tidak mencukupi

                            </td>

                            <td class="text-center">

                                <button class="btn btn-secondary btn-sm">

                                    <i class="ti ti-refresh"></i> Ajukan Ulang

                                </button>

                            </td>

                        </tr>



                        {{-- ===================== --}}
                        {{-- DATA 4 - REVISI --}}
                        {{-- ===================== --}}

                        <tr>

                            <td class="text-center">4</td>

                            <td>01 Mar 2026</td>

                            <td class="text-center">TW 1</td>

                            <td>TIN24</td>

                            <td>5121105</td>

                            <td>Honorarium Dosen Luar Biasa</td>

                            <td class="text-end">Rp 4.000.000</td>

                            <td class="text-center">

                                <button class="btn btn-info btn-sm">
                                    <i class="ti ti-file"></i>
                                </button>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-info">
                                    Revisi
                                </span>

                            </td>

                            <td class="text-center">-</td>

                            <td class="text-center">

                                <span class="badge bg-info">
                                    Perlu Revisi
                                </span>

                            </td>

                            <td>

                                Mohon perbaiki dokumen pendukung

                            </td>

                            <td class="text-center">

                                <button class="btn btn-warning btn-sm">

                                    <i class="ti ti-edit"></i> Perbaiki

                                </button>

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>

        </div>

    </div>


@endsection