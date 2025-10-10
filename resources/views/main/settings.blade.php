@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8 col-md-10 mx-auto">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pengaturan Tahun Anggaran</h5>
                    <i class="ti ti-settings fs-4"></i>
                </div>
                <div class="card-body">
                    
                    {{-- Panduan Akses Spreadsheet --}}
                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2"><i class="ti ti-info-circle me-2"></i>Panduan Menghubungkan Spreadsheet:</h6>
                        <ol class="mb-2">
                            <li>Buka file <strong>Google Sheets</strong> tahun anggaran yang ingin dikelola.</li>
                            <li>Klik tombol <strong>Bagikan</strong> di kanan atas.</li>
                            <li>Tambahkan akun berikut sebagai editor:<br>
                                <code>keuangan-tup@monita-471208.iam.gserviceaccount.com</code>
                            </li>
                            <li>Pastikan izin akses diset ke <strong>Editor</strong>.</li>
                            <li>Salin <strong>link</strong> spreadsheet tersebut, lalu tempelkan ke form di bawah.</li>
                        </ol>
                    </div>

                    {{-- Form untuk ganti spreadsheet --}}
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="sheet_link" class="form-label fw-bold">Link Google Sheet Tahun Anggaran:</label>
                            <div class="input-group">
                                <input type="url" id="sheet_link" name="sheet_link" class="form-control" 
                                       placeholder="https://docs.google.com/spreadsheets/d/xxxxxx/edit"
                                       value="{{ old('sheet_link', $currentSheetLink) }}" required>
                                <div class="input-group-text bg-light">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="use_new_sheet" name="use_new_sheet" value="1">
                                        <label class="form-check-label text-secondary small" for="use_new_sheet">
                                            Gunakan link ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Centang <strong>Gunakan link ini</strong> lalu klik <strong>Simpan</strong> untuk mengaktifkan tahun anggaran baru.
                            </small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary px-4">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
