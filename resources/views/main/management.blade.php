@extends('layouts.app')

@section('title', 'Management Akun — MONITA')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5>Daftar Akun Unit</h5>
                <div class="header-action d-flex align-items-center">
                    <button id="btnExport" class="btn btn-secondary">
                        <i class="ti ti-database-export me-1"></i> Ekspor Data
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Search & Action -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-8 mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" id="search-akun" class="form-control"
                            placeholder="Cari berdasarkan Nama atau Kode PP">
                        <button id="btnSearch" class="btn btn-secondary">
                            <i class="ti ti-search me-1"></i> Cari
                        </button>
                    </div>
                </div>

                <div class="col-md-4 text-md-end">
                    <button class="btn btn-secondary w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalAddAkun">
                        <i class="ti ti-user-plus me-1"></i> Tambah Akun Baru
                    </button>
                </div>
            </div>

            <!-- Tabel akun -->
            <div class="table-responsive card table-primary">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-center">
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
                            <tr data-kode="{{ $u['kode_pp'] }}">
                                <td>{{ $u['no'] }}</td>
                                <td>{{ $u['kode_pp'] }}</td>
                                <td>{{ $u['nama_pp'] }}</td>
                                <td>{{ $u['username'] }}</td>
                                <td>{{ $u['password'] }}</td>
                                <td>{{ $u['role'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-light-primary btn-view"
                                        data-kode="{{ $u['kode_pp'] }}"><i class="ti ti-eye"></i></button>
                                    <button class="btn btn-sm btn-icon btn-light-warning btn-edit"
                                        data-kode="{{ $u['kode_pp'] }}"><i class="ti ti-pencil"></i></button>
                                    <button class="btn btn-sm btn-icon btn-light-secondary btn-delete"
                                        data-kode="{{ $u['kode_pp'] }}"><i class="ti ti-trash"></i></button>
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

    <!-- ================= MODAL SECTION ================= -->

    <!-- Modal Tambah Akun -->
    <div class="modal fade" id="modalAddAkun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formAddAkun" class="modal-content">
                @csrf
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title text-white">Tambah Akun Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode PP</label>
                        <input name="kode_pp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama PP</label>
                        <input name="nama_pp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Akun -->
    <div class="modal fade" id="modalViewAkun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Detail Akun</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Kode PP:</strong> <span id="viewKode"></span></li>
                        <li class="list-group-item"><strong>Nama PP:</strong> <span id="viewNama"></span></li>
                        <li class="list-group-item"><strong>Username:</strong> <span id="viewUsername"></span></li>
                        <li class="list-group-item"><strong>Password:</strong> <span id="viewPassword"></span></li>
                        <li class="list-group-item"><strong>Role:</strong> <span id="viewRole"></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Akun -->
    <div class="modal fade" id="modalEditAkun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEditAkun" class="modal-content">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white">Edit Akun</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editKodeOriginal" name="kode_original">

                    <div class="mb-3">
                        <label class="form-label">Kode PP</label>
                        <input id="editKode" name="kode_pp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama PP</label>
                        <input id="editNama" name="nama_pp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input id="editUsername" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input id="editPassword" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select id="editRole" name="role" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-warning" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Akun -->
    <div class="modal fade" id="modalDeleteAkun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun
                        <strong id="delNamaPP"></strong> (Kode: <span id="delKodePP"></span>)?
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button id="btnConfirmDelete" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= END MODAL SECTION ================= -->

    <!-- Toast placeholder -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Info</strong>
                <small class="text-muted"></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody"></div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.modal.show').forEach(modalEl => {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalInstance.hide();
            });
            // ========== SWEETALERT WRAPPER ==========
            function showAlert(type, title, text) {
                const config = {
                    title: title || '',
                    text: text || '',
                    icon: type || 'info',
                    confirmButtonColor: type === 'success' ? '#28a745' : '#0d6efd',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-3 shadow-lg',
                        confirmButton: 'btn btn-primary mx-1'
                    }
                };
                return Swal.fire(config);
            }

            function showConfirm(options) {
                return Swal.fire({
                    title: options.title || 'Konfirmasi',
                    text: options.text || '',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: options.confirmText || 'Ya',
                    cancelButtonText: options.cancelText || 'Batal',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-3 shadow-lg',
                        confirmButton: 'btn btn-primary mx-1',
                        cancelButton: 'btn btn-secondary mx-1'
                    }
                });
            }

            // ========== SEARCH ==========
            const input = document.getElementById('search-akun');
            const rows = document.querySelectorAll('#akun-list tr');
            input.addEventListener('keyup', function () {
                const val = this.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(val) ? '' : 'none';
                });
            });

            // ========== ADD ACCOUNT ==========
            document.getElementById('formAddAkun').addEventListener('submit', function (e) {
                document.querySelectorAll('.modal.show').forEach(modalEl => {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    modalInstance.hide();
                });
                e.preventDefault();
                const form = new FormData(this);
                fetch("{{ route('management.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: form
                })
                    .then(r => r.json())
                    .then(j => {
                        if (j.success) {
                            showAlert('success', 'Berhasil!', j.message || 'Akun berhasil ditambahkan')
                                .then(() => location.reload());
                        } else {
                            showAlert('error', 'Gagal', j.message || 'Tidak dapat menambahkan akun');
                        }
                    })
                    .catch(() => showAlert('error', 'Error', 'Tidak dapat terhubung ke server.'));
            });

            // ========== VIEW DETAIL ==========
            document.querySelectorAll('.btn-view').forEach(btn => {
                document.querySelectorAll('.modal.show').forEach(modalEl => {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    modalInstance.hide();
                });
                btn.addEventListener('click', () => {
                    const kode = btn.dataset.kode;
                    fetch(`/management/show/${encodeURIComponent(kode)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('viewKode').textContent = data.akun.kode_pp;
                                document.getElementById('viewNama').textContent = data.akun.nama_pp;
                                document.getElementById('viewUsername').textContent = data.akun.username;
                                document.getElementById('viewPassword').textContent = data.akun.password;
                                document.getElementById('viewRole').textContent = data.akun.role;
                                new bootstrap.Modal(document.getElementById('modalViewAkun')).show();
                            } else {
                                showAlert('error', 'Gagal', data.message || 'Data tidak ditemukan');
                            }
                        });
                });
            });

            // ========== EDIT ==========
            document.querySelectorAll('.btn-edit').forEach(btn => {
                document.querySelectorAll('.modal.show').forEach(modalEl => {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    modalInstance.hide();
                });
                btn.addEventListener('click', () => {
                    const kode = btn.dataset.kode;
                    fetch(`/management/show/${encodeURIComponent(kode)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('editKodeOriginal').value = data.akun.kode_pp;
                                document.getElementById('editKode').value = data.akun.kode_pp;
                                document.getElementById('editNama').value = data.akun.nama_pp;
                                document.getElementById('editUsername').value = data.akun.username;
                                document.getElementById('editPassword').value = data.akun.password;
                                document.getElementById('editRole').value = data.akun.role.toLowerCase();
                                new bootstrap.Modal(document.getElementById('modalEditAkun')).show();
                            } else {
                                showAlert('error', 'Gagal', data.message || 'Data tidak ditemukan');
                            }
                        });
                });
            });

            // SUBMIT EDIT
            document.getElementById('formEditAkun').addEventListener('submit', function (e) {
                document.querySelectorAll('.modal.show').forEach(modalEl => {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    modalInstance.hide();
                });
                e.preventDefault();
                const kodeOriginal = document.getElementById('editKodeOriginal').value;
                const form = new FormData(this);

                fetch(`/management/update/${encodeURIComponent(kodeOriginal)}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: form
                })
                    .then(r => r.json())
                    .then(j => {
                        if (j.success) {
                            showAlert('success', 'Berhasil!', 'Perubahan tersimpan')
                                .then(() => location.reload());
                        } else {
                            showAlert('error', 'Gagal', j.message || 'Tidak dapat memperbarui akun');
                        }
                    })
                    .catch(() => showAlert('error', 'Error', 'Tidak dapat terhubung ke server.'));
            });

            // ========== DELETE ==========
            let deleteKode = null;

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', () => {
                    deleteKode = btn.dataset.kode;
                    const namaPP = btn.closest('tr').querySelector('td:nth-child(3)').textContent;

                    showConfirm({
                        title: 'Hapus Akun?',
                        text: `Apakah Anda yakin ingin menghapus akun ${namaPP} (Kode: ${deleteKode})?`,
                        confirmText: 'Ya, Hapus',
                        cancelText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(`/management/delete/${encodeURIComponent(deleteKode)}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            })
                                .then(r => r.json())
                                .then(j => {
                                    if (j.success) {
                                        showAlert('success', 'Berhasil!', 'Akun berhasil dihapus')
                                            .then(() => location.reload());
                                    } else {
                                        showAlert('error', 'Gagal', j.message || 'Penghapusan gagal');
                                    }
                                })
                                .catch(() => showAlert('error', 'Error', 'Tidak dapat menghubungi server.'));
                        }
                    });
                });
            });
        });
    </script>

@endpush