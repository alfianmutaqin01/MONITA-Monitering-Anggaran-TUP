<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container-fluid">
        <button class="sidebar-toggler" type="button">
            <i class="bi bi-list"></i>
        </button>
        <span class="navbar-brand mb-0 h1">MONITA System</span>
        <div class="d-flex align-items-center">
            <div class="user-info me-4">
                <div class="user-avatar">
                    {{ substr(session('user_data.nama_pp') ?? session('user_data.username'), 0, 1) }}
                </div>
                <div class="d-none d-md-block">
                    <div class="fw-bold">{{ session('user_data.nama_pp') ?? session('user_data.username') }}</div>
                    <div class="small text-muted">{{ session('user_data.role') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>
