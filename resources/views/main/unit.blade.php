@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xl-4 col-md-6">
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
                    <span class="text-dark d-block f-34 f-w-500 my-2">
                        1350
                        <i class="ti ti-arrow-up-right-circle opacity-50"></i>
                    </span>
                    <p class="mb-0 opacity-50">Total Pending Orders</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-secondary small"></span>
                <span class="round bg-secondary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-danger">
                            <i class="ti ti-report-money bg-light-danger"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">$203k</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Income</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-success small"></span>
                <span class="round bg-success big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-success">
                            <i class="text-success ti ti-report-money"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">$203k</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Income</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12">
            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-primary small"></span>
                <span class="round bg-primary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-primary">
                            <i class="ti ti-report-money"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">$203k</h4>
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
                            <i class="text-warning ti ti-report-money"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">$203k</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Income</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="text-center">Anggaran Unit</h1>
        <div class="container-fluid">
            <div class="form-section mt-4">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>UNIT</th>
                                <th>TIPE</th>
                                <th>DRK TUP</th>
                                <th>AKUN</th>
                                <th>NAMA AKUN</th>
                                <th>URAIAN</th>
                                <th>ANGGARAN</th>
                                <th>REALISASI</th>
                                <th>SALDO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA76</td>
                                <td>5121109</td>
                                <td>Beban Pddk Pengajaran</td>
                                <td>Kegiatan Pendidikan Pengajaran</td>
                                <td>7.990.000</td>
                                <td>2.114.000</td>
                                <td>5.876.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA77</td>
                                <td>5121113</td>
                                <td>Beban Pddk Perpustakaan</td>
                                <td>Kegiatan Operasional Pelayanan Perpustakaan</td>
                                <td>37.300.000</td>
                                <td>10.882.589</td>
                                <td>26.417.411</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA78</td>
                                <td>5121116</td>
                                <td>Beban Pddk Wisuda dan Sidang Senat</td>
                                <td>Kegiatan Pendidikan Wisuda TUNC</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA79</td>
                                <td>5121118</td>
                                <td>Beban Operasional Pusat Bahasa</td>
                                <td>Beban Pddk Pelatihan dan Sertifikasi</td>
                                <td>14.650.000</td>
                                <td>2.491.720</td>
                                <td>12.158.280</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA80</td>
                                <td>5211101</td>
                                <td>Gaji Dasar</td>
                                <td>Gaji Dasar</td>
                                <td>9.687.000</td>
                                <td>3.710.000</td>
                                <td>5.977.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA81</td>
                                <td>5211102</td>
                                <td>Tunjangan Dasar</td>
                                <td>Tunjangan Dasar</td>
                                <td>48.072.000</td>
                                <td>19.354.000</td>
                                <td>28.718.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA82</td>
                                <td>5211103</td>
                                <td>Tunjangan Fungsional</td>
                                <td>Tunjangan Posisi/Profesi</td>
                                <td>94.464.000</td>
                                <td>39.409.512</td>
                                <td>55.054.488</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA83</td>
                                <td>5211104</td>
                                <td>Tunjangan PPh Pasal 21</td>
                                <td>Tunjangan PPh Pasal 21</td>
                                <td>18.339.252</td>
                                <td>(472.886)</td>
                                <td>18.812.138</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA84</td>
                                <td>5211105</td>
                                <td>Tunjangan Struktural</td>
                                <td>Tunjangan Jabatan</td>
                                <td>28.182.000</td>
                                <td>6.681.210</td>
                                <td>21.500.790</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA85</td>
                                <td>5211111</td>
                                <td>Insentif Premium</td>
                                <td>Tunjangan Premium</td>
                                <td>21.660.000</td>
                                <td>8.496.000</td>
                                <td>13.164.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA86</td>
                                <td>5212102</td>
                                <td>Tunjangan Hari Raya Keagamaan</td>
                                <td>Tunjangan Hari Raya Keagamaan</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA87</td>
                                <td>5212104</td>
                                <td>Insentif Akhir Tahun</td>
                                <td>Insentif Akhir Tahun</td>
                                <td>98.851.000</td>
                                <td>17.401.000</td>
                                <td>81.450.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA88</td>
                                <td>5213107</td>
                                <td>Fasilitas Makan Siang Pegawai</td>
                                <td>Fasilitas Makan Siang Pegawai</td>
                                <td>10.206.000</td>
                                <td>5.970.000</td>
                                <td>4.236.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA89</td>
                                <td>5213113</td>
                                <td>BPJS TK - Jaminan Kematian & Kecelakaan Kerja</td>
                                <td>BPJS TK - Jaminan Kematian & Kecelakaan Kerja</td>
                                <td>4.013.220</td>
                                <td>3.534.940</td>
                                <td>478.280</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA90</td>
                                <td>5213115</td>
                                <td>BPJS TK - Jaminan Pensiun</td>
                                <td>BPJS TK - Jaminan Pensiun</td>
                                <td>1.893.036</td>
                                <td>797.954</td>
                                <td>1.095.082</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>REMUN</td>
                                <td>AKA91</td>
                                <td>5213116</td>
                                <td>BPJS Kesehatan</td>
                                <td>BPJS Kesehatan</td>
                                <td>3.786.072</td>
                                <td>1.584.476</td>
                                <td>2.201.596</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>NTF</td>
                                <td>AKA92</td>
                                <td>5291103</td>
                                <td>Beban Hibah (Dengan Pembatasan)</td>
                                <td>Beban Hibah (Dengan Pembatasan)</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA93</td>
                                <td>5314101</td>
                                <td>Beban Keperluan Rumah Tangga</td>
                                <td>Kegiatan Keperluan Rumah Tangga</td>
                                <td>3.600.000</td>
                                <td>1.869.636</td>
                                <td>1.730.364</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA94</td>
                                <td>5314102</td>
                                <td>Beban ATK Operasional</td>
                                <td>Keperluan ATK operasional bulanan</td>
                                <td>8.478.500</td>
                                <td>8.140.900</td>
                                <td>337.600</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA95</td>
                                <td>5314105</td>
                                <td>Beban Rapat dan Logistik</td>
                                <td>Kegiatan rapat internal dan eksternal Tel U</td>
                                <td>12.030.000</td>
                                <td>7.794.506</td>
                                <td>4.235.494</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>OPERASIONAL</td>
                                <td>AKA96</td>
                                <td>5611103</td>
                                <td>BPP Inventaris Kantor</td>
                                <td>Kegiatan Pemeliharaan dan Perbaikan Inventaris Kantor</td>
                                <td>1.500.000</td>
                                <td>957.545</td>
                                <td>542.455</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>BANG</td>
                                <td>DIR387</td>
                                <td>5311101</td>
                                <td>Beban Bang Lembaga</td>
                                <td>Studi Banding Perpus TUB bertujuan untuk meningkatkan standarisasi layanan perpustakaan
                                    TUP
                                    dengan mengadopsi praktik terbaik dari perpustakaan TUB.</td>
                                <td>6.950.000</td>
                                <td>0</td>
                                <td>6.950.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>BANG</td>
                                <td>DIR388</td>
                                <td>5311101</td>
                                <td>Beban Bang Lembaga</td>
                                <td>Kerja Sama Test EPrT TUP-TUB bertujuan untuk menyamakan standar pelaksanaan Test EPrT
                                    antara
                                    TUP dan TUB, guna memastikan kesetaraan mutu evaluasi kemampuan bahasa Inggris.</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>BANG</td>
                                <td>DIR443</td>
                                <td>5311101</td>
                                <td>Beban Bang Lembaga</td>
                                <td>Inisiasi kerja sama dengan perpustakaan wilayah BARLINGMASCAKEB dan Jawa Tengah dalam
                                    peningkatan mutu pelayanan perpustakaan</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>BANG</td>
                                <td>DIR444</td>
                                <td>5311101</td>
                                <td>Beban Bang Lembaga</td>
                                <td>Studi Banding Implementasi MBKM dan PPDU bertujuan untuk mempersiapkan pelaksanaan MBKM
                                    (Merdeka Belajar Kampus Merdeka) dan PPDU (Program Pengembangan Dosen dan Unit) di tahun
                                    ajaran baru.</td>
                                <td>5.350.000</td>
                                <td>0</td>
                                <td>5.350.000</td>
                            </tr>
                            <tr>
                                <td>AKA</td>
                                <td>BANG</td>
                                <td>DIR445</td>
                                <td>5311101</td>
                                <td>Beban Bang Lembaga</td>
                                <td>Sidang Penetapan Status Akademik dan Persiapan Wisuda bertujuan untuk memastikan
                                    kelancaran
                                    proses penetapan status akademik mahasiswa dan persiapan pelaksanaan wisuda.</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row align-items-start">
                <div class="col">
                </div>
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>TIPE</th>
                                <th>ANGGARAN</th>
                                <th>REALISASI</th>
                                <th>SALDO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ALL</td>
                                <td>437.002.080</td>
                                <td>140.717.102</td>
                                <td>296.284.978</td>
                            </tr>
                            <tr>
                                <td>OPERASIONAL</td>
                                <td>85.548.500</td>
                                <td>34.250.896</td>
                                <td>51.297.604</td>
                            </tr>
                            <tr>
                                <td>BANG</td>
                                <td>12.300.000</td>
                                <td>0</td>
                                <td>12.300.000</td>
                            </tr>
                            <tr>
                                <td>REMUN</td>
                                <td>339.153.580</td>
                                <td>106.466.206</td>
                                <td>232.687.374</td>
                            </tr>
                            <tr>
                                <td>NTF</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection