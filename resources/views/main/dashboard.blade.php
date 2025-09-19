@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
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
    <div class="">
        <p>Selamat datang, {{ session('user_data.nama_pp') ?? session('user_data.username') }} </p>
        <h2 class="mb-4">Detail Anggaran</h2>
        <div class="row align-items-center">
            <div class="col-3">
                <div>

                </div>
                <div class="card">

                    <div class="metric-title">Total Episode Aktif</div>
                    <div class="metric-value">12</div>
                </div>
            </div>

            <div class="col-3">
                <div class="card">
                    <div class="metric-title">Total Feedback</div>
                    <div class="metric-value">5.456</div>
                </div>
            </div>

            <div class="col-3">
                <div class="card">
                    <div class="metric-title">NPS Minggu ini</div>
                    <div class="metric-value">+42</div>
                </div>
            </div>

            <div class="col-3">
                <div class="card">
                    <div class="metric-title">Produk Terpantau</div>
                    <div class="metric-value">8</div>
                </div>
            </div>
        </div>

        <div class="form-section col-2">
            <form>
                <div class="mb-3">
                    <select class="form-select" id="unitSelect">
                        <option selected>Pilih unit...</option>
                        <option value="1">Laboratorium</option>
                        <option value="2">Keuangan</option>
                        <option value="3">IT</option>
                        <option value="4">HRD</option>
                    </select>
                </div>

                <div class="mb-3">
                    <select class="form-select" id="trivulanSelect">
                        <option selected>Pilih trivulan...</option>
                        <option value="1">Trivulan 1</option>
                        <option value="2">Trivulan 2</option>
                        <option value="3">Trivulan 3</option>
                        <option value="4">Trivulan 4</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" id="trivulanSelect">
                        <option selected>Pilih trivulan...</option>
                        <option value="1">Trivulan 1</option>
                        <option value="2">Trivulan 2</option>
                        <option value="3">Trivulan 3</option>
                        <option value="4">Trivulan 4</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-monita">Simpan Data</button>
            </form>
        </div>

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
@endsection