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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Modal Tambah Akun Baru -->
                <div class="modal fade" id="modalTambahAkun" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h5 class="modal-title">Tambah Akun Baru</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form id="formTambahAkun">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Kode PP</label>
                                        <input type="text" name="kode_pp" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama PP</label>
                                        <input type="text" name="nama_pp" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="text" name="password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select" required>
                                            <option value="user" selected>User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-secondary w-100">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Action -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-8 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" id="search-akun" class="form-control"
                                placeholder="Cari berdasarkan Nama atau Kode PP">
                            <button class="btn btn-secondary">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 text-md-end">
                        <button class="btn btn-secondary w-100 w-md-auto" data-bs-toggle="modal"
                            data-bs-target="#modalTambahAkun">
                            <i class="ti ti-user-plus me-1"></i> Tambah Akun Baru
                        </button>
                    </div>
                </div>

                <!-- Tabel akun -->
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
                        <tbody id="akun-list">
                            @forelse($users as $u)
                                <tr>
                                    <td>{{ $u['no'] }}</td>
                                    <td>{{ $u['kode_pp'] }}</td>
                                    <td>{{ $u['nama_pp'] }}</td>
                                    <td>{{ $u['username'] }}</td>
                                    <td>{{ $u['password'] }}</td>
                                    <td>{{ $u['role'] }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                        <button class="btn btn-sm btn-icon btn-light-warning"><i
                                                class="ti ti-pencil"></i></button>
                                        <button class="btn btn-sm btn-icon btn-light-secondary"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // --- SEARCH ---
                const input = document.getElementById('search-akun');
                const rows = document.querySelectorAll('#akun-list tr');
                input.addEventListener('keyup', function () {
                    const val = this.value.toLowerCase();
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });

                // --- TAMBAH AKUN ---
                const formTambah = document.getElementById('formTambahAkun');
                formTambah.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch("{{ route('management.store') }}", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Tambahkan baris baru ke tabel
                                const newRow = `
                            <tr>
                                <td>${data.no}</td>
                                <td>${data.kode}</td>
                                <td>${data.nama}</td>
                                <td>${data.username}</td>
                                <td>${data.password}</td>
                                <td>${data.role}</td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-light-primary"><i class="ti ti-eye"></i></button>
                                    <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                    <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                                </td>
                            </tr>
                        `;
                                document.querySelector('#akun-list').insertAdjacentHTML('beforeend', newRow);

                                // Reset form & tutup modal
                                formTambah.reset();
                                const modalEl = document.getElementById('modalTambahAkun');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                modal.hide();

                                // Tampilkan alert sukses
                                alert('✅ Akun baru berhasil ditambahkan!');
                            } else {
                                alert('❌ Gagal menambahkan akun.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan saat menambah akun.');
                        });
                });
            });
        </script>
    @endpush


@endsection