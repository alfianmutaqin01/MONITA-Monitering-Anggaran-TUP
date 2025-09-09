<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MONITA - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- Semua CSS dari kode lama tetap di sini --- */
        :root {
            --primary-color: #951923;
            --secondary-color: #d32f2f;
            --accent-color: #ff6659;
            --dark-color: #1e1e2d;
            --light-color: #f8f9fa;
            --sidebar-width: 280px;
            --header-height: 70px;
            --transition-speed: 0.3s;
            --border-radius: 8px;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7f9;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar { background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color)); color: white; position: fixed; top:0; left:0; width:var(--sidebar-width); height:100vh; padding-top:20px; z-index:1000; box-shadow:var(--box-shadow); transition:all var(--transition-speed) ease; overflow-y:auto; }
        .sidebar-brand { padding: 0 1.5rem 1.5rem; text-align:center; border-bottom:1px solid rgba(255,255,255,.1); margin-bottom:1rem; }
        .sidebar-brand h2 { font-weight:700; font-size:1.8rem; margin:0; color:white; }
        .sidebar-brand p { font-size:.9rem; opacity:.8; margin:0; }
        .nav-item { margin: .5rem 1rem; border-radius: var(--border-radius); transition: all .2s; }
        .nav-item:hover { background-color: rgba(255,255,255,.1); }
        .nav-link { color:white !important; padding:.75rem 1rem; border-radius:var(--border-radius); display:flex; align-items:center; transition: all .2s; }
        .nav-link.active { background-color: rgba(255,255,255,.2); font-weight:600; }
        .nav-link i { margin-right:.75rem; font-size:1.2rem; width:24px; text-align:center; }
        .dropdown-menu { background-color: rgba(0,0,0,.2); border:none; border-radius:var(--border-radius); margin:.25rem 0; padding:.5rem 0; }
        .dropdown-item { color:white; padding:.5rem 1.5rem; }
        .dropdown-item:hover { background-color: rgba(255,255,255,.1); color:white; }

        /* Header */
        .navbar { background-color:white; color: var(--dark-color); height:var(--header-height); box-shadow:0 1px 10px rgba(0,0,0,.1); padding:.5rem 1rem; position:fixed; top:0; left:var(--sidebar-width); right:0; z-index:999; transition:all var(--transition-speed) ease; }
        .navbar-brand { font-weight:700; font-size:1.5rem; color:var(--primary-color) !important; }
        .user-info { display:flex; align-items:center; }
        .user-avatar { width:40px; height:40px; border-radius:50%; background-color:var(--primary-color); color:white; display:flex; align-items:center; justify-content:center; font-weight:bold; margin-right:10px; }

        /* Main content */
        .main-content { margin-left:var(--sidebar-width); padding:calc(var(--header-height)+20px) 30px 30px; min-height:100vh; transition: all var(--transition-speed) ease; }
        .card { border:none; border-radius:var(--border-radius); box-shadow:0 0.15rem 1.75rem 0 rgba(58,59,69,.15); margin-bottom:1.5rem; transition: transform .2s; }
        .card:hover { transform: translateY(-5px); }
        .card-header { background-color:white; border-bottom:1px solid #e3e6f0; padding:1rem 1.35rem; font-weight:600; color:var(--dark-color); border-radius: var(--border-radius) var(--border-radius) 0 0 !important; }
        .card-body { padding:1.35rem; }
        .stat-card { text-align:center; padding:1.5rem; }
        .stat-icon { font-size:2.5rem; margin-bottom:1rem; color:var(--primary-color); }
        .stat-value { font-size:1.8rem; font-weight:700; margin-bottom:.5rem; }
        .stat-label { color:#858796; font-size:.9rem; text-transform:uppercase; letter-spacing:.5px; }
        .btn-monita { background-color:var(--primary-color); border-color:var(--primary-color); color:white; }
        .btn-monita:hover { background-color:var(--secondary-color); border-color:var(--secondary-color); color:white; }
        .table th { border-top:none; font-weight:600; color:#4e73df; background-color:#f8f9fc; }
        @media (max-width:992px) { .sidebar { left:-var(--sidebar-width); } .sidebar.active { left:0; } .navbar { left:0; } .main-content { margin-left:0; } .sidebar-toggler { display:block !important; } }
        .sidebar-toggler { display:none; background:none; border:none; font-size:1.5rem; color:var(--primary-color); cursor:pointer; }
        .metric-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1.5rem; margin-bottom:2rem; }
        .chart-placeholder { background: linear-gradient(45deg,#f8f9fc,#e3e6f0); border-radius:var(--border-radius); height:300px; display:flex; align-items:center; justify-content:center; color:#858796; font-weight:600; }
        .app-footer { background-color:white; padding:1rem; text-align:center; margin-top:2rem; border-top:1px solid #e3e6f0; color:#858796; }
    </style>
</head>
<body>
    @include('layouts.header')
    @include('layouts.sidebar')

    <div class="main-content">
        @yield('content')
        @include('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('.sidebar-toggler').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    document.querySelector('.sidebar').classList.remove('active');
                }
            });
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggler = document.querySelector('.sidebar-toggler');
            if (window.innerWidth < 992 && 
                !sidebar.contains(event.target) && 
                !toggler.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
