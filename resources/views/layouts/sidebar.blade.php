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

                    <ul class="pc-submenu" id="unit-list">
                        @if($userRole === 'admin')
                            <li class="pc-item p-2 text-end">
                                <input type="text" id="search-unit" class="form-control form-control-sm ms-5"
                                    placeholder="Cari unit..." style="width: 70%; font-size: 0.85rem; border-radius: 6px;">
                            </li>

                            <li class="pc-item">
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <ul class="list-unstyled mb-0" id="units-container">
                                        @foreach($allUnits as $kode => $nama)
                                            <li class="pc-item unit-item" data-kode="{{ $kode }}" data-nama="{{ $nama }}">
                                                <a class="pc-link" href="{{ route('unit.show', $kode) }}">
                                                    <strong>{{ $kode }}</strong> - {{ $nama }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @else
                            <li class="pc-item">
                                @if(isset($allUnits[$userUnitKode]))
                                    <a class="pc-link" href="{{ route('unit.show', $userUnitKode) }}">
                                        <strong>{{ $userUnitKode }}</strong> - {{ $allUnits[$userUnitKode] }}
                                    </a>
                                @else
                                    <a class="pc-link" href="{{ route('unit.show', $userUnitKode) }}">
                                        {{ $userUnitNama }}
                                    </a>
                                @endif
                            </li>
                        @endif
                    </ul>
                </li>

                <!-- Summary Anggaran -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-report-money"></i></span>
                        <span class="pc-mtext">Summary Anggaran</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @for ($i = 1; $i <= 4; $i++)
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('summary.triwulan', $i) }}">Triwulan {{ $i }}</a>
                            </li>
                        @endfor

                    </ul>
                </li>

                <!-- Laporan -->
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                        <span class="pc-mtext">Laporan</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @for ($i = 1; $i <= 4; $i++)
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('laporan.triwulan', $i) }}">
                                    Triwulan {{ $i }}
                                </a>
                            </li>
                        @endfor
                    </ul>
                </li>

                @if($userRole === 'admin')
                    <!-- management akun -->
                    <li class="pc-item">
                        <a class="pc-link {{ request()->routeIs('management') ? 'active' : '' }}"
                            href="{{ route('management') }}">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Management Akun</span>
                        </a>
                    </li>

                    <!-- pengaturan -->
                    <li class="pc-item">
                        <a class="pc-link {{ request()->routeIs('settings.index') ? 'active' : '' }}"
                            href="{{ route('settings.index') }}">
                            <span class="pc-micon"><i class="ti ti-settings"></i></span>
                            <span class="pc-mtext">Pengaturan</span>
                        </a>
                    </li>

                @endif


            </ul>


            <!-- Footer card -->
            <div class="w-100 text-center mt-3">
                <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-unit');
            const unitItems = document.querySelectorAll('#unit-list .unit-item');

            if (searchInput) {
                searchInput.addEventListener('keyup', function () {
                    const keyword = this.value.toLowerCase();
                    unitItems.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        // tampilkan/hilangkan item sesuai keyword
                        item.style.display = text.includes(keyword) ? '' : 'none';
                    });
                });
            }
        });
    </script>
@endpush