<!doctype html>
<html lang="id">

<head>
    <title>@yield('title', 'Dashboard | MONITA')</title>
    <meta charset="utf-8" />
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
        /* .pc-sidebar .nav-link.active {
            background-color: #f0f0f0;
            color: #212529;
            border-radius: 8px;
        } */
         
    </style>
</head>

<body>
    <!-- Loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

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

    <script>
        // Default layout settings
        layout_change('light');
        font_change('Roboto');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        feather.replace();
    </script>
</body>

</html>
