@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-credit-card"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <a href="#" class="avtar avtar-s bg-secondary text-white dropdown-toggle arrow-none"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item">Import Card</button></li>
                                    <li><button class="dropdown-item">Export</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">
                        1350
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-teal-900 dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-credit-card"></i>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">
                        1350
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-yellow-900 dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-credit-card"></i>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">
                        1350
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-credit-card"></i>
                            </div>
                        </div>

                    </div>
                    <div class="tab-content" id="chart-tab-tabContent">
                        <div class="tab-pane show active" id="chart-tab-home" role="tabpanel"
                            aria-labelledby="chart-tab-home-tab" tabindex="0">
                            <div class="row">
                                <div class="cols-6">
                                    <span class="text-white d-block f-34 f-w-500 my-2">
                                        RP. 135.000
                                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                                    </span>
                                    <p class="mb-0 opacity-50">Total Earning</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="chart-tab-profile" role="tabpanel" aria-labelledby="chart-tab-profile-tab"
                            tabindex="0">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-white d-block f-34 f-w-500 my-2">
                                        $291
                                        <i class="ti ti-arrow-down-right-circle opacity-50"></i>
                                    </span>
                                    <p class="mb-0 opacity-50">C/W Last Year</p>
                                </div>
                                <div class="col-6">
                                    <div id="tab-chart-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Daftar Anggaran Unit</h5>
                {{-- <div class="header-action d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-danger me-2">
                        <i class="ti ti-rotate-clockwise me-1"></i> Muat Ulang
                    </button>
                    <button class="btn btn-sm btn-danger">
                        <i class="ti ti-plus me-1"></i> Tambah Anggaran
                    </button>
                </div> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari anggaran...">
                </div>
                <div class="d-flex align-items-center">
                    <select class="form-select me-2" style="width: 150px;">
                        <option selected>Status</option>
                        <option>Aktif</option>
                        <option>Pending</option>
                        <option>Tidak Aktif</option>
                    </select>
                    <button class="btn btn-outline-danger">
                        <i class="ti ti-database-export me-1"></i> Ekspor Data
                    </button>
                </div>
            </div>
            <div class="table-responsive card">
                <table class="table table-striped">
                    <thead class="bg-secondary">
                        <tr class="text-gray-100">
                            <th class="text-gray-100">No</th>
                            <th>Kode PP</th>
                            <th>Nama PP</th>
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Aksi</th>
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
                        <li class="paginate_button page-item active"><a href="#" class="page-link">1</a></li>
                        <li class="paginate_button page-item "><a href="#" class="page-link">2</a></li>
                        <li class="paginate_button page-item "><a href="#" class="page-link">3</a></li>
                        <li class="paginate_button page-item next"><a href="#" class="page-link">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="form-section">
            <h3>Data Anggaran Terbaru</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Trivulan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Laboratorium</td>
                            <td>1</td>
                            <td>Rp 15.000.000</td>
                            <td><span class="badge-success">Disetujui</span></td>
                        </tr>
                        <tr>
                            <td>Keuangan</td>
                            <td>1</td>
                            <td>Rp 25.000.000</td>
                            <td><span class="badge-warning">Proses</span></td>
                        </tr>
                        <tr>
                            <td>IT</td>
                            <td>1</td>
                            <td>Rp 35.000.000</td>
                            <td><span class="badge-success">Disetujui</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-credit-card"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group">
                                <a href="#" class="avtar avtar-s bg-secondary text-white dropdown-toggle arrow-none"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item">Import Card</button></li>
                                    <li><button class="dropdown-item">Export</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <span class="text-white d-block f-34 f-w-500 my-2">
                        1350
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="avtar avtar-lg">
                                <i class="text-white ti ti-credit-card"></i>
                            </div>
                        </div>
                        <div class="col-auto">
                            <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-white active" id="chart-tab-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#chart-tab-home" role="tab" aria-controls="chart-tab-home"
                                        aria-selected="true">
                                        Month
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-white" id="chart-tab-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#chart-tab-profile" role="tab" aria-controls="chart-tab-profile"
                                        aria-selected="false">
                                        Year
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content" id="chart-tab-tabContent">
                        <div class="tab-pane show active" id="chart-tab-home" role="tabpanel"
                            aria-labelledby="chart-tab-home-tab" tabindex="0">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-white d-block f-34 f-w-500 my-2">
                                        $135
                                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                                    </span>
                                    <p class="mb-0 opacity-50">Total Earning</p>
                                </div>
                                <div class="col-6">
                                    <div id="tab-chart-1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="chart-tab-profile" role="tabpanel" aria-labelledby="chart-tab-profile-tab"
                            tabindex="0">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-white d-block f-34 f-w-500 my-2">
                                        $291
                                        <i class="ti ti-arrow-down-right-circle opacity-50"></i>
                                    </span>
                                    <p class="mb-0 opacity-50">C/W Last Year</p>
                                </div>
                                <div class="col-6">
                                    <div id="tab-chart-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card bg-primary-dark dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-primary small"></span>
                <span class="round bg-primary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-white ti ti-credit-card"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">$203k</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Income</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-warning small"></span>
                <span class="round bg-warning big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-warning">
                            <i class="text-warning ti ti-credit-card"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">$203k</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Income</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <small class="text-muted">Total Growth</small>
                            <h3>$2,324.00</h3>
                        </div>
                        <div class="col-auto">
                            <select class="form-select p-r-35">
                                <option>Today</option>
                                <option selected>This Month</option>
                                <option>This Year</option>
                            </select>
                        </div>
                    </div>
                    <div id="growthchart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4>Popular Stocks</h4>
                        </div>
                        <div class="col-auto"></div>
                    </div>
                    <div class="rounded bg-light-secondary overflow-hidden mb-3">
                        <div class="px-3 pt-3">
                            <div class="row mb-1 align-items-start">
                                <div class="col">
                                    <h5 class="text-secondary mb-0">Bajaj Finery</h5>
                                    <small class="text-muted">10% Profit</small>
                                </div>
                                <div class="col-auto">
                                    <h4 class="mb-0">$1839.00</h4>
                                </div>
                            </div>
                        </div>
                        <div id="bajajchart"></div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <div class="row align-items-start">
                                <div class="col">
                                    <h5 class="mb-0">Bajaj Finery</h5>
                                    <small class="text-success">10% Profit</small>
                                </div>
                                <div class="col-auto">
                                    <h4 class="mb-0">
                                        $1839.00
                                        <span class="ms-2 align-top avtar avtar-xxs bg-light-success"><i
                                                class="ti ti-chevron-up text-success"></i></span>
                                    </h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-start">
                                <div class="col">
                                    <h5 class="mb-0">TTML</h5>
                                    <small class="text-danger">10% Profit</small>
                                </div>
                                <div class="col-auto">
                                    <h4 class="mb-0">
                                        $100.00
                                        <span class="ms-2 align-top avtar avtar-xxs bg-light-danger"><i
                                                class="ti ti-chevron-down text-danger"></i></span>
                                    </h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-start">
                                <div class="col">
                                    <h5 class="mb-0">Reliance</h5>
                                    <small class="text-success">10% Profit</small>
                                </div>
                                <div class="col-auto">
                                    <h4 class="mb-0">
                                        $200.00
                                        <span class="ms-2 align-top avtar avtar-xxs bg-light-success"><i
                                                class="ti ti-chevron-up text-success"></i></span>
                                    </h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-start">
                                <div class="col">
                                    <h5 class="mb-0">TTML</h5>
                                    <small class="text-danger">10% Profit</small>
                                </div>
                                <div class="col-auto">
                                    <h4 class="mb-0">
                                        $189.00
                                        <span class="ms-2 align-top avtar avtar-xxs bg-light-danger"><i
                                                class="ti ti-chevron-down text-danger"></i></span>
                                    </h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row align-items-start">
                                <div class="col">
                                    <h5 class="mb-0">Stolon</h5>
                                    <small class="text-danger">10% Profit</small>
                                </div>
                                <div class="col-auto">
                                    <h4 class="mb-0">
                                        $189.00
                                        <span class="ms-2 align-top avtar avtar-xxs bg-light-danger"><i
                                                class="ti ti-chevron-down text-danger"></i></span>
                                    </h4>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="text-center">
                        <a href="#!" class="b-b-primary text-primary">
                            View all
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection