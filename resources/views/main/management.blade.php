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
        <!-- Search & Action -->
        <div class="row mb-3 align-items-center">
            <div class="col-md-8 mb-2 mb-md-0">
                <div class="input-group">
                    <input type="text" id="search-akun" class="form-control" placeholder="Cari berdasarkan Nama atau Kode PP">
                    <button class="btn btn-secondary">
                        <i class="ti ti-search me-1"></i> Cari
                    </button>
                </div>
            </div>

            <div class="col-md-4 text-md-end">
                <button class="btn btn-secondary w-100 w-md-auto">
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
                                <button class="btn btn-sm btn-icon btn-light-warning"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-sm btn-icon btn-light-secondary"><i class="ti ti-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('search-akun');
    const rows = document.querySelectorAll('#akun-list tr');

    input.addEventListener('keyup', function(){
        const val = this.value.toLowerCase();
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
});
</script>
@endpush

@endsection
