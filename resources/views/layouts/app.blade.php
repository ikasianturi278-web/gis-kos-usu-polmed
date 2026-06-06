<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosanMaps - @yield('title', 'USU & POLMED Medan')</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:    #1a3a6e;
            --primary-light: #2952a3;
            --accent:     #f97316;
            --accent-light: #fed7aa;
            --success:    #16a34a;
            --danger:     #dc2626;
            --warning:    #d97706;
            --bg:         #f0f4f8;
            --card:       #ffffff;
            --text:       #1e293b;
            --text-muted: #64748b;
            --border:     #e2e8f0;
            --sidebar-w:  260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ====== SIDEBAR ====== */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(160deg, #0f2557 0%, #1a3a6e 60%, #1d4ed8 100%);
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-logo {
            font-size: 2.2rem;
            margin-bottom: 6px;
        }

        .sidebar-brand h1 {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .sidebar-brand p {
            color: rgba(255,255,255,0.55);
            font-size: 0.72rem;
            margin-top: 2px;
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-label {
            color: rgba(255,255,255,0.35);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 8px 12px 4px;
            margin-top: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all 0.2s;
        }

        .nav-link i { width: 20px; text-align: center; font-size: 0.95rem; }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        .nav-link.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .nav-link.active i { color: #93c5fd; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.35);
            font-size: 0.68rem;
            text-align: center;
        }

        /* ====== MAIN CONTENT ====== */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .location-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #fee2e2;
            color: #b91c1c;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .page-body {
            padding: 28px 32px;
            flex: 1;
        }

        /* ====== ALERT ====== */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* ====== BUTTONS ====== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.18s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover { background: var(--primary-light); }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover { background: #15803d; }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover { background: #b91c1c; }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* ====== CARD ====== */
        .card {
            background: var(--card);
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
        }

        .card-body { padding: 20px 24px; }

        /* ====== BADGES ====== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .badge-usu     { background: #dbeafe; color: #1d4ed8; }
        .badge-polmed  { background: #dcfce7; color: #15803d; }
        .badge-keduanya{ background: #fef3c7; color: #b45309; }
        .badge-lainnya { background: #f1f5f9; color: #475569; }

        /* Kategori kosan */
        .badge-putri   { background: #fce7f3; color: #be185d; }
        .badge-putra   { background: #e0f2fe; color: #0369a1; }
        .badge-campur  { background: #ede9fe; color: #6d28d9; }
        .badge-eksklusif{ background: #fef9c3; color: #854d0e; }

        /* ====== FASILITAS ICON ====== */
        .fasil-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px;
            border-radius: 6px;
            font-size: 0.75rem;
        }

        .fasil-ada    { background: #dcfce7; color: #15803d; }
        .fasil-tidak  { background: #f1f5f9; color: #94a3b8; }

        /* ====== TABLE ====== */
        .table { width: 100%; border-collapse: collapse; }

        .table th {
            background: #f8fafc;
            padding: 12px 16px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid var(--border);
        }

        .table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.86rem;
            vertical-align: middle;
        }

        .table tr:last-child td { border-bottom: none; }

        .table tr:hover td { background: #f8fafc; }

        /* ====== FORM ====== */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 0.88rem;
            font-family: inherit;
            color: var(--text);
            background: #fff;
            transition: border-color 0.2s;
            outline: none;
        }

        .form-control:focus { border-color: var(--primary-light); }

        .form-row {
            display: grid;
            gap: 16px;
        }

        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }

        /* ====== RATING ====== */
        .rating-stars { color: #f59e0b; }

        /* ====== PAGINATION ====== */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .pagination { display: flex; gap: 4px; list-style: none; }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text);
            border: 1.5px solid var(--border);
            transition: all 0.15s;
        }

        .pagination .page-link:hover,
        .pagination .active .page-link {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .form-row-2, .form-row-3 { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">🏠</div>
            <h1>KosanMaps</h1>
            <p>USU & POLMED Medan</p>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu</div>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                Dashboard
            </a>

            <a href="{{ route('maps') }}"
               class="nav-link {{ request()->routeIs('maps') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i>
                Peta Kosan
            </a>

            <a href="{{ route('kosan.index') }}"
               class="nav-link {{ request()->routeIs('kosan.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                Data Kosan
            </a>

            <div class="nav-label">Kelola</div>

            <a href="{{ route('kosan.create') }}"
               class="nav-link {{ request()->routeIs('kosan.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle"></i>
                Tambah Kosan
            </a>
        </nav>

        <div class="sidebar-footer">
            &copy; 2024 KosanMaps Medan
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="topbar">
            <h2>@yield('page-title', 'Dashboard')</h2>
            <div class="topbar-right">
                <div class="location-badge">
                    <i class="fas fa-map-marker-alt"></i>
                    Medan, Sumatera Utara
                </div>
            </div>
        </div>

        <div class="page-body">

            {{-- Alert sukses/error --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>