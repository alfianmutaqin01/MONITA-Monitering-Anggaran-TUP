@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Daftar Akun Unit</h5>
                <div class="header-action d-flex align-items-center">
                    <button class="btn btn-secondary">
                        <i class="ti ti-database-export me-1"></i> Ekspor Data
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <!-- Kolom kiri: Search -->
                <div class="col-md-8 mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari berdasarkan nama atau kode PP">
                        <button class="btn btn-secondary">
                            <i class="ti ti-search me-1"></i> Cari
                        </button>
                    </div>
                </div>

                <!-- Kolom kanan: Action -->
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-secondary w-100 w-md-auto">
                        <i class="ti ti-user-plus me-1"></i> Tambah Akun Baru
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
                            <th class="text-white">Username</th>
                            <th class="text-white">Password</th>
                            <th class="text-white">Role</th>
                            <th class="text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>LAB</td>
                            <td>Bagian Laboratorium</td>
                            <td>LAB</td>
                            <td>LAB12-</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>AKA</td>
                            <td>Bagian Pelayanan Akademik Pusat</td>
                            <td>AKA</td>
                            <td>AKA71!</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>INV</td>
                            <td>Bagian Sentra Inovasi</td>
                            <td>INV</td>
                            <td>INV98.</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>PPM</td>
                            <td>Lembaga Penelitian dan Pengabdian Masyarakat</td>
                            <td>PPM</td>
                            <td>PPM12-</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>MHS</td>
                            <td>Bagian Kemahasiswaan</td>
                            <td>MHS</td>
                            <td>MHS12-</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>KUG</td>
                            <td>Bagian Keuangan</td>
                            <td>KUG</td>
                            <td>KUG12-</td>
                            <td class="text-danger fw-bold">Admin</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>LOG</td>
                            <td>Bagian Logistik Dan Manajemen Aset</td>
                            <td>LOG</td>
                            <td>LOG98.</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>SIF</td>
                            <td>Bagian Sistem Dan Teknologi Informasi</td>
                            <td>SIF</td>
                            <td>SIF12-</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>SDM</td>
                            <td>Bagian Sumber Daya Manusia</td>
                            <td>SDM</td>
                            <td>SDM71!</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>CDC</td>
                            <td>Bagian Alumni Dan Konseling</td>
                            <td>CDC</td>
                            <td>CDC12-</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>PSR</td>
                            <td>Bagian Pemasaran Dan Admisi</td>
                            <td>PSR</td>
                            <td>PSR12-</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>DET</td>
                            <td>CoE Digital Economic, tourism and creative innovation</td>
                            <td>DET</td>
                            <td>DET98.</td>
                            <td>User</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>HWT</td>
                            <td>CoE Healthcare and well being technologies</td>
                            <td>HWT</td>
                            <td>HWT12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>ICT</td>
                            <td>CoE ICT Infrastructure, Smart Manufacture and Digital Supply Chain</td>
                            <td>ICT</td>
                            <td>ICT71!</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>SCV</td>
                            <td>CoE Sustainbility cities, village and food security</td>
                            <td>SCV</td>
                            <td>SCV12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>DIR</td>
                            <td>Direktorat Universitas Telkom</td>
                            <td>DIR</td>
                            <td>DIR98.</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>HUM</td>
                            <td>Bagian Humas, Kerjasama, Dan KUI</td>
                            <td>HUM</td>
                            <td>HUM12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>SPM</td>
                            <td>Bagian Penjaminan Mutu, Perencanaan, Dan Pengembangan Pembelajaran</td>
                            <td>SPM</td>
                            <td>SPM71!</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>19</td>
                            <td>SEK</td>
                            <td>Bagian Sekretariat Pimpinan, Legal & Internal Audit</td>
                            <td>SEK</td>
                            <td>SEK98.</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>BSD</td>
                            <td>Program Studi S1 Bisnis Digital</td>
                            <td>BSD</td>
                            <td>BSD12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>RPL</td>
                            <td>Program Studi S1 Rekayasa Perangkat Lunak</td>
                            <td>RPL</td>
                            <td>RPL71!</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td>DSA</td>
                            <td>Program Studi S1 Sains Data</td>
                            <td>DSA</td>
                            <td>DSA12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>23</td>
                            <td>INF</td>
                            <td>Program Studi S1 Teknik Informatika</td>
                            <td>INF</td>
                            <td>INF98.</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>24</td>
                            <td>DKV</td>
                            <td>Program Studi S1 Desain Komunikasi Visual</td>
                            <td>DKV</td>
                            <td>DKV71!</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>25</td>
                            <td>DSP</td>
                            <td>Program Studi S1 Desain Produk</td>
                            <td>DSP</td>
                            <td>DSP12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>26</td>
                            <td>TTD</td>
                            <td>Program Studi D3 Teknik Telekomunikasi</td>
                            <td>TTD</td>
                            <td>TTD98.</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>27</td>
                            <td>SIN</td>
                            <td>Program Studi S1 Sistem Informasi</td>
                            <td>SIN</td>
                            <td>SIN12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>28</td>
                            <td>TIN</td>
                            <td>Program Studi S1 Teknik Industri</td>
                            <td>TIN</td>
                            <td>TIN71!</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>29</td>
                            <td>TLO</td>
                            <td>Program Studi S1 Teknik Logistik</td>
                            <td>TLO</td>
                            <td>TLO12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>30</td>
                            <td>TPA</td>
                            <td>Program Studi S1 Teknologi Pangan</td>
                            <td>TPA</td>
                            <td>TPA98.</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>31</td>
                            <td>BME</td>
                            <td>Program Studi S1 Teknik Biomedis</td>
                            <td>BME</td>
                            <td>BME12-</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>32</td>
                            <td>TEL</td>
                            <td>Program Studi S1 Teknik Elektro</td>
                            <td>TEL</td>
                            <td>TEL71!</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <td>33</td>
                            <td>TTS</td>
                            <td>Program Studi S1 Teknik Telekomunikasi</td>
                            <td>TTS</td>
                            <td>TTS98.</td>
                            <td>User</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

@endsection