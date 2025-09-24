@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="">
        <h2 class="mb-4">Detail Anggaran</h2>

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
                    <select class="form-select" id="TriwulanSelect">
                        <option selected>Pilih Triwulan...</option>
                        <option value="1">Triwulan 1</option>
                        <option value="2">Triwulan 2</option>
                        <option value="3">Triwulan 3</option>
                        <option value="4">Triwulan 4</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" id="TriwulanSelect">
                        <option selected>Pilih Triwulan...</option>
                        <option value="1">Triwulan 1</option>
                        <option value="2">Triwulan 2</option>
                        <option value="3">Triwulan 3</option>
                        <option value="4">Triwulan 4</option>
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
                            <th>Triwulan</th>
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