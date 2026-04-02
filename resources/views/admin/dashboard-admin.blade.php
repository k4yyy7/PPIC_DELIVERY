@extends('dashboard.layouts.index')

@section('content')
    <style>
        /* Plus Jakarta Sans - Local Font */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 400;
            font-style: normal;
            font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-400.woff2') format('woff2'),
                url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-400.woff') format('woff');
        }

        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 500;
            font-style: normal;
            font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-500.woff2') format('woff2'),
                url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-500.woff') format('woff');
        }

        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 600;
            font-style: normal;
            font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-600.woff2') format('woff2'),
                url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-600.woff') format('woff');
        }

        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 700;
            font-style: normal;
            font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-700.woff2') format('woff2'),
                url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-700.woff') format('woff');
        }

        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 800;
            font-style: normal;
            font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-800.woff2') format('woff2'),
                url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-800.woff') format('woff');
        }

        :root {
            --blue: #3b5bdb;
            --blue-lt: #e8edff;
            --green: #12b886;
            --green-lt: #d3f9d8;
            --yellow: #f59f00;
            --yellow-lt: #fff3bf;
            --cyan: #1098ad;
            --cyan-lt: #d0ebff;
            --red: #e03131;
            --red-lt: #ffe3e3;
            --surface: #f4f6fb;
            --card: #ffffff;
            --border: #e8ecf4;
            --text: #1a2035;
            --muted: #8592a8;
            --shadow: 0 2px 12px rgba(30, 40, 80, .07);
            --shadow-md: 0 6px 24px rgba(30, 40, 80, .10);
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }

        .page-inner {
            background: var(--surface);
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* ── TOP BAR ───────────────────────────── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 32px 0 20px;
        }

        .topbar-title h3 {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text);
            margin: 0 0 2px;
            letter-spacing: -.4px;
        }

        .topbar-title p {
            font-size: .78rem;
            color: var(--muted);
            margin: 0;
        }

        .datetime-pill {
            display: flex;
            align-items: center;
            gap: 0;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 40px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .datetime-pill .dp-part {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text);
        }

        .datetime-pill .dp-part i {
            color: var(--muted);
            font-size: .8rem;
        }

        .datetime-pill .dp-divider {
            width: 1px;
            height: 22px;
            background: var(--border);
        }

        .clock-time {
            color: var(--blue);
            font-variant-numeric: tabular-nums;
        }

        /* ── ACTION BUTTONS ────────────────────── */
        .action-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
            letter-spacing: .1px;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-primary-action {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 4px 14px rgba(59, 91, 219, .3);
        }

        .btn-primary-action:hover {
            background: #5472e8;
            box-shadow: 0 6px 18px rgba(59, 91, 219, .45);
            color: #fff;
        }

        .btn-primary-action:hover i {
            color: #e8edff;
        }

        .btn-secondary-action {
            background: var(--card);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary-action:hover {
            background: #f0f3fa;
            border-color: #c5cdd8;
        }

        .btn-secondary-action i.text-info {
            color: var(--cyan) !important;
        }

        .btn-secondary-action i.text-success {
            color: var(--green) !important;
        }

        /* ── STATUS BANNER ─────────────────────── */
        .status-banner {
            background: var(--card);
            border-radius: 14px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 4px solid var(--blue);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .status-banner .sb-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sb-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--blue-lt);
            color: var(--blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
        }

        .sb-label {
            font-size: .72rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .sb-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-top: 1px;
        }

        .sb-value i {
            color: var(--green);
        }

        .live-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--green-lt);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: .72rem;
            font-weight: 700;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse-anim 2s infinite;
        }

        @keyframes pulse-anim {
            0% {
                box-shadow: 0 0 0 0 rgba(18, 184, 134, .6);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(18, 184, 134, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(18, 184, 134, 0);
            }
        }

        /* ── STAT CARDS ────────────────────────── */
        .stat-card {
            background: var(--card);
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: var(--shadow);
            transition: all .22s;
            border: 1px solid var(--border);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card .sc-label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .stat-card .sc-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        .stat-card .sc-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        /* ── BOTTOM ROW CARDS ──────────────────── */
        .bottom-card {
            background: var(--card);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 360px;
            /* taller */
            overflow: hidden;
        }

        /* ── FEED HEADER ───────────────────────── */
        .feed-head {
            background: linear-gradient(135deg, #3b5bdb 0%, #1a3bb3 100%);
            color: #fff;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .feed-head .fh-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .feed-head h6 {
            font-size: .88rem;
            font-weight: 700;
            margin: 0;
        }

        .feed-head .fh-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
        }

        .feed-pill {
            background: rgba(255, 255, 255, .2);
            padding: 3px 11px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }

        /* ── FEED SCROLL ───────────────────────── */
        .feed-scroll-area {
            flex: 1;
            overflow: hidden;
            /* NO scrollbar */
            position: relative;
            min-height: 0;
        }

        .feed-scroll-inner {
            display: flex;
            flex-direction: column;
            /* Tambahkan will-change untuk performa GPU yang lebih bagus */
            will-change: transform;
        }

        .feed-scroll-inner.is-scrolling {
            animation: feedScrollUp var(--scroll-dur, 20s) linear infinite;
        }

        .feed-scroll-inner.is-paused {
            animation-play-state: paused !important;
        }

        @keyframes feedScrollUp {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-50%);
            }
        }

        .feed-item {
            padding: 11px 18px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            background: var(--card);
            transition: background .15s;
        }

        .feed-item:hover {
            background: #f8f9fe;
        }

        .feed-item .fi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .fi-plat {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: .84rem;
            color: var(--text);
        }

        .fi-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse-anim 2s infinite;
        }

        .fi-meta {
            display: flex;
            justify-content: space-between;
        }

        .fi-meta span {
            font-size: .7rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .feed-status {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
        }

        .feed-status.ok {
            background: var(--green-lt);
            color: var(--green);
        }

        .feed-status.ng {
            background: var(--red-lt);
            color: var(--red);
        }

        .feed-status.belum-laporan {
            background: var(--blue-lt);
            color: var(--blue);
        }

        .feed-status.unknown {
            background: var(--yellow-lt);
            color: var(--yellow);
        }

        /* ── FEED FOOTER ───────────────────────── */
        .feed-foot {
            padding: 8px 18px;
            background: #fafbff;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .feed-foot .ff-info {
            font-size: .7rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-check-label {
            font-size: .7rem;
            color: var(--muted);
        }

        /* Auto Scroll Toggle - aligned with refresh */
        .feed-foot .auto-scroll-control {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .feed-foot .auto-scroll-control .form-check {
            margin-bottom: 0;
            padding-left: 2rem;
        }

        .feed-foot .auto-scroll-control .form-check-input {
            width: 36px;
            height: 18px;
            margin-left: -2rem;
        }

        .feed-foot .auto-scroll-control .form-check-label {
            font-size: .7rem;
            color: var(--muted);
            margin-bottom: 0;
        }

        /* ── CHART CARD ────────────────────────── */
        .chart-head {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .chart-head h6 {
            font-size: .9rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }

        .chart-head .ch-badge {
            font-size: .72rem;
            font-weight: 600;
            color: var(--muted);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 3px 12px;
            border-radius: 20px;
        }

        .chart-body {
            flex: 1;
            padding: 10px 6px 0;
            min-height: 0;
        }

        .chart-stats-row {
            display: flex;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .chart-stat-item {
            flex: 1;
            padding: 10px 0;
            text-align: center;
        }

        .chart-stat-item+.chart-stat-item {
            border-left: 1px solid var(--border);
        }

        .chart-stat-item .csi-label {
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            margin-bottom: 3px;
        }

        .chart-stat-item .csi-val {
            font-size: .9rem;
            font-weight: 800;
        }

        /* Container Feed */
        .feed-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f1f1;
            background: #fff;
        }

        .fi-dot {
            height: 10px;
            width: 10px;
            background-color: #1cc88a;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        /* Meta Grid: Membagi 3 kolom sama rata dan sejajar vertikal */
        .fi-meta-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 0.8fr;
            /* Driver lebih lebar, jam lebih sempit */
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: #6e707e;
        }

        .meta-col {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            overflow: hidden;
        }

        .meta-col i {
            width: 14px;
            /* Lebar icon tetap supaya teks sejajar */
            text-align: center;
        }

        .meta-col.driver span {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .meta-col.time {
            justify-content: flex-end;
            /* Jam mepet kanan agar rapi */
            font-weight: 500;
        }

        .meta-col.tasks {
            justify-content: center;
            /* Task di tengah */
            color: #4e73df;
            font-weight: 600;
        }

        /* Status Badge */
        .feed-status {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .feed-status.ok {
            background: #e8faf4;
            color: #1cc88a;
        }

        .feed-status.ng {
            background: #fbeaea;
            color: #e74a3b;
        }

        /* Container Utama Meta */
        .fi-meta-grid {
            display: flex;
            justify-content: space-between;
            /* Driver di kiri, grup info di kanan */
            align-items: center;
            font-size: 0.8rem;
            color: #6e707e;
            padding-top: 4px;
        }

        /* Kolom Driver (Kiri) */
        .meta-col.driver {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            /* Mengambil sisa ruang agar info kanan terdorong */
            min-width: 0;
            /* Penting untuk text-truncate */
        }

        /* Grup Info Kanan (Tasks + Jam) */
        .meta-right {
            display: flex;
            align-items: center;
            gap: 12px;
            /* Jarak antar elemen di kanan */
            flex-shrink: 0;
            /* Jangan biarkan grup ini mengecil */
        }

        /* Gaya untuk setiap kolom info */
        .meta-col {
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        /* Gaya Garis Pembatas (Skat) */
        .meta-separator {
            color: #d1d3e2;
            /* Warna abu-abu halus */
            font-weight: 300;
            font-size: 0.85rem;
        }

        /* Detail Tambahan */
        .meta-col.tasks {
            color: #4e73df;
            font-weight: 600;
        }

        .meta-col.time {
            color: #858796;
        }

        .meta-col i {
            font-size: 0.75rem;
        }
    </style>

    <div class="page-inner">
        <div class="container-fluid" style="max-width: 1260px; margin: 0 auto; padding: 0 20px;">

            {{-- TOP BAR --}}
            <div class="topbar">
                <div class="topbar-title">
                    <h3>PPIC Delivery</h3>
                    <p><i class="fas fa-layer-group me-2"></i>Sistem Monitoring Manajemen Terpusat</p>
                </div>
                <div class="datetime-pill">
                    <div class="dp-part">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ now()->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="dp-divider"></div>
                    <div class="dp-part">
                        <i class="far fa-clock"></i>
                        <span class="clock-time" id="realtime-clock">00:00:00</span>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="action-bar">
                <a href="{{ route('drivers.create') }}" class="btn-action btn-primary-action">
                    <i class="fas fa-plus-circle"></i> Tambah Driver
                </a>
                <a href="{{ route('cars.create') }}" class="btn-action btn-secondary-action">
                    <i class="fas fa-car text-info"></i> Armada
                </a>
                <div class="dropdown">
                    <button class="btn-action btn-secondary-action dropdown-toggle" type="button" id="buatLaporanDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-export text-success"></i> Buat Laporan
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="buatLaporanDropdown"
                        style="min-width: 200px; border-radius: 10px; box-shadow: var(--shadow-md); border: 1px solid var(--border);">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                href="{{ route('driver-items.index') }}">
                                <i class="fas fa-user-tie text-primary"></i>
                                <span>Driver Items</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                href="{{ route('armada-items.index') }}">
                                <i class="fas fa-truck text-info"></i>
                                <span>Armada Items</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                href="{{ route('dokument.index') }}">
                                <i class="fas fa-file-alt text-warning"></i>
                                <span>Document Control</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                href="{{ route('environment.index') }}">
                                <i class="fas fa-seedling text-success"></i>
                                <span>Environment</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                href="{{ route('safety.index') }}">
                                <i class="fas fa-shield-alt text-danger"></i>
                                <span>Safety Warning</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- STAT CARDS --}}
            <div class="row g-3 mb-3">
                @php
                    $stats = [
                        [
                            'title' => 'Driver Aktif',
                            'value' => $totalDriver,
                            'icon' => 'fa-users',
                            'color' => '#3b5bdb',
                            'bg' => '#e8edff',
                        ],
                        [
                            'title' => 'Unit Armada',
                            'value' => $totalArmada,
                            'icon' => 'fa-truck',
                            'color' => '#1098ad',
                            'bg' => '#d0ebff',
                        ],
                        [
                            'title' => 'Total Item',
                            'value' => $totalItem,
                            'icon' => 'fa-box-open',
                            'color' => '#12b886',
                            'bg' => '#d3f9d8',
                        ],
                        [
                            'title' => 'Total Laporan',
                            'value' => $laporanHariIni,
                            'icon' => 'fa-file-invoice',
                            'color' => '#f59f00',
                            'bg' => '#fff3bf',
                        ],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="col-6 col-md-3">
                        <div class="stat-card d-flex align-items-center justify-content-between">
                            <div>
                                <div class="sc-label">{{ $stat['title'] }}</div>
                                <div class="sc-value">{{ $stat['value'] }}</div>
                            </div>
                            <div class="sc-icon" style="background:{{ $stat['bg'] }}; color:{{ $stat['color'] }};">
                                <i class="fas {{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            {{-- STATUS BANNER --}}
            <div class="status-banner">
                <div class="sb-left">
                    <div class="sb-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <div class="sb-label">Update Produksi Hari Ini</div>
                        <div class="sb-value">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ $laporanHariIni }} Laporan Telah Terbit
                        </div>
                    </div>
                </div>
                <div class="live-badge">
                    <div class="pulse-dot"></div>
                    Live Operational
                </div>
            </div>

            {{-- BOTTOM ROW --}}
            <div class="row g-3">

                {{-- DRIVER LIVE FEED --}}
                <div class="col-md-5">
                    <div class="bottom-card">
                        {{-- Header --}}
                        <div class="feed-head">
                            <div class="fh-left">
                                <div class="fh-icon"><i class="fas fa-truck" style="color:#fff;"></i></div>
                                <h6>Live Driver Feed</h6>                            </div>
                            <span class="feed-pill" id="feed-count">{{ count($driverFeed) }} User</span>
                        </div>
    
                        {{-- Scroll Area — NO scrollbar --}}
                        <div class="feed-scroll-area" id="driverFeedWrapper">
                            <div class="feed-scroll-inner" id="driverFeedScroll">
                                @if (count($driverFeed) > 0)
                                    @foreach ($driverFeed as $feed)
                                        <div class="feed-item">
                                            <div class="fi-top d-flex justify-content-between align-items-center mb-2">
                                                <div class="fi-plat fw-bold">
                                                    <span class="fi-dot"></span>
                                                    {{ $feed['plat_nomor'] }}
                                                </div>
                                                <div class="d-flex gap-1 flex-wrap justify-content-end">
                                                    @if (isset($feed['status_list']) && is_array($feed['status_list']))
                                                        @foreach ($feed['status_list'] as $s)
                                                            <span
                                                                class="feed-status {{ $s['class'] ?? 'unknown' }}">{{ $s['text'] ?? 'Unknown' }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="feed-status unknown">Unknown</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="fi-meta-grid">
                                                <div class="meta-col driver">
                                                    <i class="fas fa-user"></i>
                                                    <span class="text-truncate">{{ $feed['driver_name'] }}</span>
                                                </div>

                                                <div class="meta-right">
                                                    <div class="meta-col tasks">
                                                        <i class="fas fa-tasks text-primary"></i>
                                                        <span>{{ $feed['item_filled'] ?? 0 }}/36 item</span>
                                                    </div>

                                                    <div class="meta-separator">|</div>

                                                    <div class="meta-col time">
                                                        <i class="fas fa-clock"></i>
                                                        <span>{{ $feed['last_login_formatted'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5 text-muted" id="emptyFeedMessage">
                                        <i class="fas fa-user fa-2x mb-3 d-block"></i>
                                        <p class="mb-0 small">Belum ada driver login hari ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="feed-foot">
                            <div class="ff-info">
                                <i class="fas fa-sync-alt"></i> Refresh 10dt
                            </div>
                            <div class="auto-scroll-control">
                                <div class="form-check form-switch mb-0 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="autoScrollToggle">
                                    <label class="form-check-label" for="autoScrollToggle">Auto Scroll</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CHART --}}
                <div class="col-md-7">
                    <div class="bottom-card">
                        <div class="chart-head">
                            <h6>Tren Aktivitas</h6>
                            <span class="ch-badge">7 Hari Terakhir</span>
                        </div>
                        <div class="chart-body">
                            <div id="userStatsChart" style="height: 220px;"></div>
                        </div>
                        <div class="chart-stats-row">
                            <div class="chart-stat-item">
                                <div class="csi-label">Total Login</div>
                                <div class="csi-val" style="color: var(--blue);">
                                    {{ number_format(array_sum($chartData['loginCounts'])) }}</div>
                            </div>
                            <div class="chart-stat-item">
                                <div class="csi-label">Rata-rata</div>
                                <div class="csi-val" style="color: var(--yellow);">
                                    {{ round(array_sum($chartData['loginCounts']) / 7, 1) }}</div>
                            </div>
                            <div class="chart-stat-item">
                                <div class="csi-label">Tertinggi</div>
                                <div class="csi-val" style="color: var(--cyan);">
                                    {{ max($chartData['activeUsersCounts']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script>
        // ── CLOCK ──────────────────────────────────────────
        function updateClock() {
            const t = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('realtime-clock').textContent = t + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener('DOMContentLoaded', function() {

            // ── CHART ──────────────────────────────────────
            new ApexCharts(document.querySelector('#userStatsChart'), {
                chart: {
                    type: 'area',
                    height: 220,
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 600
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                series: [{
                        name: 'Login',
                        data: {!! json_encode($chartData['loginCounts']) !!}
                    },
                    {
                        name: 'User Aktif',
                        data: {!! json_encode($chartData['activeUsersCounts']) !!}
                    }
                ],
                xaxis: {
                    categories: {!! json_encode($chartData['dates']) !!},
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontFamily: 'Plus Jakarta Sans, sans-serif',
                            colors: '#8592a8'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontFamily: 'Plus Jakarta Sans, sans-serif',
                            colors: '#8592a8'
                        }
                    }
                },
                colors: ['#3b5bdb', '#12b886'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.25,
                        opacityTo: 0.02,
                        stops: [0, 95, 100]
                    }
                },
                grid: {
                    borderColor: '#e8ecf4',
                    strokeDashArray: 4,
                    padding: {
                        left: 10,
                        right: 10,
                        top: -10
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontWeight: 600,
                    fontSize: '12px',
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                tooltip: {
                    theme: 'light'
                }
            }).render();

            // ── FEED SCROLL SYSTEM (FIXED) ─────────────────────────
            const wrapper = document.getElementById('driverFeedWrapper');
            const scrollEl = document.getElementById('driverFeedScroll');
            const countEl = document.getElementById('feed-count');
            const toggleEl = document.getElementById('autoScrollToggle');
            let currentCount = {{ count($driverFeed) }};
            let active = false;

            // Fungsi helper: Hitung durasi berdasarkan jumlah item asli (setengah dari total)
            function calcDuration(itemCount) {
                // Minimal 8 detik, atau 2.5 detik per item
                return Math.max(8, itemCount * 2.5) + 's';
            }

            function startScroll() {
                active = true;
                // Hitung item asli (total / 2 karena sudah diduplikasi)
                const totalItems = scrollEl.querySelectorAll('.feed-item').length;
                const originalCount = totalItems / 2;

                scrollEl.style.setProperty('--scroll-dur', calcDuration(originalCount));
                scrollEl.classList.add('is-scrolling');
                scrollEl.classList.remove('is-paused');
            }

            function stopScroll() {
                active = false;
                scrollEl.classList.remove('is-scrolling');
            }

            function buildItem(feed) {
                let statusHtml = '';
                if (feed.status_list && feed.status_list.length > 0) {
                    feed.status_list.forEach(s => {
                        statusHtml += `<span class="feed-status ${s.class}">${s.text}</span>`;
                    });
                } else {
                    statusHtml = `<span class="feed-status unknown">Unknown</span>`;
                }
                return `
                <div class="feed-item">
                    <div class="fi-top">
                        <div class="fi-plat"><span class="fi-dot"></span>${feed.plat_nomor}</div>
                        <div class="d-flex gap-1 flex-wrap">
                            ${statusHtml}
                        </div>
                    </div>
                    <div class="fi-meta">
                        <span><i class="fas fa-user"></i> ${feed.driver_name}</span>
                        <span><i class="fas fa-clock"></i> ${feed.last_login_formatted || '-'} </span>
                    </div>
                </div>`;
            }

            // LOGICA UTAMA: Menyiapkan konten untuk seamless loop
            function prepareContent(data) {
                if (!data || data.length === 0) return '';

                // 1. Buat HTML dasar dari data
                let baseHtml = data.map(buildItem).join('');

                // 2. Cek apakah perlu multiple copies?
                // Kita ingin tinggi konten asli (baseHtml) MINIMAL sama dengan tinggi container (wrapper).
                // Jika kurang, animasi translateY(-50%) akan membuat "lobang" kosong di tengah.

                // Strategi: Duplikasi baseHtml sampai setengah tinggi total >= tinggi container.
                // Karena kita pakai translateY(-50%), berarti tinggi baseHtml harus >= tinggi wrapper.

                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = baseHtml;
                tempDiv.style.position = 'absolute';
                tempDiv.style.visibility = 'hidden';
                tempDiv.style.height = 'auto';
                // Approximate width agar sama dengan wrapper (supaya tinggi item akurat)
                tempDiv.style.width = wrapper.clientWidth + 'px';
                document.body.appendChild(tempDiv);

                let baseHeight = tempDiv.offsetHeight;
                let wrapperHeight = wrapper.clientHeight;
                document.body.removeChild(tempDiv);

                // Jika konten asli lebih pendek dari container, ulangi lagi.
                // Contoh: Data cuma 1 item (tinggi 50px), wrapper 300px.
                // Perlu ulangi sampai 300px.
                let finalHtml = baseHtml;
                let repeats = 1;

                // Loop sampai tinggi base cukup (dengan sedikit buffer 10px)
                while (baseHeight < wrapperHeight + 10) {
                    finalHtml += baseHtml;
                    baseHeight += baseHeight; // Perkiraan penambahan tinggi
                    repeats++;
                    // Hard limit biar ga infinite loop kalau ada error (misal item 0px)
                    if (repeats > 20) break;
                }

                // 3. Kembalikan HTML yang SUDAH CUKUP TINGGI, lalu DUPLIKASI untuk loop seamless
                // Hasil: [Base_Netto] + [Clone_Base_Netto]
                return finalHtml + finalHtml;
            }

            function renderFeed(data) {
                if (!data || data.length === 0) {
                    scrollEl.innerHTML = `
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-truck-loading fa-2x mb-3 d-block"></i>
                            <p class="mb-0 small">Belum ada driver login hari ini</p>
                        </div>`;
                    countEl.textContent = '0 User';
                    stopScroll();
                    toggleEl.checked = false;
                    return;
                }

                // Gunakan fungsi prepareContent yang cerdas
                scrollEl.innerHTML = prepareContent(data);
                countEl.textContent = data.length + ' User';

                // Reset animation
                scrollEl.style.animation = 'none';
                scrollEl.offsetHeight; // trigger reflow
                scrollEl.style.animation = '';

                if (data.length >= 1 && toggleEl.checked) startScroll();
            }

            // Init: duplicate existing PHP-rendered items
            const existing = Array.from(scrollEl.querySelectorAll('.feed-item'));
            if (existing.length > 0) {
                // Kita leverage prepareContent juga untuk init, biar konsisten
                // Tapi kita butuh data mentah. Karena di init kita cuma punya DOM,
                // Kita pakai cara simple: cek tinggi lalu duplikat.

                let baseHtml = '';
                existing.forEach(el => baseHtml += el.outerHTML);

                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = baseHtml;
                tempDiv.style.cssText =
                    `position:absolute;visibility:hidden;height:auto;width:${wrapper.clientWidth}px`;
                document.body.appendChild(tempDiv);
                let baseHeight = tempDiv.offsetHeight;
                let wrapperHeight = wrapper.clientHeight;
                document.body.removeChild(tempDiv);

                let finalHtml = baseHtml;
                while (baseHeight < wrapperHeight + 10) {
                    finalHtml += baseHtml;
                    baseHeight += baseHeight;
                }

                // Terapkan
                scrollEl.innerHTML = finalHtml + finalHtml;

                if (existing.length >= 1) {
                    toggleEl.checked = true;
                    startScroll();
                }
            }

            // Toggle
            toggleEl.addEventListener('change', () => toggleEl.checked ? startScroll() : stopScroll());

            // Hover pause
            wrapper.addEventListener('mouseenter', () => {
                if (active) scrollEl.classList.add('is-paused');
            });
            wrapper.addEventListener('mouseleave', () => {
                if (active) scrollEl.classList.remove('is-paused');
            });

            // Auto-refresh
            setInterval(() => {
                fetch('{{ route('admin.driver-feed') }}')
                    .then(r => r.json())
                    .then(d => {
                        if (d.count !== currentCount) {
                            currentCount = d.count;
                            renderFeed(d.feed);
                        }
                    });
            }, 10000);
        });
    </script>
@endsection
