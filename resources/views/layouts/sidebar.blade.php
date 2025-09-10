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
    $userData = session('user_data') ?? [];
    $userRole = $userData['role'] ?? 'user';
    $userUnitKode = $userData['kode_pp'] ?? '';
    $userUnitNama = $userData['nama_pp'] ?? '';

    // semua unit ambil dari Google Sheet (bisa simpan di cache/session)
    $allUnits = [
        'LAB' => 'Bagian Laboratorium',
        'AKA' => 'Bagian Pelayanan Akademik Pusat',
        'INV' => 'Bagian Sentra Inovasi',
        'PPM' => 'Lembaga Penelitian dan Pengabdian Masyarakat',
        'MHS' => 'Bagian Kemahasiswaan',
        'KUG' => 'Bagian Keuangan',
        'LOG' => 'Bagian Logistik Dan Manajemen Aset',
        'SIF' => 'Bagian Sistem Dan Teknologi Informasi',
        'SDM' => 'Bagian Sumber Daya Manusia',
        'CDC' => 'Bagian Alumni Dan Konseling',
        'PSR' => 'Bagian Pemasaran Dan Admisi',
        'DET' => 'CoE Digital Economic, Tourism and Creative Innovation',
        'HWT' => 'CoE Healthcare and Well Being Technologies',
        'ICT' => 'CoE ICT Infrastructure, Smart Manufacture and Digital Supply Chain',
        'SCV' => 'CoE Sustainability Cities, Village and Food Security',
        'DIR' => 'Direktorat Universitas Telkom',
        'HUM' => 'Bagian Humas, Kerjasama, dan KUI',
        'SPM' => 'Bagian Penjaminan Mutu, Perencanaan, dan Pengembangan Pembelajaran',
        'SEK' => 'Bagian Sekretariat Pimpinan, Legal & Internal Audit',
        'BSD' => 'Program Studi S1 Bisnis Digital',
        'RPL' => 'Program Studi S1 Rekayasa Perangkat Lunak',
        'DSA' => 'Program Studi S1 Sains Data',
        'INF' => 'Program Studi S1 Teknik Informatika',
        'DKV' => 'Program Studi S1 Desain Komunikasi Visual',
        'DSP' => 'Program Studi S1 Desain Produk',
        'TTD' => 'Program Studi D3 Teknik Telekomunikasi',
        'SIN' => 'Program Studi S1 Sistem Informasi',
        'TIN' => 'Program Studi S1 Teknik Industri',
        'TLO' => 'Program Studi S1 Teknik Logistik',
        'TPA' => 'Program Studi S1 Teknologi Pangan',
        'BME' => 'Program Studi S1 Teknik Biomedis',
        'TEL' => 'Program Studi S1 Teknik Elektro',
        'TTS' => 'Program Studi S1 Teknik Telekomunikasi',
    ];
@endphp

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-building"></i> Detail Anggaran Unit
    </a>
    <ul class="dropdown-menu">
        @if($userRole === 'admin')
            @foreach($allUnits as $kode => $nama)
                <li><a class="dropdown-item" href="{{ route('unit.show', $kode) }}">{{ $nama }}</a></li>
            @endforeach
        @else
            <li><a class="dropdown-item" href="{{ route('unit.show', $userUnitKode) }}">{{ $userUnitNama }}</a></li>
        @endif
    </ul>
</li>


        {{-- <li class="nav-item dropdown">
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
        </li> --}}

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
