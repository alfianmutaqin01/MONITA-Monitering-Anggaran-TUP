@extends('layouts.app')

@section('title', "Summary Anggaran Triwulan $triwulan")

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Daftar Anggaran Unit</h5>
                <div class="header-action d-flex align-items-center">
                    <button class="btn btn-secondary">
                        <i class="ti ti-database-export me-1"></i> Ekspor Data
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group">
                    <select class="form-select me-2 border-hover-secondary" style="width: 150px;">
                        <option selected>Periode</option>
                        <option>Terwulan 1</option>
                        <option>Terwulan 2</option>
                        <option>Terwulan 3</option>
                        <option>Terwulan 4</option>
                    </select>
                    <select class="form-select me-2" style="width: 150px;">
                        <option selected>Type</option>
                        <option>ALL</option>
                        <option>OPERASIONAL</option>
                        <option>BANG</option>
                        <option>REMUN</option>
                        <option>NTF</option>
                    </select>
                    <button class="btn btn-secondary">
                        <i class="ti ti-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>
            <div class="table-responsive card table-primary">
                <table class="table table-striped">
                    <thead class="bg-secondary">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white">Kode PP</th>
                            <th class="text-white">Nama PP</th>
                            <th class="text-white">Anggaran</th>
                            <th class="text-white">Realisasi</th>
                            <th class="text-white">Sisa</th>
                            <th class="text-white">Status</th>
                            <th class="text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>LAB</td>
                            <td>Bagian Laboratorium</td>
                            <td>Rp 500.000.000</td>
                            <td>Rp 450.000.000</td>
                            <td>Rp 50.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>AKA</td>
                            <td>Pelayanan Akademik</td>
                            <td>Rp 300.000.000</td>
                            <td>Rp 320.000.000</td>
                            <td><span class="text-danger">-Rp 20.000.000</span></td>
                            <td><span class="badge bg-danger">Overspent</span></td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>SIF</td>
                            <td>Sistem Informasi</td>
                            <td>Rp 450.000.000</td>
                            <td>Rp 200.000.000</td>
                            <td>Rp 250.000.000</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="dataTables_info">Menampilkan 1 sampai 3 dari 32 entri</div>
                <div class="dataTables_paginate paging_simple_numbers">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous disabled"><a href="#" class="page-link">Previous</a>
                        </li>
                        <li class="paginate_button page-item active"><a href="#" class="page-link text-white">1</a></li>
                        <li class="paginate_button page-item "><a href="#" class="page-link">2</a></li>
                        <li class="paginate_button page-item "><a href="#" class="page-link">3</a></li>
                        <li class="paginate_button page-item next"><a href="#" class="page-link">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection