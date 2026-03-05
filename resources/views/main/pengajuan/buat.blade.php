@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Ajukan Anggaran</h5>
        </div>

        <div class="card-body">

            {{-- Informasi Pengajuan --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Triwulan</label>
                    <input type="text" class="form-control" value="TW {{ $activeTw }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Pengajuan</label>
                    <input type="text" class="form-control" value="{{ now()->format('d M Y') }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Unit</label>
                    <input type="text" class="form-control" value="{{ session('user_data.username') }}" readonly>
                </div>
            </div>


            <form method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="tw" value="{{ $activeTw }}">

                {{-- TYPE ANGGARAN --}}
                <div class="mb-3">
                    <label class="form-label">Tipe Anggaran</label>
                    <select name="type" id="type_anggaran" class="form-select" required>
                        <option value="">-- Pilih Tipe Anggaran --</option>
                        <option value="OPERASIONAL">Operasional</option>
                        <option value="REMUN">Remun</option>
                        <option value="BANG">Bang</option>
                        <option value="NTF">NTF</option>
                    </select>
                    <small class="text-muted">Pilih tipe anggaran yang akan diajukan.</small>
                </div>


                {{-- DRK DAN AKUN --}}
                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">DRK TUP</label>
                        <select name="drk_tup" id="drk_tup" class="form-select" disabled>
                            <option>Menunggu pilihan tipe anggaran...</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Akun Anggaran</label>
                        <input type="text" class="form-control" id="akun" readonly>
                    </div>

                </div>


                {{-- NAMA AKUN --}}
                <div class="mb-3">
                    <label class="form-label">Nama Akun</label>
                    <input type="text" class="form-control" id="nama_akun" readonly>
                </div>


                {{-- URAIAN --}}
                <div class="mb-3">
                    <label class="form-label">Uraian Anggaran</label>
                    <textarea class="form-control" id="uraian" rows="2" readonly></textarea>
                </div>


                {{-- SALDO --}}
                <div class="mb-3">
                    <label class="form-label">Sisa Anggaran Saat Ini</label>
                    <input type="text" class="form-control" id="saldo_anggaran" readonly>
                    <small class="text-muted">Nilai ini diambil otomatis dari data anggaran.</small>
                </div>


                {{-- NOMINAL AJUAN --}}
                <div class="mb-3">
                    <label class="form-label">Nominal Anggaran yang Diajukan</label>
                    <input type="number" name="nominal" id="nominal_ajuan" class="form-control" required>
                    <small class="text-danger">Nominal tidak boleh melebihi sisa anggaran.</small>
                </div>


                {{-- FILE --}}
                <div class="mb-4">
                    <label class="form-label">Dokumen Pendukung</label>
                    <input type="file" name="dokumen" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">
                        Format yang diperbolehkan: PDF, JPG, PNG. Maksimal 5MB.
                    </small>
                </div>


                {{-- BUTTON --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-send me-1"></i> Kirim Pengajuan
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection