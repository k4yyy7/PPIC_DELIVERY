<div class="sidebar" data-background-color="success">
    <style>
        /* 1. HILANGKAN SCROLLBAR TOTAL */
        .sidebar-wrapper {
            overflow: hidden !important; 
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
        .sidebar-wrapper::-webkit-scrollbar {
            display: none !important;
        }

        /* 2. HOVER STYLE - TARGET ICON & TEKS SERENTAK */
        /* Menghapus warna ungu/biru default dan menggantinya ke Hijau Sakura saat hover */
        .sidebar .nav.nav-secondary > .nav-item a:focus i,
        .sidebar .nav.nav-secondary > .nav-item a:hover i,
        .sidebar .nav.nav-secondary > .nav-item a:focus p,
        .sidebar .nav.nav-secondary > .nav-item a:hover p,
        .sidebar .nav.nav-secondary > .nav-item a[data-toggle=collapse][aria-expanded=true] i,
        .sidebar .nav.nav-secondary > .nav-item a[data-toggle=collapse][aria-expanded=true] p,
        .sidebar .nav.nav-secondary > .nav-item.active a i,
        .sidebar .nav.nav-secondary > .nav-item.active a p,
        /* Target untuk sub-item (dropdown) */
        .sidebar .nav.nav-collapse > li > a.active i,
        .sidebar .nav.nav-collapse > li > a.active .sub-item,
        .sidebar .nav.nav-collapse > li > a:hover i,
        .sidebar .nav.nav-collapse > li > a:hover .sub-item {
            color: #31ce36 !important;
            fill: #31ce36 !important;
        }

        /* Garis indikator samping hijau */
        .sidebar .nav.nav-secondary > .nav-item a[data-toggle=collapse][aria-expanded=true]:before,
        .sidebar .nav.nav-secondary > .nav-item.active a:before {
            background: #31ce36 !important;
        }

        /* Fix Jarak Sub-item agar icon dan teks rapat (Bullet dibuang) */
        .sidebar .nav.nav-collapse > li > a .sub-item:before {
            display: none !important;
        }
        .sidebar .nav.nav-collapse > li > a i {
            margin-right: 10px !important;
            width: 20px;
            text-align: center;
        }

        /* Warna default icon agar tidak ungu sebelum di-hover */
        .sidebar .nav .nav-item a i, 
        .sidebar .nav .nav-item a p {
            color: #8b8b8b;
            transition: all 0.2s ease-in-out;
        }

        /* Hamburger button color */
        .logo-header .btn-toggle i,
        .logo-header .topbar-toggler i {
            color: #31ce36 !important;
        }
    </style>

    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="success">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/sakura.png') }}" alt="navbar brand" class="navbar-brand" height="120" />
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
    </div>

    <div class="sidebar-wrapper">
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
                <li class="nav-item {{ $isDashboard || $isDataUser || $isDriverMaster || $isCars ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dashboard" class="{{ $isDashboard || $isDataUser || $isDriverMaster || $isCars ? '' : 'collapsed' }}">
                        <i class="fas fa-home"></i>
                        <p>Home</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isDashboard || $isDataUser || $isDriverMaster || $isCars ? 'show' : '' }}" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('cars.index') }}" class="{{ $isCars ? 'active' : '' }}">
                                    <i class="fas fa-car-side"></i>
                                    <span class="sub-item">Data Mobil</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('datauser.index') }}" class="{{ $isDataUser ? 'active' : '' }}">
                                    <i class="fas fa-user"></i>
                                    <span class="sub-item">Data User</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('drivers.index') }}" class="{{ $isDriverMaster ? 'active' : '' }}">
                                    <i class="fas fa-id-badge"></i>
                                    <span class="sub-item">Data Driver</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <h4 class="text-section">Components</h4>
                </li>

                <li class="nav-item {{ $isDriver ? 'active' : '' }}">
                    <a href="{{ route('driver-items.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <p>Driver Item</p>
                    </a>
                </li>
                <li class="nav-item {{ $isArmada ? 'active' : '' }}">
                    <a href="{{ route('armada-items.index') }}">
                        <i class="fas fa-truck"></i>
                        <p>Armada Item</p>
                    </a>
                </li>
                <li class="nav-item {{ $isDokument ? 'active' : '' }}">
                    <a href="{{ route('dokument.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <p>Document Control</p>
                    </a>
                </li>
                <li class="nav-item {{ $isEnvironment ? 'active' : '' }}">
                    <a href="{{ route('environment.index') }}">
                        <i class="fas fa-seedling"></i>
                        <p>Environment</p>
                    </a>
                </li>
                <li class="nav-item {{ $isSafety ? 'active' : '' }}">
                    <a href="{{ route('safety.index') }}">
                        <i class="fas fa-shield-alt"></i>
                        <p>Safety Warning</p>
                    </a>
                </li>

                <li class="nav-section">
                    <h4 class="text-section">Reports</h4>
                </li>

                <li class="nav-item {{ $isMonthlyReport ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.monthly') }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Rekap Bulanan</p>
                    </a>
                </li>

                <li class="nav-item {{ $isChart || $isSummaryChart ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#charts" class="{{ $isChart || $isSummaryChart ? '' : 'collapsed' }}">
                        <i class="fas fa-chart-line"></i>
                        <p>Charts</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isChart || $isSummaryChart ? 'show' : '' }}" id="charts">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.reports.chart') }}" class="{{ $isChart ? 'active' : '' }}">
                                    <i class="fas fa-chart-pie"></i>
                                    <span class="sub-item">Chart Laporan Harian</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.summary-chart') }}" class="{{ $isSummaryChart ? 'active' : '' }}">
                                    <i class="fas fa-chart-area"></i>
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