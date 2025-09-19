@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <div class="row align-items-center">
            <!-- Judul -->
            <div class="col-md-3">
                <h3 class="mb-0">Pengaturan User</h3>
            </div>

            <!-- Form Search -->
            <div class="col-md-6">
                <form class="d-flex" role="search">
                    <input type="search" class="form-control" placeholder="Cari user..." aria-label="Search">
                </form>
            </div>

            <!-- Tombol Tambah User -->
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-monita">
                    <i class="fa fa-user-plus me-2"></i> Tambah User
                </button>
            </div>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode PP</th>
                <th>Nama PP</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>LAB</td>
                <td>Bagian Laboratorium</td>
                <td>LAB</td>
                <td>LAB12-</td>
                <td>user</td>
                <td class="text-center action-icons">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash"></i>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>AKA</td>
                <td>Bagian Pelayanan Akademik Pusat</td>
                <td>AKA</td>
                <td>AKA71!</td>
                <td>user</td>
                <td class="text-center action-icons">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash"></i>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>INV</td>
                <td>Bagian Sentra Inovasi</td>
                <td>INV</td>
                <td>INV98.</td>
                <td>user</td>
                <td class="text-center action-icons">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash"></i>
                </td>
            </tr>
        </tbody>
    </table>
    </div>
@endsection