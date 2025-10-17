@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
            <h5 class="mb-0  text-white">Pengaturan Tahun Anggaran</h5>
        </div>
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Peringatan:</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="card-body">

            {{-- Panduan --}}
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

            {{-- Form tambah atau ubah link spreadsheet --}}
            <form action="{{ route('settings.update') }}" method="POST" class="mb-4">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Tahun Anggaran</label>
                        <input type="number" name="year" class="form-control" placeholder="2025" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Link Google Sheet</label>
                        <input type="url" name="sheet_link" class="form-control"
                            placeholder="https://docs.google.com/spreadsheets/d/..." required>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="ti ti-plus me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Daftar Spreadsheet Tahun --}}
            <h6 class="fw-bold mb-3">Daftar Spreadsheet Aktif</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 80px;">Aktif</th>
                            <th>Tahun</th>
                            <th>ID Spreadsheet</th>
                            <th>Pratinjau</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sheetYears as $year => $id)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input checkbox-active" data-year="{{ $year }}"
                                        data-key="{{ $id }}" {{ env('ACTIVE_YEAR') == $year ? 'checked' : '' }}>
                                </td>
                                <td>{{ $year }}</td>
                                <td><code>{{ $id }}</code></td>
                                <td>
                                    <a href="https://docs.google.com/spreadsheets/d/{{ $id }}" target="_blank">
                                        Lihat Spreadsheet
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data spreadsheet tersimpan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.checkbox-active').forEach(chk => {
                chk.addEventListener('change', function () {
                    const year = this.dataset.year;
                    const sheetKey = this.dataset.key;

                    // pastikan hanya satu checkbox aktif
                    document.querySelectorAll('.checkbox-active').forEach(cb => {
                        if (cb !== this) cb.checked = false;
                    });

                    if (this.checked) {
                        Swal.fire({
                            title: "Konfirmasi",
                            text: `Jadikan tahun ${year} sebagai tahun aktif?`,
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Ya, aktifkan",
                            cancelButtonText: "Batal",
                            confirmButtonColor: "#0d6efd",
                            cancelButtonColor: "#6c757d",
                            background: "#fff",
                            customClass: {
                                popup: 'rounded-3 shadow-lg',
                                confirmButton: 'btn btn-primary mx-1',
                                cancelButton: 'btn btn-secondary mx-1'
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                fetch("{{ route('settings.update') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({
                                        active_year: year,
                                        spreadsheet_key: sheetKey
                                    })
                                })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire({
                                                title: "Berhasil!",
                                                text: "Tahun aktif telah diperbarui.",
                                                icon: "success",
                                                confirmButtonColor: "#28a745",
                                                timer: 2000,
                                                showConfirmButton: false
                                            }).then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire("Gagal", data.message || "Terjadi kesalahan.", "error");
                                        }
                                    })
                                    .catch(() => {
                                        Swal.fire("Error", "Tidak dapat terhubung ke server.", "error");
                                    });
                            } else {
                                this.checked = false;
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush