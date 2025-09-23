<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <!-- Logo -->
        <div class="m-header">
            {{-- <img src="{{ asset('public/images/Logo.png') }}" alt="Logo" class="logo logo-lg" /> --}}
            <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                <img src="{{ asset('images/icon.png') }}" alt="Logo" class="logo logo-lg" />
            </a>
        </div>

        <div class="navbar-content">
            <ul class="pc-navbar">
                <!-- Dashboard -->
                <li class="pc-item pc-caption">
                    <label>Dashboard</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item">
                    <a href="{{ route('dashboard') }}"
                        class="pc-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <!-- Detail Anggaran Unit -->
                <li class="pc-item pc-caption">
                    <label>Anggaran</label>
                    <i class="ti ti-building"></i>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-building"></i></span>
                        <span class="pc-mtext">Detail Anggaran Unit</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @if($userRole === 'admin')
                            @foreach($allUnits as $kode => $nama)
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('unit.show', $kode) }}">{{ $nama }}</a>
                                </li>
                            @endforeach
                        @else
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('unit.show', $userUnitKode) }}">{{ $userUnitNama }}</a>
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Summary Anggaran -->
                @if($userRole === 'admin')
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-report-money"></i></span>
                            <span class="pc-mtext">Summary Anggaran</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            @for ($i = 1; $i <= 4; $i++)
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('trivulan', $i) }}">Trivulan {{ $i }}</a>
                                </li>
                            @endfor
                        </ul>
                    </li>
                @endif

                <!-- Laporan -->
                <li class="pc-item">
                    <a class="pc-link {{ request()->routeIs('laporan') ? 'active' : '' }}"
                        href="{{ route('laporan') }}">
                        <span class="pc-micon"><i class="ti ti-file"></i></span>
                        <span class="pc-mtext">Laporan</span>
                    </a>
                </li>

                <!-- Pengaturan -->
                <li class="pc-item">
                    <a class="pc-link {{ request()->routeIs('pengaturan') ? 'active' : '' }}"
                        href="{{ route('pengaturan') }}">
                        <span class="pc-micon"><i class="ti ti-settings"></i></span>
                        <span class="pc-mtext">Pengaturan Akun</span>
                    </a>
                </li>
            </ul>

            <!-- Footer card -->
            <div class="w-100 text-center mt-3">
                <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
            </div>
        </div>
    </div>
</nav>