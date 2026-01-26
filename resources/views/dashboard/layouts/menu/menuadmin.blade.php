<div class="sidebar" data-background-color="success">
    <style>
        /* Override Kaiadmin purple on nav-secondary to bright green */
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

        /* Style for active sub-items in nav-collapse */
        .sidebar .nav.nav-collapse > li > a.active i,
        .sidebar .nav.nav-collapse > li > a.active {
            color: #31ce36 !important;
        }

        .sidebar .nav.nav-collapse > li > a.active:before {
            background: #31ce36 !important;
        }

        /* Hamburger button color to black */
        .logo-header .btn-toggle,
        .logo-header .topbar-toggler {
            color: #31ce36 !important;
        }

        .logo-header .btn-toggle i,
        .logo-header .topbar-toggler i {
            color: #31ce36 !important;
        }
    </style>
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="success">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/sakura.png') }}" alt="navbar brand" class="navbar-brand"
                    height="120" />
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            @php
                $isDashboard = request()->routeIs('admin.dashboard') || request()->is('admin/dashboard-admin');
                $isDataUser = request()->is('admin/datauser*');
                $isCars = request()->is('admin/cars*');
                $isDriver = request()->is('admin/driver-items*');
                $isDriverMaster = request()->is('admin/drivers*');
                $isArmada = request()->is('admin/armada-items*');
                $isDokument = request()->is('admin/dokument*');
                $isEnvironment = request()->is('admin/environment*');
                $isSafety = request()->is('admin/safety*');
                $isMonthlyReport = request()->routeIs('admin.reports.monthly') || request()->is('admin/reports/monthly*');
                $isChart = request()->routeIs('admin.reports.chart') || request()->is('admin/reports/chart*');
                $isSummaryChart = request()->routeIs('admin.reports.summary-chart') || request()->is('admin/reports/summary-chart*');
            @endphp
            <ul class="nav nav-secondary">
                <li class="nav-item {{ $isDashboard ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dashboard" class="{{ ($isDashboard || $isDataUser) ? '' : 'collapsed' }}" aria-expanded="{{ ($isDashboard || $isDataUser) ? 'true' : 'false' }}">
                        <i class="fas fa-home"></i>
                        <p>Home</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ ($isDashboard || $isDataUser) ? 'show' : '' }}" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('datauser.index') }}" class="{{ $isDataUser ? 'active' : '' }}">
                                    <span class="sub-item">Data User</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                <li class="nav-item {{ $isDriverMaster ? 'active' : '' }}">
                    <a href="{{ route('drivers.index') }}">
                        <i class="fas fa-id-badge"></i>
                        <p>Data Driver</p>
                    </a>
                </li>
                <li class="nav-item {{ $isCars ? 'active' : '' }}">
                    <a href="{{ route('cars.index') }}">
                        <i class="fas fa-car"></i>
                        <p>Cars</p>
                    </a>
                </li>
                <li class="nav-item {{ $isDriver ? 'active' : '' }}">
                    <a href="{{ route('driver-items.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <p>Driver Item</p>
                    </a>
                </li>
                <li class="nav-item {{ $isArmada ? 'active' : '' }}">
                    <a href="{{ route('armada-items.index') }}" class="{{ $isArmada ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <p>Armada Item</p>
                    </a>
                </li>
                <li class="nav-item {{ $isDokument ? 'active' : '' }}">
                    <a href="{{ route('dokument.index') }}" class="{{ $isDokument ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <p>Document Control</p>
                    </a>
                </li>
                <li class="nav-item {{ $isEnvironment ? 'active' : '' }}">
                    <a href="{{ route('environment.index') }}" class="{{ $isEnvironment ? 'active' : '' }}">
                        <i class="fas fa-seedling"></i>
                        <p>Environment</p>
                    </a>
                </li>
                <li class="nav-item {{ $isSafety ? 'active' : '' }}">
                    <a href="{{ route('safety.index') }}" class="{{ $isSafety ? 'active' : '' }}">
                        <i class="fas fa-shield-alt"></i>
                        <p>Safety Warning</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Reports</h4>
                </li>
                <li class="nav-item {{ $isMonthlyReport ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.monthly') }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Rekap Bulanan</p>
                    </a>
                </li>
                <li class="nav-item {{ $isChart || $isSummaryChart ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#charts" class="{{ ($isChart || $isSummaryChart) ? '' : 'collapsed' }}" aria-expanded="{{ ($isChart || $isSummaryChart) ? 'true' : 'false' }}">
                        <i class="fas fa-chart-line"></i>
                        <p>Charts</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ ($isChart || $isSummaryChart) ? 'show' : '' }}" id="charts">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.reports.chart') }}" class="{{ $isChart ? 'active' : '' }}">
                                    <span class="sub-item">Chart Laporan Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.summary-chart') }}" class="{{ $isSummaryChart ? 'active' : '' }}">
                                    <span class="sub-item">Chart Overview</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
