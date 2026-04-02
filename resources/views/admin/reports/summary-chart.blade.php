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
    .page-inner { background: var(--surface); min-height: 100vh; padding-bottom: 40px; }

    /* ── TOPBAR ─────────────────────────────────── */
    .topbar {
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap;
        gap: 16px; padding: 32px 0 24px;
    }
    .topbar-title h3 {
        font-size: 1.45rem; font-weight: 800;
        color: var(--text); margin: 0 0 2px; letter-spacing: -.4px;
    }
    .topbar-title p { font-size: .78rem; color: var(--muted); margin: 0; }

    /* Month picker */
    .month-picker-wrap {
        display: flex; align-items: center; gap: 10px;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 12px; padding: 8px 14px;
        box-shadow: var(--shadow);
    }
    .month-picker-wrap label {
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted); white-space: nowrap;
    }
    .month-picker-wrap input[type="month"] {
        border: none; background: transparent; outline: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .82rem; font-weight: 700; color: var(--text);
        cursor: pointer;
    }

    /* ── STAT CARDS ──────────────────────────────── */
    .stat-card {
        background: var(--card); border-radius: 16px; padding: 20px 22px;
        box-shadow: var(--shadow); border: 1px solid var(--border);
        transition: all .22s; height: 100%;
        display: flex; align-items: center; justify-content: space-between;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .sc-label {
        font-size: .65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted); margin-bottom: 6px;
    }
    .sc-value { font-size: 2rem; font-weight: 800; color: var(--text); line-height: 1; }
    .sc-icon {
        width: 48px; height: 48px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }

    /* ── CHART CARD ──────────────────────────────── */
    .chart-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        overflow: hidden; height: 100%;
    }
    .chart-card-header {
        background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
        padding: 18px 24px;
        display: flex; align-items: center; gap: 12px;
        position: relative; overflow: hidden;
    }
    .chart-card-header::before {
        content: ''; position: absolute;
        width: 180px; height: 180px; border-radius: 50%;
        border: 36px solid rgba(255,255,255,.04);
        top: -70px; right: -50px; pointer-events: none;
    }
    .chart-card-header .ch-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.1);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0; position: relative; z-index: 1;
    }
    .chart-card-header h5 {
        color: #fff; font-size: .9rem; font-weight: 700;
        margin: 0; position: relative; z-index: 1;
    }
    .chart-card-body { padding: 20px; }

    /* Pie legend rows */
    .legend-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .legend-row:last-child { border-bottom: none; }
    .legend-dot {
        width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    }
    .legend-label { font-size: .8rem; font-weight: 600; color: var(--text); flex: 1; margin-left: 10px; }
    .legend-val { font-size: .9rem; font-weight: 800; color: var(--text); }
    .legend-pct {
        font-size: .7rem; font-weight: 600; color: var(--muted);
        margin-left: 6px; background: var(--surface);
        border-radius: 20px; padding: 2px 8px;
    }

    /* OK/NG ratio bar */
    .ratio-bar-wrap { margin-top: 18px; }
    .ratio-bar-label {
        display: flex; justify-content: space-between;
        font-size: .7rem; font-weight: 700; color: var(--muted);
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px;
    }
    .ratio-bar {
        height: 10px; border-radius: 20px; overflow: hidden;
        background: var(--red-lt); display: flex;
    }
    .ratio-bar-ok {
        background: linear-gradient(90deg, #12b886, #0ca678);
        border-radius: 20px; transition: width 1s ease-in-out;
    }
</style>

<div class="page-inner">
    <div class="container-fluid" style="max-width: 1260px; margin: 0 auto; padding: 0 20px;">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="topbar-title">
                <h3>Chart Overview</h3>
                <p><i class="fas fa-chart-pie me-2"></i>Distribusi status laporan keseluruhan</p>
            </div>
            <form method="GET">
                <div class="month-picker-wrap">
                    <label><i class="fas fa-calendar-alt me-1"></i> Bulan</label>
                    <input type="month" name="month"
                           value="{{ request('month', now()->format('Y-m')) }}"
                           onchange="this.form.submit()">
                </div>
            </form>
        </div>

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">
            @php
                $statItems = [
                    ['label' => 'Status OK',          'value' => $summary['OK'] ?? 0,      'icon' => 'fa-check-circle',   'color' => '#12b886', 'bg' => '#d3f9d8'],
                    ['label' => 'Status NG',          'value' => $summary['NG'] ?? 0,      'icon' => 'fa-times-circle',   'color' => '#e03131', 'bg' => '#ffe3e3'],
                    ['label' => 'Belum Dicek',        'value' => $summary['UNKNOWN'] ?? 0, 'icon' => 'fa-question-circle','color' => '#8592a8', 'bg' => '#f1f3f5'],
                    ['label' => 'Total Laporan',      'value' => $totalReports,             'icon' => 'fa-clipboard-list', 'color' => '#3b5bdb', 'bg' => '#e8edff'],
                ];
            @endphp
            @foreach($statItems as $s)
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div>
                        <div class="sc-label">{{ $s['label'] }}</div>
                        <div class="sc-value">{{ $s['value'] }}</div>
                    </div>
                    <div class="sc-icon" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }};">
                        <i class="fas {{ $s['icon'] }}"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CHART + SUMMARY ROW --}}
        <div class="row g-3">

            {{-- Pie Chart --}}
            <div class="col-md-7">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div class="ch-icon"><i class="fas fa-chart-pie"></i></div>
                        <h5>Distribusi Status Laporan</h5>
                    </div>
                    <div class="chart-card-body">
                        <div id="pieChart"></div>
                    </div>
                </div>
            </div>

            {{-- Summary panel --}}
            <div class="col-md-5">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div class="ch-icon"><i class="fas fa-chart-bar"></i></div>
                        <h5>Ringkasan Statistik</h5>
                    </div>
                    <div class="chart-card-body">

                        @php
                            $total   = $totalReports ?: 1;
                            $okPct   = round(($summary['OK']      ?? 0) / $total * 100, 1);
                            $ngPct   = round(($summary['NG']      ?? 0) / $total * 100, 1);
                            $unkPct  = round(($summary['UNKNOWN'] ?? 0) / $total * 100, 1);
                        @endphp

                        {{-- Legend rows --}}
                        <div class="legend-row">
                            <div class="legend-dot" style="background:#12b886;"></div>
                            <span class="legend-label">OK</span>
                            <span class="legend-val">{{ $summary['OK'] ?? 0 }}</span>
                            <span class="legend-pct">{{ $okPct }}%</span>
                        </div>
                        <div class="legend-row">
                            <div class="legend-dot" style="background:#e03131;"></div>
                            <span class="legend-label">NG</span>
                            <span class="legend-val">{{ $summary['NG'] ?? 0 }}</span>
                            <span class="legend-pct">{{ $ngPct }}%</span>
                        </div>
                        <div class="legend-row">
                            <div class="legend-dot" style="background:#8592a8;"></div>
                            <span class="legend-label">Belum Dicek</span>
                            <span class="legend-val">{{ $summary['UNKNOWN'] ?? 0 }}</span>
                            <span class="legend-pct">{{ $unkPct }}%</span>
                        </div>
                        <div class="legend-row" style="border-bottom:none; padding-top:14px;">
                            <div class="legend-dot" style="background:#3b5bdb;"></div>
                            <span class="legend-label" style="font-weight:800;">Total</span>
                            <span class="legend-val" style="color:var(--blue);">{{ $totalReports }}</span>
                            <span class="legend-pct">100%</span>
                        </div>

                        {{-- OK vs NG ratio bar --}}
                        <div class="ratio-bar-wrap">
                            <div class="ratio-bar-label">
                                <span style="color:#12b886;">OK {{ $okPct }}%</span>
                                <span style="color:#e03131;">NG {{ $ngPct }}%</span>
                            </div>
                            <div class="ratio-bar">
                                <div class="ratio-bar-ok" style="width:{{ $okPct }}%;"></div>
                            </div>
                            <div style="margin-top:8px; font-size:.72rem; color:var(--muted); text-align:center;">
                                Rasio OK vs NG dari total laporan bulan ini
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<script>
var pieOptions = {
    series: [
        {{ $summary['OK'] ?? 0 }},
        {{ $summary['NG'] ?? 0 }},
        {{ $summary['UNKNOWN'] ?? 0 }}
    ],
    chart: {
        type: 'donut',
        height: 360,
        fontFamily: "'Plus Jakarta Sans', sans-serif",
        toolbar: { show: false }
    },
    labels: ['OK', 'NG', 'Belum Dicek'],
    colors: ['#12b886', '#e03131', '#adb5bd'],
    dataLabels: {
        enabled: true,
        style: { fontSize: '13px', fontWeight: 700, fontFamily: "'Plus Jakarta Sans', sans-serif" },
        formatter: function(val) { return val.toFixed(1) + '%' }
    },
    plotOptions: {
        pie: {
            donut: {
                size: '62%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total',
                        fontSize: '13px',
                        fontWeight: 700,
                        color: '#8592a8',
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        formatter: function(w) {
                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        }
                    },
                    value: {
                        fontSize: '22px',
                        fontWeight: 800,
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        color: '#1a2035'
                    }
                }
            }
        }
    },
    legend: {
        show: false
    },
    stroke: { width: 3, colors: ['#f4f6fb'] },
    tooltip: {
        style: { fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '13px' }
    }
};

var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
pieChart.render();
</script>
@endsection
