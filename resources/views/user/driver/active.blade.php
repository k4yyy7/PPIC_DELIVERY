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
        --green-dk:  #0ca678;
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

    /* ── TOPBAR ──────────────────────────────────── */
    .topbar {
        display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap;
        gap: 16px; padding: 32px 0 24px;
    }
    .topbar-title h3 {
        font-size: 1.45rem; font-weight: 800;
        color: var(--text); margin: 0 0 4px; letter-spacing: -.4px;
    }
    .topbar-date {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--card); border: 1px solid var(--border);
        border-radius: 30px; padding: 5px 14px;
        font-size: .75rem; font-weight: 600; color: var(--muted);
        box-shadow: var(--shadow);
    }
    .topbar-date i { color: var(--blue); }

    /* ── FORM CARD ───────────────────────────────── */
    .form-card {
        background: var(--card); border-radius: 18px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        overflow: hidden; height: 100%;
    }
    .form-card-header {
        background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
        padding: 20px 26px;
        display: flex; align-items: center; gap: 12px;
        position: relative; overflow: hidden;
    }
    .form-card-header::before {
        content: ''; position: absolute;
        width: 180px; height: 180px; border-radius: 50%;
        border: 36px solid rgba(255,255,255,.04);
        top: -70px; right: -50px; pointer-events: none;
    }
    .fch-icon {
        width: 40px; height: 40px; border-radius: 11px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.1);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0; position: relative; z-index: 1;
    }
    .form-card-header h5 {
        color: #fff; font-size: .92rem; font-weight: 700;
        margin: 0; position: relative; z-index: 1;
    }
    .form-card-body { padding: 26px; }

    /* Alert success */
    .alert-ok {
        display: flex; align-items: center; gap: 12px;
        background: var(--green-lt); border: 1px solid #a7f3d0;
        border-radius: 12px; padding: 13px 16px; margin-bottom: 22px;
    }
    .alert-ok-icon {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--green); color: #fff; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem;
    }
    .alert-ok span { font-size: .82rem; font-weight: 600; color: #065f46; }

    /* Select group */
    .select-group { margin-bottom: 22px; }
    .select-group label {
        display: block; font-size: .65rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .7px;
        color: var(--muted); margin-bottom: 7px;
    }
    .select-wrap {
        display: flex; align-items: center;
        border: 1.5px solid var(--border); border-radius: 12px;
        background: var(--surface); overflow: hidden;
        transition: border-color .2s, box-shadow .2s;
    }
    .select-wrap:focus-within {
        border-color: var(--green); background: #fff;
        box-shadow: 0 0 0 3px rgba(18,184,134,.12);
    }
    .select-wrap .sw-icon {
        width: 44px; height: 46px; display: flex; align-items: center; justify-content: center;
        color: var(--green); font-size: .9rem; flex-shrink: 0;
        border-right: 1.5px solid var(--border); background: rgba(18,184,134,.06);
    }
    .select-wrap select {
        flex: 1; border: none; background: transparent; outline: none;
        padding: 11px 14px; font-size: .84rem; font-weight: 600;
        color: var(--text); font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
    }

    /* Submit btn */
    .btn-submit {
        width: 100%; padding: 13px;
        background: linear-gradient(135deg, var(--green), var(--green-dk));
        color: #fff; border: none; border-radius: 12px;
        font-size: .88rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; transition: all .22s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 14px rgba(18,184,134,.3);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(18,184,134,.4);
    }
    .btn-submit:active { transform: translateY(0); }

    /* ── ACTIVE DRIVER CARD ──────────────────────── */
    .driver-card {
        background: var(--card); border-radius: 18px;
        border: 1px solid var(--border); box-shadow: var(--shadow);
        overflow: hidden; height: 100%;
    }
    .driver-card-banner {
        background: linear-gradient(135deg, #1e2540 0%, #2d3660 100%);
        padding: 24px 26px 20px;
        position: relative; overflow: hidden;
    }
    .driver-card-banner::before {
        content: ''; position: absolute;
        width: 160px; height: 160px; border-radius: 50%;
        border: 32px solid rgba(255,255,255,.04);
        top: -60px; right: -40px; pointer-events: none;
    }
    .driver-card-banner::after {
        content: ''; position: absolute;
        width: 100px; height: 100px; border-radius: 50%;
        border: 20px solid rgba(18,184,134,.1);
        bottom: -40px; left: 30%; pointer-events: none;
    }
    .driver-avatar {
        width: 56px; height: 56px; border-radius: 50%;
        background: linear-gradient(135deg, var(--green), var(--green-dk));
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.3rem; flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(18,184,134,.4);
        position: relative; z-index: 1;
    }
    .driver-banner-info { position: relative; z-index: 1; }
    .driver-banner-info .dbi-label {
        font-size: .63rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .8px; color: rgba(255,255,255,.5); margin-bottom: 3px;
    }
    .driver-banner-info .dbi-name {
        font-size: 1.15rem; font-weight: 800; color: #fff; line-height: 1.1;
    }
    .driver-active-badge {
        position: relative; z-index: 1;
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(18,184,134,.2); border: 1px solid rgba(18,184,134,.35);
        border-radius: 20px; padding: 4px 12px;
        font-size: .68rem; font-weight: 700; color: #6ee7c0;
        margin-top: 10px;
    }
    .driver-active-badge .pulse-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--green); animation: pulseGreen 2s infinite; flex-shrink: 0;
    }
    @keyframes pulseGreen {
        0%   { box-shadow: 0 0 0 0 rgba(18,184,134,.6); }
        70%  { box-shadow: 0 0 0 6px rgba(18,184,134,0); }
        100% { box-shadow: 0 0 0 0 rgba(18,184,134,0); }
    }

    /* Driver detail rows */
    .driver-details { padding: 22px 26px; }
    .detail-row {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 0; border-bottom: 1px solid var(--border);
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .82rem; flex-shrink: 0;
    }
    .detail-label { font-size: .68rem; font-weight: 600; color: var(--muted); margin-bottom: 2px; text-transform: uppercase; letter-spacing: .5px; }
    .detail-value { font-size: .88rem; font-weight: 700; color: var(--text); }

    /* Empty card */
    .empty-card {
        background: var(--card); border-radius: 18px;
        border: 2px dashed var(--border); box-shadow: none;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 50px 30px; text-align: center; height: 100%;
        min-height: 240px;
    }
    .empty-card-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: var(--surface); border: 2px dashed var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: var(--muted); margin-bottom: 16px;
    }
    .empty-card h6 { font-size: .88rem; font-weight: 700; color: var(--text); margin: 0 0 6px; }
    .empty-card p  { font-size: .78rem; color: var(--muted); margin: 0; }
