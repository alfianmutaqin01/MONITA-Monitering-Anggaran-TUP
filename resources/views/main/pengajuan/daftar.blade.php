@extends('layouts.app')

@section('content')

    <div class="row">

        {{-- ============================= --}}
        {{-- PENGATURAN STATUS PENGAJUAN --}}
        {{-- ============================= --}}
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h5>Status Pengajuan Anggaran</h5>
                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <label class="form-label">Triwulan Aktif</label>

                        <select class="form-select">

                            <option value="1">Triwulan 1</option>
                            <option value="2">Triwulan 2</option>
                            <option value="3">Triwulan 3</option>
                            <option value="4">Triwulan 4</option>

                        </select>

                        <small class="text-muted">
                            Pengajuan unit akan masuk ke triwulan ini
                        </small>

                    </div>

                    <div class="form-check form-switch">

                        <input class="form-check-input" type="checkbox" checked>

                        <label class="form-check-label">
                            Terima Pengajuan
                        </label>

                    </div>

                    <small class="text-muted">
                        Jika dimatikan maka unit tidak dapat mengajukan anggaran
                    </small>

                </div>
            </div>

        </div>


        {{-- ============================= --}}
        {{-- STAFF APPROVAL --}}
        {{-- ============================= --}}
        <div class="col-md-6">

            <div class="card">

                <div class="card-header d-flex justify-content-between">

                    <h5>Staff Approval</h5>

                    <button class="btn btn-primary btn-sm" id="addStaff">

                        <i class="ti ti-plus"></i> Tambah

                    </button>

                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <thead class="table-light">

                            <tr>

                                <th width="10%">No</th>
                                <th>Nama Staff</th>
                                <th width="15%">Aksi</th>

                            </tr>

                        </thead>

                        <tbody id="staffTable">

                            <tr>
                                <td>1</td>
                                <td>Staff Keuangan 1</td>
                                <td>
                                    <button class="btn btn-danger btn-sm removeStaff">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Staff Keuangan 2</td>
                                <td>
                                    <button class="btn btn-danger btn-sm removeStaff">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>

        </div>


        {{-- ============================= --}}
        {{-- DAFTAR AJUAN --}}
        {{-- ============================= --}}
        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    <h5>Daftar Ajuan Anggaran Unit</h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead class="bg-secondary text-white">

                                <tr class="text-center">

                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Unit</th>
                                    <th>DRK</th>
                                    <th>Akun</th>
                                    <th>Uraian</th>
                                    <th>Nominal</th>
                                    <th>Dokumen</th>
                                    <th>Approval 1</th>
                                    <th>Approval 2</th>
                                    <th>Status</th>
                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                                {{-- contoh data --}}

                                <tr>

                                    <td class="text-center">1</td>
                                    <td>10 Mar 2026</td>
                                    <td>TTS</td>
                                    <td>TTS16</td>
                                    <td>5121105</td>
                                    <td>Honorarium Dosen Luar Biasa</td>

                                    <td class="text-end">
                                        Rp 2.000.000
                                    </td>

                                    <td class="text-center">

                                        <button class="btn btn-info btn-sm">
                                            <i class="ti ti-file"></i>
                                        </button>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-warning">
                                            Pending
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-warning">
                                            Pending
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-warning">
                                            Pending
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <button class="btn btn-success btn-sm approveBtn">
                                            <i class="ti ti-check"></i>
                                        </button>

                                        <button class="btn btn-warning btn-sm revisiBtn">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm tolakBtn">
                                            <i class="ti ti-x"></i>
                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- MODAL ALASAN --}}
    {{-- ============================= --}}

    <div class="modal fade" id="modalAlasan">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Masukkan Alasan
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <textarea class="form-control" rows="4" placeholder="Tuliskan alasan revisi / penolakan"></textarea>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </div>
        </div>
    </div>


@endsection


@push('scripts')

    <script>


        /* ========================= */
        /* TAMBAH STAFF */
        /* ========================= */

        document.getElementById("addStaff").addEventListener("click", function () {

            let name = prompt("Masukkan nama staff keuangan");

            if (!name) return;

            let table = document.getElementById("staffTable");

            let rowCount = table.rows.length + 1;

            let row = `
    <tr>
    <td>${rowCount}</td>
    <td>${name}</td>
    <td>
    <button class="btn btn-danger btn-sm removeStaff">
    <i class="ti ti-trash"></i>
    </button>
    </td>
    </tr>
    `;

            table.insertAdjacentHTML("beforeend", row);

        });


        /* ========================= */
        /* HAPUS STAFF */
        /* ========================= */

        document.addEventListener("click", function (e) {

            if (e.target.closest(".removeStaff")) {

                e.target.closest("tr").remove();

            }

        });


        /* ========================= */
        /* MODAL REVISI / TOLAK */
        /* ========================= */

        document.querySelectorAll(".revisiBtn").forEach(btn => {

            btn.addEventListener("click", () => {

                new bootstrap.Modal(
                    document.getElementById("modalAlasan")
                ).show();

            });

        });


        document.querySelectorAll(".tolakBtn").forEach(btn => {

            btn.addEventListener("click", () => {

                new bootstrap.Modal(
                    document.getElementById("modalAlasan")
                ).show();

            });

        });

    </script>

@endpush