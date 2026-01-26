<div class="sidebar" data-background-color="success">
    <style>
        /* Match admin sidebar accent color */
        .sidebar .nav.nav-secondary>.nav-item a:focus i,
        .sidebar .nav.nav-secondary>.nav-item a:hover i,
        .sidebar .nav.nav-secondary>.nav-item a[data-toggle=collapse][aria-expanded=true] i,
        .sidebar .nav.nav-secondary>.nav-item.active a i {
            color: #31ce36 !important;
        }

        .sidebar .nav.nav-secondary>.nav-item a:focus,
        .sidebar .nav.nav-secondary>.nav-item a:hover,
        .sidebar .nav.nav-secondary>.nav-item a[data-toggle=collapse][aria-expanded=true] {
            color: #31ce36 !important;
        }

        .sidebar .nav.nav-secondary>.nav-item a[data-toggle=collapse][aria-expanded=true]:before,
        .sidebar .nav.nav-secondary>.nav-item.active a:before {
            background: #31ce36 !important;
        }

        .sidebar .nav.nav-collapse > li > a.active i,
        .sidebar .nav.nav-collapse > li > a.active {
            color: #31ce36 !important;
        }

        .sidebar .nav.nav-collapse > li > a.active:before {
            background: #31ce36 !important;
        }

        /* Hamburger button color */
        .logo-header .btn-toggle,
        .logo-header .topbar-toggler,
        .logo-header .btn-toggle i,
        .logo-header .topbar-toggler i {
            color: #31ce36 !important;
        }

        /* Mobile: make navbar/sidebar white */
        @media (max-width: 991px) {
            .logo-header[data-background-color="success"] {
                background-color: #ffffff !important;
            }

            .sidebar[data-background-color="success"],
            .sidebar .sidebar-wrapper {
                background-color: #ffffff !important;
            }

            .sidebar .nav > li > a,
            .sidebar .nav > li > a i {
                color: #1a1a1a !important;
            }
        }
    </style>

    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="success">
            <a href="{{ route('user.dashboard') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/sakura.png') }}" alt="Sakura Logo" class="navbar-brand" height="100" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    @php
        $isDashboard = request()->routeIs('user.dashboard') || request()->is('user/dashboard-user');
        $isDailyDriver = request()->routeIs('user.daily.driver') || request()->is('user/daily/driver');
        $isActiveDriver = request()->routeIs('user.driver.active') || request()->is('user/driver/active');
        $isDailyArmada = request()->routeIs('user.daily.armada') || request()->is('user/daily/armada');
        $isDokument = request()->routeIs('user.daily.dokument') || request()->is('user/daily/dokument');
        $isEnvironment = request()->routeIs('user.daily.environment') || request()->is('user/daily/environment');
        $isSafety = request()->routeIs('user.daily.safety') || request()->is('user/daily/safety');
        $isHistory = request()->routeIs('user.daily.history');
    @endphp
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ $isDashboard ? 'active' : '' }}">
                    <a href="{{ route('user.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ $isActiveDriver ? 'active' : '' }}">
                    <a href="{{ route('user.driver.active') }}">
                        <i class="fas fa-id-badge"></i>
                        <p>Driver Aktif Hari Ini</p>
                    </a>
                </li>
                <li class="nav-item {{ $isDailyDriver ? 'active' : '' }}">
                    <a href="{{ route('user.daily.driver') }}">
                        <i class="fas fa-user-tie"></i>
                        <p>Driver Items</p>
                    </a>
                </li>
                <li class="nav-item {{ $isDailyArmada ? 'active' : '' }}">
                    <a href="{{ route('user.daily.armada') }}">
                        <i class="fas fa-truck"></i>
                        <p>Armada Items</p>
                    </a>
                </li>
                <li class="nav-item {{ $isDokument ? 'active' : '' }}">
                    <a href="{{ route('user.daily.dokument') }}">
                        <i class="fas fa-file-alt"></i>
                        <p>Document Control</p>
                    </a>
                </li>
                <li class="nav-item {{ $isEnvironment ? 'active' : '' }}">
                    <a href="{{ route('user.daily.environment') }}">
                        <i class="fas fa-seedling"></i>
                        <p>Environment</p>
                    </a>
                </li>
                <li class="nav-item {{ $isSafety ? 'active' : '' }}">
                    <a href="{{ route('user.daily.safety') }}">
                        <i class="fas fa-shield-alt"></i>
                        <p>Safety Warning</p>
                    </a>
                </li>
                <li class="nav-item {{ $isHistory ? 'active' : '' }}">
                    <a href="{{ route('user.daily.history') }}">
                        <i class="fas fa-history"></i>
                        <p>Riwayat Laporan</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
