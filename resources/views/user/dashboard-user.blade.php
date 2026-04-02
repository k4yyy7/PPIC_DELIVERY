@extends('dashboard.layouts.index')

@section('content')
    <style>
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 400; font-style: normal; font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-400.woff2') format('woff2'),
                 url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-400.woff') format('woff');
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 500; font-style: normal; font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-500.woff2') format('woff2'),
                 url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-500.woff') format('woff');
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 600; font-style: normal; font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-600.woff2') format('woff2'),
                 url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-600.woff') format('woff');
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 700; font-style: normal; font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-700.woff2') format('woff2'),
                 url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-700.woff') format('woff');
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-weight: 800; font-style: normal; font-display: swap;
            src: url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-800.woff2') format('woff2'),
                 url('/assets/fonts/plus-jakarta-sans/plus-jakarta-sans-v12-latin-800.woff') format('woff');
        }

        :root {
            --blue:      #3b5bdb;
            --blue-lt:   #e8edff;
            --green:     #12b886;
            --green-lt:  #d3f9d8;
            --yellow:    #f59f00;
            --yellow-lt: #fff3bf;
            --cyan:      #1098ad;
            --cyan-lt:   #d0ebff;
            --purple:    #7048e8;
            --purple-lt: #f3f0ff;
            --red:       #e03131;
            --red-lt:    #ffe3e3;
            --surface:   #f4f6fb;
            --card:      #ffffff;
            --border:    #e8ecf4;
            --text:      #1a2035;
            --muted:     #8592a8;
            --shadow:    0 2px 12px rgba(30,40,80,.07);
            --shadow-md: 0 6px 24px rgba(30,40,80,.10);
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        .page-inner {
            background: var(--surface);
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* TOP BAR */
        .topbar {
            display: flex; align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
            padding: 32px 0 24px;
        }
        .topbar-title h3 {
            font-size: 1.45rem; font-weight: 800;
            color: var(--text); margin: 0 0 2px; letter-spacing: -.4px;
        }
        .topbar-title p { font-size: .78rem; color: var(--muted); margin: 0; }

        .live-pill {
            display: flex; align-items: center; gap: 8px;
            background: var(--card); border: 1px solid var(--border);
            border-radius: 40px; padding: 7px 16px; box-shadow: var(--shadow);
        }
        .live-pill span.label {
            font-size: .72rem; font-weight: 700;
            color: var(--muted); text-transform: uppercase; letter-spacing: .5px;
        }
        .pulse-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--green); animation: pulseGreen 2s infinite; flex-shrink: 0;
        }
        @keyframes pulseGreen {
            0%   { box-shadow: 0 0 0 0 rgba(18,184,134,.6); }
            70%  { box-shadow: 0 0 0 7px rgba(18,184,134,0); }
            100% { box-shadow: 0 0 0 0 rgba(18,184,134,0); }
        }

        /* STAT CARDS */
        .stat-card {
            background: var(--card); border-radius: 16px; padding: 18px 20px;
            box-shadow: var(--shadow); border: 1px solid var(--border);
            transition: all .22s; height: 100%;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
        .stat-card .sc-label {
            font-size: .66rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .6px; color: var(--muted); margin-bottom: 6px;
        }
        .stat-card .sc-value { font-size: 1.85rem; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-card .sc-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }

        /* PROGRESS CARD */
        .progress-card {
            background: var(--card); border-radius: 16px;
            box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden;
        }

        /* Header — dark navy, NOT blue */
        .progress-card-header {
            background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
            padding: 20px 26px;
            display: flex; flex-direction: row;
            align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 14px;
            position: relative; overflow: hidden;
        }
        .progress-card-header::before {
            content: ''; position: absolute;
            width: 240px; height: 240px; border-radius: 50%;
            border: 44px solid rgba(255,255,255,.04);
            top: -90px; right: -70px; pointer-events: none;
        }
        .progress-card-header::after {
            content: ''; position: absolute;
            width: 140px; height: 140px; border-radius: 50%;
            border: 28px solid rgba(255,255,255,.03);
            bottom: -60px; left: 38%; pointer-events: none;
        }

        /* Header layout */
        .pch-row-top { display: contents; }
        .pch-left {
            display: flex; align-items: center; gap: 14px;
            position: relative; z-index: 1;
        }
        .pch-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.1);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .pch-title-wrap {}
        .pch-title h5 { color: #fff; font-size: .9rem; font-weight: 700; margin: 0 0 2px; }
        .pch-title small { color: rgba(255,255,255,.55); font-size: .73rem; }

        /* Driver name — inline dot separator after subtitle */
        .pch-driver-dot {
            display: inline-flex; align-items: center; gap: 5px;
            margin-top: 5px;
        }
        .pch-driver-dot .dd-dot {
            width: 5px; height: 5px; border-radius: 50%;
            background: var(--green); flex-shrink: 0;
            box-shadow: 0 0 0 2px rgba(18,184,134,.25);
        }
        .pch-driver-dot .dd-label {
            font-size: .7rem; color: rgba(255,255,255,.45); font-weight: 500;
        }
        .pch-driver-dot .dd-name {
            font-size: .7rem; font-weight: 700; color: #6ee7c0;
        }

        .pch-right { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }

        /* Status pill */
        .pch-status-pill {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 30px;
            font-size: .72rem; font-weight: 700; border: 1px solid transparent;
        }

        /* Circular */
        .circular-wrap { position: relative; width: 80px; height: 80px; flex-shrink: 0; }
        .circular-wrap svg { transform: rotate(-90deg); }
        .circ-bg  { fill: none; stroke: rgba(255,255,255,.15); stroke-width: 7; }
        .circ-bar { fill: none; stroke-width: 7; stroke-linecap: round; transition: stroke-dashoffset 1.2s ease-in-out; }
        .circ-text {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 800; color: #fff;
        }



        /* Progress bar */
        .main-progress-wrap {
            padding: 20px 26px; border-bottom: 1px solid var(--border);
        }
        .main-progress-label {
            display: flex; justify-content: space-between;
            font-size: .75rem; font-weight: 600; color: var(--muted); margin-bottom: 8px;
        }
        .main-bar {
            height: 8px; border-radius: 20px;
            background: var(--surface); overflow: hidden; border: 1px solid var(--border);
        }
        .main-bar-fill { height: 100%; border-radius: 20px; transition: width 1s ease-in-out; }

        /* CATEGORY SECTION */
        .cat-section { padding: 22px 26px 26px; }
        .cat-section-title {
            font-size: .7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .8px; color: var(--muted); margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }
        .cat-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        .cat-card {
            background: var(--surface); border-radius: 14px; padding: 16px;
            border: 1px solid var(--border); transition: all .22s;
            height: 100%; display: block;
            text-decoration: none !important; color: inherit !important;
        }
        .cat-card:hover {
            background: var(--card); border-color: #c8d0e8;
            transform: translateY(-3px); box-shadow: var(--shadow-md);
        }
        .cat-card.is-complete { background: linear-gradient(135deg,#f0fdf9,#ecfdf5); border-color: #6ee7c0; }
        .cat-card.is-complete:hover { border-color: #34d399; }
        .cat-card.is-incomplete { background: linear-gradient(135deg,#fffdf0,#fefce8); border-color: #fde68a; }
        .cat-card.is-incomplete:hover { border-color: #fbbf24; }

        .cat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
        .cat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; }
        .cat-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .67rem; font-weight: 700; }
        .cat-badge.done    { background: var(--green-lt); color: #087f5b; }
        .cat-badge.pending { background: var(--yellow-lt); color: #7c5a00; }
        .cat-name { font-size: .84rem; font-weight: 700; color: var(--text); margin: 0 0 3px; }
        .cat-sub  { font-size: .7rem; color: var(--muted); margin: 0 0 12px; }
        .cat-bar  { height: 5px; border-radius: 20px; background: var(--border); overflow: hidden; }
        .cat-bar-fill { height: 100%; border-radius: 20px; transition: width .8s ease-in-out; }
        .cat-arrow { opacity: 0; transition: opacity .2s, transform .2s; color: var(--muted); font-size: .72rem; flex-shrink: 0; }
        .cat-card:hover .cat-arrow { opacity: 1; transform: translateX(3px); }

        /* DONE ALERT */
        .done-alert {
            margin: 0 26px 26px;
            background: linear-gradient(135deg,#f0fdf9,#dcfce7);
            border: 1px solid #6ee7c0; border-radius: 14px;
            padding: 16px 20px; display: flex; align-items: center; gap: 14px;
        }
        .done-alert-icon {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--green); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(18,184,134,.3);
        }
        .done-alert h6 { font-size: .88rem; font-weight: 700; color: #065f46; margin: 0 0 2px; }
        .done-alert small { font-size: .75rem; color: #047857; }
    </style>

    <div class="page-inner">
        <div class="container-fluid" style="max-width: 1260px; margin: 0 auto; padding: 0 20px;">

            {{-- TOP BAR --}}
            <div class="topbar">
                <div class="topbar-title">
                    <h3>PPIC Delivery</h3>
                    <p><i class="fas fa-layer-group me-2"></i>Monitoring &amp; Management System</p>
                </div>
                <div class="live-pill">
                    <span class="pulse-dot"></span>
                    <span class="label">Live Monitoring</span>
                </div>
            </div>

            {{-- STAT CARDS --}}
            <div class="row g-3 mb-4">
                @php
                    $stats = [
                        ['title' => 'Total Driver',  'value' => $totalDriver,    'icon' => 'fa-users',         'color' => '#3b5bdb', 'bg' => '#e8edff'],
                        ['title' => 'Total Armada',  'value' => $totalArmada,    'icon' => 'fa-shipping-fast', 'color' => '#1098ad', 'bg' => '#d0ebff'],
                        ['title' => 'Total Item',    'value' => $totalItem,      'icon' => 'fa-box',           'color' => '#12b886', 'bg' => '#d3f9d8'],
                        ['title' => 'Total Laporan', 'value' => $laporanHariIni, 'icon' => 'fa-calendar-check','color' => '#f59f00', 'bg' => '#fff3bf'],
                    ];
                @endphp
                @foreach($stats as $stat)
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

            {{-- PROGRESS MAIN CARD --}}
            <div class="row">
                <div class="col-12">
                    <div class="progress-card">

                        {{-- Card Header — single row --}}
                        <div class="progress-card-header">

                            {{-- LEFT: icon + title + driver badge inline --}}
                            <div class="pch-left">
                                <div class="pch-icon"><i class="fas fa-clipboard-check"></i></div>
                                <div class="pch-title-wrap">
                                    <div class="pch-title">
                                        <h5>Progress &amp; Status Pengisian</h5>
                                        <small>{{ $itemTerisi }} dari {{ $totalItem }} item telah diisi hari ini</small>
                                    </div>
                                    {{-- Driver name — compact dot style --}}
                                    <div class="pch-driver-dot">
                                        <span class="dd-dot"></span>
                                        <span class="dd-label">Driver aktif:</span>
                                        <span class="dd-name">
                                            @if(isset($activeDriver) && $activeDriver){{ $activeDriver->name }}@else—@endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: status pill + circular --}}
                            <div class="pch-right">
                                @php
                                    $pillBg   = $persen == 100 ? 'rgba(18,184,134,.22)'   : ($persen > 50 ? 'rgba(245,159,0,.18)'   : 'rgba(224,49,49,.18)');
                                    $pillClr  = $persen == 100 ? '#6ee7c0'                : ($persen > 50 ? '#fde68a'                : '#fca5a5');
                                    $pillBdr  = $persen == 100 ? 'rgba(110,231,192,.4)'   : ($persen > 50 ? 'rgba(253,230,138,.4)'   : 'rgba(252,165,165,.4)');
                                    $pillIcon = $persen == 100 ? 'fa-check-circle'        : ($persen > 50 ? 'fa-clock'               : 'fa-exclamation-circle');
                                    $pillText = $persen == 100 ? 'Lengkap'                : ($persen > 50 ? 'Hampir Selesai'         : 'Belum Diisi');
                                @endphp
                                <div class="pch-status-pill" style="background:{{ $pillBg }}; color:{{ $pillClr }}; border-color:{{ $pillBdr }};">
                                    <i class="fas {{ $pillIcon }}"></i> {{ $pillText }}
                                </div>

                                @php
                                    $circ     = 2 * 3.14159 * 33;
                                    $circFill = ($persen / 100) * $circ;
                                    $circClr  = $persen == 100 ? '#6ee7c0' : ($persen > 50 ? '#fde68a' : '#fca5a5');
                                @endphp
                                <div class="circular-wrap">
                                    <svg width="80" height="80" viewBox="0 0 80 80">
                                        <circle class="circ-bg" cx="40" cy="40" r="33"></circle>
                                        <circle class="circ-bar"
                                            cx="40" cy="40" r="33"
                                            stroke="{{ $circClr }}"
                                            stroke-dasharray="{{ $circFill }} {{ $circ }}">
                                        </circle>
                                    </svg>
                                    <div class="circ-text">{{ $persen }}%</div>
                                </div>
                            </div>

                        </div>

                        {{-- Progress Bar --}}
                        <div class="main-progress-wrap">
                            <div class="main-progress-label">
                                <span>Progress Keseluruhan</span>
                                <span style="color:var(--text); font-weight:700;">{{ $itemTerisi }} / {{ $totalItem }} item</span>
                            </div>
                            <div class="main-bar">
                                @php
                                    $barColor = $persen == 100
                                        ? 'linear-gradient(90deg,#12b886,#0ca678)'
                                        : ($persen > 50
                                            ? 'linear-gradient(90deg,#f59f00,#fcc419)'
                                            : 'linear-gradient(90deg,#e03131,#fa5252)');
                                @endphp
                                <div class="main-bar-fill" style="width:{{ $persen }}%; background:{{ $barColor }};"></div>
                            </div>
                        </div>

                        {{-- Categories --}}
                        <div class="cat-section">
                            <div class="cat-section-title">Detail Per Kategori</div>
                            <div class="row g-3">
                                @php
                                    $categoryMap = [
                                        'DriverItem'  => ['label' => 'Driver',      'icon' => 'fa-id-card',   'color' => '#3b5bdb', 'bg' => '#e8edff'],
                                        'ArmadaItem'  => ['label' => 'Armada',      'icon' => 'fa-truck',     'color' => '#7048e8', 'bg' => '#f3f0ff'],
                                        'Dokument'    => ['label' => 'Dokumen',     'icon' => 'fa-file-alt',  'color' => '#1098ad', 'bg' => '#d0ebff'],
                                        'Environment' => ['label' => 'Environment', 'icon' => 'fa-leaf',      'color' => '#12b886', 'bg' => '#d3f9d8'],
                                        'Safety'      => ['label' => 'Safety',      'icon' => 'fa-shield-alt','color' => '#f59f00', 'bg' => '#fff3bf'],
                                    ];
                                @endphp

                                @foreach($categoryMap as $type => $cat)
                                    @php
                                        $progress   = $typeProgress[$type] ?? ['filled' => 0, 'total' => 0];
                                        $isComplete = $progress['filled'] == $progress['total'] && $progress['total'] > 0;
                                        $pct        = $progress['total'] > 0 ? round(($progress['filled'] / $progress['total']) * 100) : 0;
                                        $remaining  = $progress['total'] - $progress['filled'];
                                        $barFill    = $isComplete ? '#12b886' : ($pct > 50 ? '#f59f00' : '#e03131');
                                        $typeRoutes = [
                                            'DriverItem'  => route('user.daily.driver'),
                                            'ArmadaItem'  => route('user.daily.armada'),
                                            'Dokument'    => route('user.daily.dokument'),
                                            'Environment' => route('user.daily.environment'),
                                            'Safety'      => route('user.daily.safety'),
                                        ];
                                        $itemUrl = $typeRoutes[$type] ?? '#';
                                    @endphp
                                    <div class="col-md-6 col-lg-4">
                                        <a href="{{ $itemUrl }}" class="cat-card {{ $isComplete ? 'is-complete' : 'is-incomplete' }}">
                                            <div class="cat-top">
                                                <div class="cat-icon" style="background:{{ $cat['bg'] }}; color:{{ $cat['color'] }};">
                                                    <i class="fas {{ $cat['icon'] }}"></i>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($isComplete)
                                                        <span class="cat-badge done">
                                                            <i class="fas fa-check-circle"></i> Selesai
                                                        </span>
                                                    @else
                                                        <span class="cat-badge pending">
                                                            <i class="fas fa-exclamation-circle"></i> {{ $remaining }} sisa
                                                        </span>
                                                    @endif
                                                    <span class="cat-arrow"><i class="fas fa-arrow-right"></i></span>
                                                </div>
                                            </div>
                                            <p class="cat-name">{{ $cat['label'] }}</p>
                                            <p class="cat-sub">{{ $progress['filled'] }} dari {{ $progress['total'] }} item &bull; {{ $pct }}%</p>
                                            <div class="cat-bar">
                                                <div class="cat-bar-fill" style="width:{{ $pct }}%; background:{{ $barFill }};"></div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Done alert --}}
                        @if($persen == 100)
                            <div class="done-alert">
                                <div class="done-alert-icon"><i class="fas fa-check"></i></div>
                                <div>
                                    <h6>Semua Data Telah Lengkap!</h6>
                                    <small>Terima kasih telah melengkapi semua laporan hari ini.</small>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection