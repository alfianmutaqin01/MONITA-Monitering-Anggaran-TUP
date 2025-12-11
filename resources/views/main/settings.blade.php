@extends('layouts.app')

@section('content')
    {{-- Notifikasi Error PHP standar (hanya untuk saat load awal jika ada error sistem) --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
            {{-- Hapus action/method, gunakan ID untuk JS --}}
            <form id="form-spreadsheet" class="mb-5 p-3 border rounded-3 bg-light">
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Tahun Anggaran</label>
                        <input type="number" id="input-year" class="form-control" placeholder="2026" min="2020" max="2030" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Link Google Sheet</label>
                        <input type="url" id="input-sheet-link" class="form-control" placeholder="https://docs.google.com/spreadsheets/d/..." required>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-secondary w-100 mt-3 mt-md-0">
                            <i class="ti ti-plus me-1"></i> Simpan
                        </button>
                    </div>
                </div>
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
    <div class="card mb-4">
        <div class="card-header bg-secondary">
            <h5 class="mb-0 text-white">2. Pengaturan Data Penanda Tangan Laporan (TTD)</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">Data ini akan digunakan untuk mencetak nama dan NIP pada footer laporan PDF.</p>

            <form id="form-ttd">
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

                <div class="text-end mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-secondary px-4" id="btn-save-ttd">
                        <i class="ti ti-save me-1"></i> Simpan Pengaturan TTD
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Card 3: Aktivitas Login --}}
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
    
    // --- BAGIAN 1: SIMPAN / UPDATE SPREADSHEET (AJAX) ---
    const formSheet = document.getElementById('form-spreadsheet');
    
    if(formSheet){
        formSheet.addEventListener('submit', function(e) {
            e.preventDefault(); // STOP RELOAD
            
            // Ambil data dari input
            const year = document.getElementById('input-year').value;
            const link = document.getElementById('input-sheet-link').value;
            const btn = formSheet.querySelector('button[type="submit"]');
            
            sendSpreadsheetData(year, link, false, btn);
        });
    }

    function sendSpreadsheetData(year, link, confirmed = false, btnElement) {
        // Loading State
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Proses...';
        btnElement.disabled = true;

        fetch("{{ route('settings.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                year: year,
                sheet_link: link,
                confirmed_override: confirmed ? '1' : '0'
            })
        })
        .then(res => res.json())
        .then(data => {
            btnElement.innerHTML = originalText;
            btnElement.disabled = false;

            if (data.success) {
                // SUKSES
                Swal.fire({
                    title: "Berhasil!",
                    text: data.message,
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });

                // Update Tabel HTML secara Manual (Tanpa Reload)
                updateTableDOM(data.data);
                
                // Reset Form
                document.getElementById('input-year').value = '';
                document.getElementById('input-sheet-link').value = '';

            } else if (data.needs_override) {
                // KONFIRMASI TIMPA DATA (SweetAlert)
                Swal.fire({
                    title: "Tahun Sudah Ada",
                    text: `Tahun ${data.year} sudah tersimpan. Apakah Anda ingin menimpanya dengan ID baru?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ffc107",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, Timpa!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Panggil ulang fungsi dengan konfirmasi
                        sendSpreadsheetData(year, link, true, btnElement);
                    }
                });

            } else {
                Swal.fire("Gagal", data.message, "error");
            }
        })
        .catch(err => {
            btnElement.innerHTML = originalText;
            btnElement.disabled = false;
            console.error(err);
            Swal.fire("Error", "Terjadi kesalahan server.", "error");
        });
    }

    function updateTableDOM(data) {
        const tableBody = document.querySelector('#spreadsheet-table tbody');
        const rows = tableBody.querySelectorAll('tr');
        let found = false;

        // Cek update baris yang ada
        rows.forEach(row => {
            if (row.dataset.year == data.year) {
                row.cells[2].innerHTML = `<code class="text-dark">${data.key}</code>`;
                row.cells[3].querySelector('a').href = data.link;
                
                const radio = row.querySelector('.radio-active');
                if(radio) radio.dataset.key = data.key;
                
                row.classList.add('table-warning');
                setTimeout(() => row.classList.remove('table-warning'), 2000);
                found = true;
            }
        });

        // Tambah baris baru jika belum ada
        if (!found) {
            const emptyRow = tableBody.querySelector('td[colspan="4"]');
            if (emptyRow) emptyRow.parentElement.remove();

            const newRow = `
                <tr data-year="${data.year}">
                    <td class="text-center">
                        <div class="form-check d-flex justify-content-center">
                            <input type="radio" name="active_year" class="form-check-input radio-active" 
                                data-year="${data.year}" data-key="${data.key}">
                        </div>
                    </td>
                    <td class="text-dark">${data.year}</td>
                    <td><code class="text-dark">${data.key}</code></td>
                    <td>
                        <a href="${data.link}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            Lihat Spreadsheet <i class="ti ti-external-link ms-1"></i>
                        </a>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
        }
    }


    // --- BAGIAN 2: SIMPAN TTD (AJAX) ---
    const btnSaveTTD = document.getElementById('btn-save-ttd');
    
    if(btnSaveTTD){
        btnSaveTTD.addEventListener('click', function(e) {
            e.preventDefault();
            
            const payload = {
                ttd_jabatan_1: document.getElementById('jabatan_1').value,
                ttd_nama_1: document.getElementById('nama_1').value,
                ttd_nip_1: document.getElementById('nip_1').value,
                ttd_jabatan_2: document.getElementById('jabatan_2').value,
                ttd_nama_2: document.getElementById('nama_2').value,
                ttd_nip_2: document.getElementById('nip_2').value,
            };

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menyimpan...';
            this.disabled = true;

            fetch("{{ route('settings.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                this.innerHTML = originalText;
                this.disabled = false;
                
                if (data.success) {
                    Swal.fire({
                        title: "Tersimpan!",
                        text: data.message,
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire("Gagal", data.message, "error");
                }
            })
            .catch(err => {
                this.innerHTML = originalText;
                this.disabled = false;
                Swal.fire("Error", "Gagal menghubungi server", "error");
            });
        });
    }


    // --- BAGIAN 3: AKTIVASI TAHUN (AJAX - TANPA RELOAD) ---
    // Event Delegation
    const tableElement = document.getElementById('spreadsheet-table');
    if(tableElement){
        tableElement.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('radio-active')) {
                const radio = e.target;
                const year = radio.dataset.year;
                const sheetKey = radio.dataset.key;
                
                Swal.fire({
                    title: "Ganti Tahun Aktif?",
                    text: `Aktifkan tahun anggaran ${year}?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Aktifkan",
                    cancelButtonText: "Batal"
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch("{{ route('settings.update') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ active_year: year, spreadsheet_key: sheetKey })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Sukses", 
                                    text: data.message, 
                                    icon: "success", 
                                    timer: 1500, 
                                    showConfirmButton: false
                                });

                                // --- LOGIC VISUAL UPDATE (PENTING: MENGGANTIKAN RELOAD) ---
                                // 1. Hapus style aktif dari semua baris
                                document.querySelectorAll('#spreadsheet-table tr').forEach(tr => {
                                    tr.classList.remove('table-primary', 'fw-bold');
                                });
                                // 2. Tambahkan style aktif ke baris yang dipilih
                                radio.closest('tr').classList.add('table-primary', 'fw-bold');
                                // ---------------------------------------------------------

                            } else {
                                Swal.fire("Gagal", data.message, "error");
                                e.target.checked = false; // Reset radio jika gagal
                            }
                        })
                        .catch(err => {
                             console.error(err);
                             Swal.fire("Error", "Gagal koneksi server", "error");
                             e.target.checked = false;
                        });
                    } else {
                        // User membatalkan di SweetAlert
                        e.target.checked = false; 
                        // Opsi tambahan: Centang kembali radio button tahun yang sebelumnya aktif (jika perlu logika kompleks)
                        // Untuk sekarang, uncheck cukup menandakan batal.
                    }
                });
            }
        });
    }

    // --- BAGIAN 4: COPY TEXT HELPER ---
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-copy-target');
            const text = document.getElementById(targetId)?.innerText || '';
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                setTimeout(() => { this.innerHTML = originalHtml; }, 1500);
            });
        });
    });

});
</script>
@endpush