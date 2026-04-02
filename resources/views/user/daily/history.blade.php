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
        --red:       #e03131;
        --red-lt:    #ffe3e3;
        --yellow:    #f59f00;
        --yellow-lt: #fff3bf;
        --cyan:      #1098ad;
        --cyan-lt:   #d0ebff;
        --purple:    #7048e8;
        --purple-lt: #f3f0ff;
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

    /* ── TOPBAR ──────────────────────────────────── */
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

    /* ── FILTER CARD ─────────────────────────────── */
    .filter-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        padding: 16px 22px; margin-bottom: 24px;
        display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label {
        font-size: .65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted);
    }
    .filter-group input[type="date"] {
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: 8px 12px; font-size: .82rem; font-weight: 600;
        color: var(--text); background: var(--surface);
        font-family: 'Plus Jakarta Sans', sans-serif;
        outline: none; transition: border-color .2s; min-width: 170px;
    }
    .filter-group input[type="date"]:focus { border-color: var(--blue); background: #fff; }

    .btn-filter {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px;
        font-size: .8rem; font-weight: 700; border: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif; transition: all .2s;
        text-decoration: none;
    }
    .btn-filter:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
    .btn-filter.search { background: var(--blue); color: #fff; }
    .btn-filter.export { background: var(--green); color: #fff; }

    /* ── TABLE CARD ──────────────────────────────── */
    .table-card {
        background: var(--card); border-radius: 16px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        overflow: hidden;
    }
    .table-card-header {
        background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        position: relative; overflow: hidden;
    }
    .table-card-header::before {
        content: ''; position: absolute;
        width: 200px; height: 200px; border-radius: 50%;
        border: 40px solid rgba(255,255,255,.04);
        top: -80px; right: -50px; pointer-events: none;
    }
    .table-card-header::after {
        content: ''; position: absolute;
        width: 120px; height: 120px; border-radius: 50%;
        border: 24px solid rgba(255,255,255,.03);
        bottom: -50px; left: 40%; pointer-events: none;
    }
    .tch-left {
        display: flex; align-items: center; gap: 12px;
        position: relative; z-index: 1;
    }
    .tch-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.1);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: .88rem;
    }
    .table-card-header h4 { color: #fff; font-size: .9rem; font-weight: 700; margin: 0; }
    .table-card-header small { color: rgba(255,255,255,.5); font-size: .72rem; display: block; margin-top: 2px; }
    .row-count {
        position: relative; z-index: 1;
        font-size: .72rem; font-weight: 600;
        color: rgba(255,255,255,.6);
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 20px; padding: 4px 12px;
    }

    /* Table scroll */
    .table-scroll-wrap {
        overflow-y: auto; overflow-x: auto;
        max-height: 540px;
    }
    .table-scroll-wrap::-webkit-scrollbar { width: 6px; height: 6px; }
    .table-scroll-wrap::-webkit-scrollbar-track { background: var(--surface); }
    .table-scroll-wrap::-webkit-scrollbar-thumb { background: #c8d0e4; border-radius: 10px; }
    .table-scroll-wrap::-webkit-scrollbar-thumb:hover { background: #9aa3be; }

    /* Table styles */
    .rpt-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .rpt-table thead th {
        position: sticky; top: 0; z-index: 10;
        background: #f0f3fa;
        padding: 11px 14px;
        font-size: .67rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: var(--muted);
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
    }
    .rpt-table tbody tr { transition: background .15s; }
    .rpt-table tbody tr:hover { background: #f6f8ff; }
    .rpt-table tbody tr:nth-child(even) { background: #fafbfd; }
    .rpt-table tbody tr:nth-child(even):hover { background: #f0f3ff; }
    .rpt-table td {
        padding: 11px 14px; font-size: .8rem; color: var(--text);
        border-bottom: 1px solid var(--border); vertical-align: middle;
        white-space: nowrap;
    }
    .rpt-table td.wrap-col { white-space: normal; max-width: 180px; }

    /* Badges */
    .rbadge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px;
        font-size: .67rem; font-weight: 700;
    }
    .rbadge.ok      { background: var(--green-lt); color: #087f5b; }
    .rbadge.ng      { background: var(--red-lt);   color: #c92a2a; }
    .rbadge.unknown { background: #f1f3f5;          color: var(--muted); }
    .rbadge.driver  { background: var(--blue-lt);  color: var(--blue); }
    .rbadge.armada  { background: var(--cyan-lt);  color: var(--cyan); }
    .rbadge.dokumen { background: #f1f3f5;          color: #495057; }
    .rbadge.env     { background: var(--green-lt); color: #087f5b; }
    .rbadge.safety  { background: var(--yellow-lt);color: #7c5a00; }

    /* Local Plus Jakarta Sans font removed: now using Google Fonts CDN only */
    .pagination-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        padding: 14px 20px;
        border-top: 1px solid var(--border);
        background: var(--surface);
    }
    .pagination-info { font-size: .75rem; color: var(--muted); font-weight: 500; }
    .pagination-nav  { display: flex; align-items: center; gap: 6px; }
    .pg-btn {
        width: 32px; height: 32px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .78rem; font-weight: 700; color: var(--muted);
        background: var(--card); border: 1px solid var(--border);
        text-decoration: none; transition: all .18s;
    }
    .pg-btn:hover { background: var(--blue-lt); color: var(--blue); border-color: #b8c8f8; text-decoration: none; }
    .pg-btn.active  { background: var(--blue); color: #fff; border-color: var(--blue); }
    .pg-btn.disabled { opacity: .4; pointer-events: none; }
    .pg-label { font-size: .75rem; color: var(--muted); font-weight: 600; padding: 0 4px; }
</style>

<div class="page-inner">
    <div class="container-fluid" style="max-width: 1300px; margin: 0 auto; padding: 0 20px;">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="topbar-title">
                <h3>Riwayat Laporan Harian</h3>
                <p><i class="fas fa-history me-2"></i>Daftar laporan yang sudah diisi</p>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('user.daily.history') }}">
            <div class="filter-card">
                <div class="filter-group">
                    <label>Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" />
                </div>
                <button class="btn-filter search" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
                <button class="btn-filter export" name="export" value="1" type="submit">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </form>

        {{-- TABLE CARD --}}
        <div class="table-card">

            <div class="table-card-header">
                <div class="tch-left">
                    <div class="tch-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <h4>Daftar Laporan Harian</h4>
                        <small>
                            @if(request('date'))
                                Tanggal {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                            @else
                                Semua tanggal
                            @endif
                        </small>
                    </div>
                </div>
                @if(method_exists($reports, 'total'))
                    <span class="row-count">{{ $reports->total() }} laporan</span>
                @endif
            </div>

            <div class="table-scroll-wrap">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Nama Driver</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $i => $report)
                        <tr>
                            <td style="color:var(--muted); font-size:.72rem;">
                                {{ method_exists($reports, 'firstItem') ? $reports->firstItem() + $i : $i + 1 }}
                            </td>
                            <td style="font-weight:600; white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}
                            </td>
                            <td>
                                @switch($report->subject_type)
                                    @case(App\Models\DriverItem::class)
                                        <span class="rbadge driver"><i class="fas fa-id-card"></i> Driver</span>@break
                                    @case(App\Models\ArmadaItem::class)
                                        <span class="rbadge armada"><i class="fas fa-truck"></i> Armada</span>@break
                                    @case(App\Models\Dokument::class)
                                        <span class="rbadge dokumen"><i class="fas fa-file-alt"></i> Dokumen</span>@break
                                    @case(App\Models\Environment::class)
                                        <span class="rbadge env"><i class="fas fa-leaf"></i> Environment</span>@break
                                    @case(App\Models\Safety::class)
                                        <span class="rbadge safety"><i class="fas fa-shield-alt"></i> Safety</span>@break
                                    @default
                                        <span class="rbadge unknown">Lainnya</span>
                                @endswitch
                            </td>
                            <td style="font-weight:600;">{{ $report->driver_name ?? '—' }}</td>
                            <td class="wrap-col">
                                @if($report->subject)
                                    @if($report->subject->car?->plat_nomor)
                                        <div class="plat-badge">{{ $report->subject->car->plat_nomor }}</div>
                                    @elseif(Auth::user()->plat_nomor)
                                        <div class="plat-badge">{{ Auth::user()->plat_nomor }}</div>
                                    @endif
                                    <div class="item-sub">{{ Str::limit($report->subject->safety_items ?? '', 30) }}</div>
                                    <div class="item-sub">{{ Str::limit($report->subject->standard_items ?? '', 30) }}</div>
                                @else
                                    <span style="color:var(--muted); font-size:.75rem;">#{{ $report->subject_id }}</span>
                                @endif
                            </td>
                            <td>
                                @if($report->status === 'OK')
                                    <span class="rbadge ok"><i class="fas fa-check"></i> OK</span>
                                @elseif($report->status === 'NG')
                                    <span class="rbadge ng"><i class="fas fa-times"></i> NG</span>
                                @else
                                    <span class="rbadge unknown">?</span>
                                @endif
                            </td>
                            <td>
                                @if($report->image_path)
                                    <a href="{{ asset('storage/'.$report->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$report->image_path) }}" alt="evidence" class="img-thumb">
                                    </a>
                                @else
                                    <span style="color:var(--muted); font-size:.75rem;">—</span>
                                @endif
                            </td>
                            <td class="wrap-col" style="font-size:.75rem; color:var(--muted);">
                                {{ $report->notes ?? '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada laporan{{ request('date') ? ' untuk tanggal ini' : '' }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($reports->hasPages())
            <div class="pagination-bar">
                <div class="pagination-info">
                    Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                </div>
                <div class="pagination-nav">
                    @if($reports->onFirstPage())
                        <span class="pg-btn disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a class="pg-btn" href="{{ $reports->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @php
                        $cur  = $reports->currentPage();
                        $last = $reports->lastPage();
                        $range = collect(range(1, $last))->filter(fn($p) =>
                            $p == 1 || $p == $last || abs($p - $cur) <= 1
                        );
                        $prev = null;
                    @endphp
                    @foreach($range as $page)
                        @if($prev && $page - $prev > 1)
                            <span class="pg-label">…</span>
                        @endif
                        <a class="pg-btn {{ $page == $cur ? 'active' : '' }}"
                           href="{{ $reports->url($page) }}">{{ $page }}</a>
                        @php $prev = $page; @endphp
                    @endforeach

                    @if($reports->hasMorePages())
                        <a class="pg-btn" href="{{ $reports->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="pg-btn disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- /table-card --}}

    </div>
</div>
@endsection
