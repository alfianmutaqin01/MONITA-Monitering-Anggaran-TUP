<!doctype html>
<html lang="id">

<head>
    <title>@yield('title', 'Dashboard | MONITA')</title>
    <meta charset="utf-8" />
    {{-- PERBAIKAN: Menambahkan minimal-ui untuk konsistensi mobile --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="MONITA - Monitoring Anggaran Telkom University Purwokerto" />
    <meta name="keywords" content="Dashboard, MONITA, Anggaran, Telkom University Purwokerto" />
    <meta name="author" content="MONITA Dev Team" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/material.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('berry/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('berry/assets/css/style-preset.css') }}" />
    <style>
        .modal.fade .modal-dialog {
            transform: translateY(-20px);
            transition: all 0.25s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        .swal2-popup {
            animation: fadeInZoom 0.25s ease-in-out;
        }

        @keyframes fadeInZoom {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* CSS Anchor Tambahan: Memastikan layout fluid */
        .pc-container, .pc-content {
            min-width: 100%; 
            box-sizing: border-box;
        }
        @media (max-width: 991.98px) {
             /* Pastikan sidebar ter-trigger di mobile */
            .pc-container.pc-sidebar-active {
                overflow: hidden;
            }
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    
    {{-- PERBAIKAN: Memindahkan loading.js ke body setelah loader agar elemen dom dimuat --}}
    <script src="{{ asset('js/monita/loading.js') }}"></script> 

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Header -->
    @include('layouts.header')

    <!-- Content -->
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Vendor JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('berry/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('berry/assets/js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('berry/assets/js/plugins/feather.min.js') }}"></script>

    <!-- Core JS -->
    <script src="{{ asset('berry/assets/js/theme.js') }}"></script>
    <script src="{{ asset('berry/assets/js/script.js') }}"></script>

    <!-- Page-specific JS -->
    @stack('scripts')

    {{-- PERBAIKAN: Mengganti script inisialisasi yang berulang dengan feather.replace() --}}
    <script>
        if (typeof layout_change === 'function') layout_change('light');
        if (typeof font_change === 'function') font_change('Roboto');
        if (typeof change_box_container === 'function') change_box_container('false'); // Memastikan Layout Fluid
        if (typeof layout_caption_change === 'function') layout_caption_change('true');
        if (typeof layout_rtl_change === 'function') layout_rtl_change('false');
        if (typeof preset_change === 'function') preset_change('preset-1');

        feather.replace();
    </script>
</body>

</html>
