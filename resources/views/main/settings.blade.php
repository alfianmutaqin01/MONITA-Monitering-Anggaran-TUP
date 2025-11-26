@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Peringatan:</strong> {!! session('warning') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses:</strong> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Info:</strong> {!! session('info') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Modal Konfirmasi Timpa Data --}}
    <div class="modal fade" id="overrideModal" tabindex="-1" aria-labelledby="overrideModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark" id="overrideModalLabel">
                        <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Penimpaan Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $pendingData = session('pending_spreadsheet');
                    @endphp

                    @if($pendingData)
                        <p class="mb-3">Tahun <strong>{{ $pendingData['year'] }}</strong> sudah memiliki data spreadsheet
                            tersimpan.</p>

                        <div class="alert alert-warning">
                            <small>
                                <strong>Data yang akan ditimpa:</strong><br>
                                Tahun: {{ $pendingData['year'] }}<br>
                                Link Baru: <code>{{ $pendingData['link'] }}</code>
                            </small>
                        </div>

                        <p class="text-danger mb-0">
                            <strong>Apakah Anda yakin ingin menimpa ID Spreadsheet untuk tahun ini?</strong>
                        </p>
                    @else
                        <p>Data konfirmasi tidak tersedia.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <form action="{{ route('settings.update') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="cancel_override" value="1">
                        <button type="submit" class="btn btn-secondary">
                            <i class="ti ti-x me-1"></i> Batal
                        </button>
                    </form>

                    @if($pendingData)
                        <form action="{{ route('settings.update') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="confirm_override" value="1">
                            <input type="hidden" name="override_year" value="{{ $pendingData['year'] }}">
                            <input type="hidden" name="override_key" value="{{ $pendingData['key'] }}">
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-check me-1"></i> Ya, Timpa Data
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Card 1: Pengaturan Spreadsheet --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary">
            <h5 class="mb-0 text-white">1. Pengaturan Tahun Anggaran (Google Sheets)</h5>
        </div>
        <div class="card-body">
            {{-- Panduan --}}
            <div class="alert alert-info">
                <h6 class="fw-bold mb-2"><i class="ti ti-info-circle me-2"></i>Panduan Menghubungkan Spreadsheet:</h6>
                <ol class="mb-2">
                    <li>Buka file <strong>Google Sheets</strong> tahun anggaran yang ingin dikelola.</li>
                    <li>Klik tombol <strong>Bagikan</strong> di kanan atas.</li>
                    <li>
                        Tambahkan akun berikut sebagai editor:<br>
                        <code id="service-account-email">keuangan-tup@monita-471208.iam.gserviceaccount.com</code>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1 copy-btn"
                            data-copy-target="service-account-email" title="Salin ke Clipboard"
                            style="border-radius: 0.25rem;">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </li>

                    <li>Pastikan izin akses diset ke <strong>Editor</strong>.</li>
                    <li>Salin <strong>link</strong> spreadsheet tersebut, lalu tempelkan ke form di bawah.</li>
                </ol>
            </div>

            {{-- Form tambah atau ubah link spreadsheet --}}
            <h6 class="fw-bold mb-3">Tambah / Ubah Data Spreadsheet</h6>
            <form action="{{ route('settings.update') }}" method="POST" id="form-spreadsheet"
                class="mb-5 p-3 border rounded-3 bg-light">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Tahun Anggaran</label>
                        <input type="number" name="year" id="input-year" class="form-control" placeholder="2026" min="2020"
                            max="2030" required value="{{ old('year') }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Link Google Sheet</label>
                        <input type="url" name="sheet_link" id="input-sheet-link" class="form-control"
                            placeholder="https://docs.google.com/spreadsheets/d/..." required
                            value="{{ old('sheet_link') }}">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-secondary w-100 mt-3 mt-md-0">
                            <i class="ti ti-plus me-1"></i> Simpan
                        </button>
                    </div>
                </div>

                @error('year')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                @error('sheet_link')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </form>

            {{-- Daftar Spreadsheet Tahun --}}
            <h6 class="fw-bold mb-3">Daftar Spreadsheet Tersimpan</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-hover" id="spreadsheet-table">
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
                            <tr class="{{ $activeYear == $year ? 'table-primary fw-bold' : '' }}" data-year="{{ $year }}">
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input type="radio" name="active_year" class="form-check-input radio-active" 
                                               data-year="{{ $year }}" data-key="{{ $id }}" 
                                               {{ $activeYear == $year ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="text-dark">{{ $year }}</td>
                                <td><code class="text-dark">{{ $id }}</code></td>
                                <td>
                                    <a href="https://docs.google.com/spreadsheets/d/{{ $id }}" target="_blank"
                                        class="btn btn-sm btn-outline-secondary">
                                        Lihat Spreadsheet <i class="ti ti-external-link ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="ti ti-table-off me-2"></i>Belum ada data spreadsheet tersimpan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Card 2: Pengaturan Penanda Tangan (TTD) --}}
    <div class="card">
        <div class="card-header bg-secondary">
            <h5 class="mb-0 text-white">2. Pengaturan Data Penanda Tangan Laporan (TTD)</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">Data ini akan digunakan untuk mencetak nama dan NIP pada footer laporan PDF.</p>

            <form action="{{ route('settings.update') }}" method="POST" id="form-ttd">
                @csrf

                <div class="row">
                    {{-- TTD Kolom 1 (Kiri) --}}
                    <div class="col-md-6 border-end pe-4">
                        <h6 class="fw-semibold text-secondary mb-3">Penanda Tangan 1 (Kiri/Mengetahui)</h6>
                        <div class="mb-3">
                            <label class="form-label">Jabatan TTD 1</label>
                            <input type="text" id="jabatan_1" class="form-control"
                                value="{{ $ttdData['ttd_jabatan_1'] ?? '' }}" placeholder="Contoh: Kepala Bagian">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap TTD 1</label>
                            <input type="text" id="nama_1" class="form-control" value="{{ $ttdData['ttd_nama_1'] ?? '' }}"
                                placeholder="Nama lengkap penanda tangan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIP TTD 1</label>
                            <input type="text" id="nip_1" class="form-control" value="{{ $ttdData['ttd_nip_1'] ?? '' }}"
                                placeholder="Nomor Induk Pegawai">
                        </div>
                    </div>

                    {{-- TTD Kolom 2 (Kanan) --}}
                    <div class="col-md-6 ps-4">
                        <h6 class="fw-semibold text-primary mb-3">Penanda Tangan 2 (Kanan/Pembuat)</h6>
                        <div class="mb-3">
                            <label class="form-label">Jabatan TTD 2</label>
                            <input type="text" id="jabatan_2" class="form-control"
                                value="{{ $ttdData['ttd_jabatan_2'] ?? '' }}" placeholder="Contoh: Bendahara Pengeluaran">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap TTD 2</label>
                            <input type="text" id="nama_2" class="form-control" value="{{ $ttdData['ttd_nama_2'] ?? '' }}"
                                placeholder="Nama lengkap penanda tangan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIP TTD 2</label>
                            <input type="text" id="nip_2" class="form-control" value="{{ $ttdData['ttd_nip_2'] ?? '' }}"
                                placeholder="Nomor Induk Pegawai">
                        </div>
                    </div>
                </div>

                {{-- Hidden inputs untuk data TTD --}}
                <input type="hidden" name="ttd_jabatan_1_input" value="">
                <input type="hidden" name="ttd_nama_1_input" value="">
                <input type="hidden" name="ttd_nip_1_input" value="">
                <input type="hidden" name="ttd_jabatan_2_input" value="">
                <input type="hidden" name="ttd_nama_2_input" value="">
                <input type="hidden" name="ttd_nip_2_input" value="">

                <div class="text-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-secondary px-4" id="btn-save-ttd">
                        <i class="ti ti-save me-1"></i> Simpan Pengaturan TTD
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header bg-secondary">
            <h5 class="mb-0 text-white">3. Aktivitas Login Pengguna</h5>
        </div>
        <div class="card-body">

            <table class="table table-bordered table-hover" id="login-activity-table">
                <thead class="bg-light">
                    <tr>
                        <th>Waktu</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>IP</th>
                        <th>Browser</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loginLogs as $log)
                        <tr>
                            <td>{{ $log->login_time }}</td>
                            <td>{{ $log->username }}</td>
                            <td>{{ $log->role }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ Str::limit($log->user_agent, 30) }}</td>
                            <td>
                                @if ($log->status == 'success')
                                    <span class="badge bg-success">Berhasil</span>
                                @else
                                    <span class="badge bg-secondary text-white">Gagal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-copy-target');
                    const text = document.getElementById(targetId)?.innerText || '';

                    if (!text) return;

                    navigator.clipboard.writeText(text).then(() => {
                        // Ganti icon sementara jadi centang
                        this.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                        this.title = "Tersalin!";
                        setTimeout(() => {
                            this.innerHTML = '<i class="bi bi-clipboard"></i>';
                            this.title = "Salin ke Clipboard";
                        }, 1500);
                    }).catch(err => {
                        console.error("Gagal menyalin:", err);
                        alert("Gagal menyalin teks!");
                    });
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const existingYears = @json($existingYears ?? []);
            const activeYear = "{{ $activeYear ?? '' }}";

            // Tampilkan modal konfirmasi jika diperlukan
            @if(session('show_override_modal'))
                const overrideModal = new bootstrap.Modal(document.getElementById('overrideModal'));
                overrideModal.show();
            @endif


            // LOGIKA 1: KONFIRMASI AKTIVASI TAHUN (RADIO BUTTON)

            document.querySelectorAll('.radio-active').forEach(radio => {
                radio.addEventListener('change', function () {
                    const year = this.dataset.year;
                    const sheetKey = this.dataset.key;

                    // Jika memilih tahun yang sama dengan aktif, tidak perlu konfirmasi
                    if (year === activeYear) {
                        return;
                    }

                    Swal.fire({
                        title: "Konfirmasi Aktivasi Tahun",
                        text: `Jadikan tahun ${year} sebagai tahun anggaran aktif?`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Aktifkan",
                        cancelButtonText: "Batal",
                        confirmButtonColor: "#0d6efd",
                        cancelButtonColor: "#6c757d",
                        customClass: { popup: 'rounded-3 shadow-lg' }
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
                                            text: data.message || "Tahun aktif telah diperbarui.",
                                            icon: "success",
                                            confirmButtonColor: "#28a745",
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire("Gagal", data.message || "Terjadi kesalahan.", "error");
                                        // Kembalikan ke radio sebelumnya
                                        document.querySelector(`.radio-active[data-year="${activeYear}"]`).checked = true;
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    Swal.fire("Error", "Tidak dapat terhubung ke server.", "error");
                                    document.querySelector(`.radio-active[data-year="${activeYear}"]`).checked = true;
                                });
                        } else {
                            // Kembalikan ke radio tahun aktif sebelumnya
                            this.checked = false;
                            document.querySelector(`.radio-active[data-year="${activeYear}"]`).checked = true;
                        }
                    });
                });
            });


            // LOGIKA 2: DETEKSI DUPLIKASI TAHUN PADA FORM SUBMIT

            document.getElementById('form-spreadsheet').addEventListener('submit', function (e) {
                const yearInput = document.getElementById('input-year').value.trim();

                // Deteksi jika tahun sudah ada
                if (existingYears.includes(yearInput)) {
                    e.preventDefault();

                    Swal.fire({
                        title: "Tahun Sudah Ada",
                        text: `Tahun ${yearInput} sudah memiliki data spreadsheet. Anda akan menimpa data yang ada.`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Timpa Data",
                        cancelButtonText: "Batal",
                        confirmButtonColor: "#dc3545",
                        customClass: { popup: 'rounded-3 shadow-lg' }
                    }).then(result => {
                        if (result.isConfirmed) {
                            // Tambahkan parameter konfirmasi dan submit form
                            const confirmInput = document.createElement('input');
                            confirmInput.type = 'hidden';
                            confirmInput.name = 'confirmed_override';
                            confirmInput.value = '1';
                            e.target.appendChild(confirmInput);
                            e.target.submit();
                        }
                    });
                }
            });


            // LOGIKA 3: SIMPAN DATA TTD

            document.getElementById('form-ttd').addEventListener('submit', function (e) {
                e.preventDefault(); //BARU: Mencegah submit form standar (penting)

                // Transfer nilai dari input visible ke hidden inputs
                const mappings = [
                    ['jabatan_1', 'ttd_jabatan_1_input'],
                    ['nama_1', 'ttd_nama_1_input'],
                    ['nip_1', 'ttd_nip_1_input'],
                    ['jabatan_2', 'ttd_jabatan_2_input'],
                    ['nama_2', 'ttd_nama_2_input'],
                    ['nip_2', 'ttd_nip_2_input']
                ];

                mappings.forEach(([visibleId, hiddenName]) => {
                    const visibleValue = document.getElementById(visibleId)?.value || '';
                    document.querySelector(`input[name="${hiddenName}"]`).value = visibleValue;
                });

                const formData = new FormData(this); // Mengambil data form yang sudah di-update hidden inputnya

                fetch("{{ route('settings.update') }}", {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json" // Minta JSON response
                    },
                    body: formData
                })
                    .then(r => {
                        if (!r.ok) {
                            // Handle error response (termasuk validasi 422)
                            return r.json().then(data => Promise.reject(data.message || 'Gagal menyimpan TTD.'));
                        }
                        return r.json();
                    })
                    .then(j => {
                        // Jika sukses, tampilkan SweetAlert dan reload
                        Swal.fire({
                            title: "Berhasil!",
                            text: j.message || "Pengaturan TTD berhasil disimpan.",
                            icon: "success",
                            confirmButtonColor: "#28a745",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload(); // Memaksa reload halaman
                        });
                    })
                    .catch(error => {
                        Swal.fire("Error", error.toString(), "error");
                    });
            });


        });
    </script>
@endpush