</style>

<div class="page-inner">
    <div class="container-fluid" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="topbar-title">
                <h3>Manajemen Driver Aktif</h3>
                <div class="topbar-date">
                    <i class="fas fa-calendar-alt"></i>
                    Operasional: <strong style="color:var(--text);">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</strong>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- FORM PILIH DRIVER --}}
            <div class="col-12 col-lg-6">
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-user-cog"></i></div>
                        <h5>Pilih Driver Hari Ini</h5>
                    </div>
                    <div class="form-card-body">

                        @if(session('success'))
                            <div class="alert-ok">
                                <div class="alert-ok-icon"><i class="fas fa-check"></i></div>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.driver.active.store', ['date' => $date]) }}">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}">

                            <div class="select-group">
                                <label>Nama Driver</label>
                                <div class="select-wrap">
                                    <div class="sw-icon"><i class="fas fa-id-card"></i></div>
                                    <select name="driver_id" required>
                                        <option value="">— Pilih Driver Aktif —</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}"
                                                @if(optional($active)->driver_id == $driver->id) selected @endif>
                                                {{ $driver->name }}@if($driver->license_no) · SIM: {{ $driver->license_no }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button class="btn-submit" type="submit">
                                <i class="fas fa-check-circle"></i> Konfirmasi Driver
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DRIVER AKTIF INFO --}}
            <div class="col-12 col-lg-6">
                @if($active && $active->driver)
                    <div class="driver-card">
                        <div class="driver-card-banner">
                            <div class="d-flex align-items-center gap-3 mb-1">
                                <div class="driver-avatar">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="driver-banner-info">
                                    <div class="dbi-label">Driver Aktif Saat Ini</div>
                                    <div class="dbi-name">{{ $active->driver->name }}</div>
                                </div>
                            </div>
                            <div class="driver-active-badge">
                                <span class="pulse-dot"></span>
                                Sedang Bertugas
                            </div>
                        </div>
                        <div class="driver-details">
                            <div class="detail-row">
                                <div class="detail-icon" style="background:#e8edff; color:#3b5bdb;">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <div class="detail-label">Nomor SIM</div>
                                    <div class="detail-value">{{ $active->driver->license_no ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-icon" style="background:#d3f9d8; color:#12b886;">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="detail-label">Kontak / HP</div>
                                    <div class="detail-value">{{ $active->driver->phone ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-icon" style="background:#fff3bf; color:#f59f00;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div class="detail-label">Tanggal Tugas</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-card">
                        <div class="empty-card-icon">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <h6>Belum Ada Driver Dipilih</h6>
                        <p>Pilih driver aktif untuk hari ini<br>menggunakan form di sebelah kiri.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
