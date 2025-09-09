<div class="sidebar">
    <div class="sidebar-brand">
        <h2><i class="bi bi-clipboard-data me-2"></i>MONITA</h2>
        <p>Monitoring Anggaran</p>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>

        @php
            $userRole = session('user_data.role');
            $userUnit = session('user_data.nama_pp');
            $units = ['Laboratorium', 'Keuangan', 'IT', 'HRD'];
        @endphp

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-building"></i> Detail Anggaran Unit
            </a>
            <ul class="dropdown-menu">
                @foreach($units as $unit)
                    @if($userRole === 'admin' || $userUnit === $unit)
                        <li><a class="dropdown-item" href="{{ route('unit', $unit) }}">{{ $unit }}</a></li>
                    @endif
                @endforeach
            </ul>
        </li>

        @if($userRole === 'admin')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-graph-up"></i> Summary Anggaran
            </a>
            <ul class="dropdown-menu">
                @for ($i = 1; $i <= 4; $i++)
                    <li><a class="dropdown-item" href="{{ route('trivulan', $i) }}">Trivulan {{ $i }}</a></li>
                @endfor
            </ul>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-file-text"></i> Laporan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-gear"></i> Pengaturan</a>
        </li>
    </ul>
</div>
