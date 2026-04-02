@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('landingpage/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
@endpush

@section('content')
    <div class="landing-offline">

    {{-- ═══ HEADER ═══ --}}
    <header class="lp-header">
        <img src="{{ asset('landingpage/img/sakura1.png') }}" class="lp-logo" alt="Logo">
        <div class="lp-header-center">
            <h1 id="headerSlider" class="slide-in">SAFETY DELIVERY REPORT</h1>
        </div>
        {{-- HEADER RIGHT --}}
        <div class="lp-header-right">
            <div class="live-badge">
                <span class="pulse-dot"></span>
                <span class="live-text">Live Monitoring</span>
            </div>
            <a href="{{ route('login') }}" class="btn-login" title="Login">
                <i class="fa-solid fa-user"></i>
            </a>
        </div>
    </header>

    {{-- ═══ BODY ═══ --}}
    <div class="lp-body">

        {{-- STAT CARDS --}}
        <div class="stat-row">
            {{-- Card 1: Total Driver --}}
            <div class="stat-card" style="--accent: #2563eb; --accent-glow: rgba(37,99,235,.18);">
                <div class="sc-left">
                    <div class="sc-label">Total Driver</div>
                    <div class="sc-value" id="qs-driver">{{ $totalDriver ?? 0 }}</div>
                </div>
                <div class="sc-icon" style="background: var(--blue-lt); color: var(--blue);">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="sc-shine"></div>
            </div>

            {{-- Card 3: Total Item --}}
            <div class="stat-card" style="--accent: #10b981; --accent-glow: rgba(16,185,129,.18);">
                <div class="sc-left">
                    <div class="sc-label">Total Item</div>
                    <div class="sc-value" id="qs-item">{{ $totalItem ?? 0 }}</div>
                </div>
                <div class="sc-icon" style="background: var(--green-lt); color: var(--green);">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div class="sc-shine"></div>
            </div>

            {{-- Card 2: Armada Login --}}
            <div class="stat-card" style="--accent: #0e9f7e; --accent-glow: rgba(14,159,126,.18);">
                <div class="sc-left">
                    <div class="sc-label">Driver Login Hari Ini</div>
                    <div class="sc-value" id="qs-armada">{{ $armadaLoginCount ?? 0 }}</div>
                </div>
                <div class="sc-icon" style="background: var(--teal-lt); color: var(--teal);">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div class="sc-shine"></div>
            </div>

            {{-- Card 4: Laporan Hari Ini --}}
            <div class="stat-card" style="--accent: #f59e0b; --accent-glow: rgba(245,158,11,.18);">
                <div class="sc-left">
                    <div class="sc-label">Laporan Hari Ini</div>
                    <div class="sc-value" id="qs-laporan">{{ $todayReports ?? 0 }}</div>
                </div>
                <div class="sc-icon" style="background: var(--amber-lt); color: var(--amber);">
                    <i class="fa-solid fa-file-alt"></i>
                </div>
                <div class="sc-shine"></div>
            </div>
        </div>

        {{-- STATUS BANNER --}}
        <div class="status-banner">
            <div class="sb-left">
                <div class="sb-icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <div class="sb-label">Update Laporan Hari Ini</div>
                    <div class="sb-value">
                        <i class="fa-solid fa-check-circle"></i>
                        <span id="banner-laporan">{{ $todayReports ?? 0 }}</span>
                        <span class="sb-value-text">Laporan Telah Terbit</span>
                    </div>
                </div>
            </div>
            <div class="live-op-badge">
                <span class="pulse-dot"></span>
                <span class="lob-text">Live Operational</span>
            </div>
        </div>

        {{-- BOTTOM ROW --}}
        <div class="bottom-row">

            {{-- ── ARMADA FEED ── --}}
            <div class="bottom-card">
                <div class="feed-head">
                    <div class="fh-left">
                        <div class="fh-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <span class="fh-title">Monitoring Armada</span>
                    </div>
                    <span class="feed-pill" id="armada-count">{{ count($platAggregates) }} Units</span>
                </div>

                {{-- Plat marquee strip --}}
                @if (count($platAggregates) > 0)
                    <div class="plat-strip">
                        <div class="plat-strip-inner">
                            @php $platList = array_keys($platAggregates); @endphp
                            @for ($i = 0; $i < 3; $i++)
                                @foreach ($platList as $idx => $plat)
                                    <span class="plat-chip{{ $idx === count($platList) - 1 ? ' no-gap' : '' }}"
                                        aria-hidden="{{ $i > 0 ? 'true' : 'false' }}">
                                        <i class="fa-solid fa-circle"></i>
                                        {{ $plat }}
                                    </span>
                                @endforeach
                            @endfor
                        </div>
                    </div>
                @endif

                <div class="feed-scroll-area" id="feedWrapper">
                    <div class="feed-scroll-inner" id="feedScroll">
                        @forelse($platAggregates as $plat => $data)
                            <div class="feed-item">
                                <div class="fi-top">
                                    <div class="fi-plat">
                                        <span class="fi-dot"></span>
                                        {{ $plat }}
                                    </div>
                                    <div class="ai-badges">
                                        <span class="ai-ok">{{ $data['ok'] }} OK</span>
                                        <span class="ai-ng">{{ $data['ng'] }} NG</span>
                                    </div>
                                </div>
                                <div class="fi-meta">
                                    <span>
                                        <i class="fa-solid fa-user"></i>
                                        {{ $data['driver_name'] ?? 'No Driver' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="feed-empty">
                                <i class="fa-solid fa-truck"></i>
                                <p>Belum ada data armada hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="feed-foot">
                    <div class="ff-info">
                        <i class="fa-solid fa-rotate"></i> Refresh 4s
                    </div>
                    <label class="toggle-wrap" for="autoScrollToggle">
                        <input type="checkbox" id="autoScrollToggle">
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        <span class="toggle-label">Auto Scroll</span>
                    </label>
                </div>
            </div>

            {{-- ── LINE CHART ── --}}
            <div class="bottom-card">
                <div class="chart-head">
                    <div class="ch-left">
                        <div class="ch-icon">
                            <i class="fa-solid fa-chart-area"></i>
                        </div>
                        <h6>Tren Armada per Hari (OK / NG / Belum Dicek)</h6>
                    </div>
                    <small id="weekBadge">{{ $weekLabel ?? 'Minggu Ini' }}</small>
                </div>

                <div class="chart-legend-row">
                    <div class="cl-item">
                        <span class="cl-line" style="background:linear-gradient(90deg,#0e9f7e,#34d399);"></span> OK
                    </div>
                    <div class="cl-item">
                        <span class="cl-line" style="background:linear-gradient(90deg,#e53935,#f87171);"></span> NG
                    </div>
                    <div class="cl-item">
                        <span class="cl-line cl-dashed" style="background:#94a3b8;"></span> Belum Dicek
                    </div>
                    <div style="margin-left:auto; display:flex; gap:5px;">
                        <button onclick="changeWeek(1)" id="prevBtn" class="ch-nav-btn" title="Minggu sebelumnya">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button onclick="changeWeek(-1)" id="nextBtn" class="ch-nav-btn" disabled
                            title="Minggu berikutnya">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="chart-body">
                    <div id="lineChart"></div>
                </div>

                <div class="chart-stats-row">
                    <div class="chart-stat-item">
                        <div class="csi-label">Total OK</div>
                        <div class="csi-val" id="stat-ok" style="color:#0e9f7e;">{{ $todayOk ?? 0 }}</div>
                    </div>
                    <div class="chart-stat-item">
                        <div class="csi-label">Total NG</div>
                        <div class="csi-val" id="stat-ng" style="color:#e53935;">{{ $todayNg ?? 0 }}</div>
                    </div>
                    <div class="chart-stat-item">
                        <div class="csi-label">Belum Dicek</div>
                        <div class="csi-val" id="stat-unk" style="color:#94a3b8;">{{ $todayUnknown ?? 0 }}</div>
                    </div>
                    <div class="chart-stat-item">
                        <div class="csi-label">Total Item</div>
                        <div class="csi-val" id="stat-total" style="color:var(--navy);">
                            {{ ($todayOk ?? 0) + ($todayNg ?? 0) + ($todayUnknown ?? 0) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══ FOOTER ═══ --}}
    <footer class="lp-footer">
       <div class="marquee-wrap">
    <span class="marquee-inner">
        <strong>SAFETY DELIVERY REPORT</strong> &nbsp;&bull;&nbsp;
        <span style="font-weight: 800; text-transform: uppercase;">PT Sakura Java Indonesia</span> &nbsp;&bull;&nbsp;
        Real-Time Monitoring System &nbsp;&bull;&nbsp;
        <i>"Safety First, Delivery Fast"</i> &nbsp;&bull;&nbsp;
        Keselamatan Kerja Adalah Prioritas Utama Kami &nbsp;&bull;&nbsp;
        <strong>Zero Accident, Zero Delay</strong>
    </span>
</div>
    </footer>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ════════════════════════════════════════
               HEADER SLIDER
            ════════════════════════════════════════ */
            function pad2(n) { return n < 10 ? '0' + n : '' + n; }

            const months = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI',
                            'JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
            const days   = ['MINGGU','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'];

            const slides = [
                () => 'SAFETY DELIVERY REPORT',
                () => {
                    const d = new Date();
                    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
                },
                () => {
                    const d = new Date();
                    return `${days[d.getDay()]}, ${pad2(d.getHours())}:${pad2(d.getMinutes())}:${pad2(d.getSeconds())}`;
                }
            ];

            let sIdx = 0, isAnim = false;
            const sliderEl = document.getElementById('headerSlider');

            function runSlider() {
                if (isAnim) return;
                isAnim = true;
                sliderEl.classList.add('slide-out');
                sliderEl.classList.remove('slide-in');
                setTimeout(() => {
                    sIdx = (sIdx + 1) % 3;
                    sliderEl.textContent = slides[sIdx]();
                    sliderEl.classList.remove('slide-out');
                    sliderEl.classList.add('slide-in');
                    setTimeout(() => { isAnim = false; }, 450);
                    setTimeout(runSlider, sIdx === 0 ? 6000 : 3000);
                }, 450);
            }
            setTimeout(runSlider, 6000);
            setInterval(() => {
                if (sIdx === 2 && !isAnim) sliderEl.textContent = slides[2]();
            }, 1000);


            /* ════════════════════════════════════════
               APEX CHART — Area 3D Premium
            ════════════════════════════════════════ */
            const font           = "'Poppins', sans-serif";
            const chartContainer = document.querySelector('#lineChart');
            let chartL           = null;

            if (chartContainer) {
                chartL = new ApexCharts(chartContainer, {
                    chart: {
                        type: 'area',
                        height: '100%',
                        toolbar: { show: false },
                        background: 'transparent',
                        animations: { enabled: true, easing: 'easeinout', speed: 700 },
                        fontFamily: font,
                        dropShadow: {
                            enabled: true,
                            enabledOnSeries: [0, 1],
                            top: 6,
                            left: 0,
                            blur: 10,
                            color: ['#0e9f7e', '#e53935'],
                            opacity: 0.35
                        },
                        zoom: { enabled: false }
                    },
                    series: [
                        { name: 'OK',          data: @json($okData ?? []) },
                        { name: 'NG',          data: @json($ngData ?? []) },
                        { name: 'Belum Dicek', data: @json($unknownData ?? []) }
                    ],
                    colors: ['#0e9f7e', '#e53935', '#94a3b8'],
                    stroke: {
                        curve: 'smooth',
                        width: [3, 3, 2],
                        dashArray: [0, 0, 5],
                        lineCap: 'round'
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            type: 'vertical',
                            shadeIntensity: 1,
                            gradientToColors: ['#0e9f7e', '#e53935', '#94a3b8'],
                            inverseColors: false,
                            opacityFrom: [0.55, 0.45, 0.15],
                            opacityTo: [0.03, 0.03, 0.0],
                            stops: [0, 88, 100]
                        }
                    },
                    markers: {
                        size: [4, 4, 3],
                        strokeWidth: 2,
                        strokeColors: ['#fff', '#fff', '#fff'],
                        colors: ['#0e9f7e', '#e53935', '#94a3b8'],
                        hover: { size: 7, sizeOffset: 2 }
                    },
                    xaxis: {
                        categories: @json($lineLabels ?? []),
                        axisBorder: { show: false },
                        axisTicks:  { show: false },
                        labels: { style: { fontSize: '10px', colors: '#94a3b8', fontFamily: font } }
                    },
                    yaxis: {
                        min: 0,
                        forceNiceScale: true,
                        labels: {
                            style: { fontSize: '10px', colors: '#94a3b8', fontFamily: font },
                            formatter: val => Math.round(val)
                        }
                    },
                    grid: {
                        borderColor: '#e2e8f0',
                        strokeDashArray: 4,
                        padding: { left: 8, right: 8, top: -10 },
                        xaxis: { lines: { show: false } },
                        yaxis: { lines: { show: true } }
                    },
                    dataLabels: { enabled: false },
                    legend: { show: false },
                    tooltip: {
                        theme: 'light',
                        style: { fontFamily: font },
                        shared: true,
                        intersect: false,
                        x: { formatter: val => 'Tgl ' + val },
                        y: { formatter: val => val + ' unit' }
                    }
                });
                chartL.render();
            }

            function updateStats(ok, ng, unk) {
                const o = ok.reduce((a, b) => a + b, 0);
                const n = ng.reduce((a, b) => a + b, 0);
                const u = unk.reduce((a, b) => a + b, 0);
                document.getElementById('stat-ok').textContent    = o;
                document.getElementById('stat-ng').textContent    = n;
                document.getElementById('stat-unk').textContent   = u;
                document.getElementById('stat-total').textContent = o + n + u;
            }

            /* ── WEEK NAV ── */
            let offset      = 0;
            const weekBadge = document.getElementById('weekBadge');
            const nextBtn   = document.getElementById('nextBtn');

            window.changeWeek = function (dir) {
                offset += dir;
                if (offset < 0) offset = 0;
                nextBtn.disabled      = (offset === 0);
                weekBadge.textContent = 'Loading...';
                fetch(`{{ route('landing.live') }}?offset=${offset}`)
                    .then(r => r.json())
                    .then(d => {
                        if (chartL) {
                            chartL.updateOptions({ xaxis: { categories: d.lineLabels ?? [] } });
                            chartL.updateSeries([
                                { name: 'OK',          data: d.okData      ?? [] },
                                { name: 'NG',          data: d.ngData      ?? [] },
                                { name: 'Belum Dicek', data: d.unknownData ?? [] }
                            ]);
                        }
                        weekBadge.textContent = d.weekLabel ?? 'Minggu Ini';
                        updateStats(d.okData ?? [], d.ngData ?? [], d.unknownData ?? []);
                    })
                    .catch(() => { weekBadge.textContent = 'Error'; });
            };


            /* ════════════════════════════════════════
               FEED SCROLL
               — seamless infinite loop, no-jump, smooth
               — scroll HANYA aktif jika entries.length > 4
            ════════════════════════════════════════ */
            const feedWrapper = document.getElementById('feedWrapper');
            const feedScroll  = document.getElementById('feedScroll');
            const toggleEl    = document.getElementById('autoScrollToggle');

            function getFeedSpeed() {
                if (window.innerWidth <= 480) return 20;
                if (window.innerWidth <= 820) return 28;
                return 38;
            }

            let SPEED         = getFeedSpeed();
            let scrollEnabled = false;
            let isHovered     = false;
            let posY          = 0;
            let loopH         = 0;
            let lastTs        = null;

            function buildItemsHTML(entries) {
                if (!entries.length) return `
                    <div class="feed-empty">
                        <i class="fa-solid fa-truck"></i>
                        <p>Belum ada data armada hari ini</p>
                    </div>`;
                return entries.map(([plat, data]) => `
                    <div class="feed-item">
                        <div class="fi-top">
                            <div class="fi-plat"><span class="fi-dot"></span>${plat}</div>
                            <div class="ai-badges">
                                <span class="ai-ok">${data.ok} OK</span>
                                <span class="ai-ng">${data.ng} NG</span>
                            </div>
                        </div>
                        <div class="fi-meta">
                            <span><i class="fa-solid fa-user"></i> ${data.driver_name ?? 'No Driver'}</span>
                        </div>
                    </div>`).join('');
            }

            function calcRepeat(singleH, areaH) {
                if (singleH <= 0 || areaH <= 0) return 3;
                return Math.max(Math.ceil((areaH * 2.5) / singleH), 3);
            }

            function updateFeedContent(entries, shouldScroll) {
                document.getElementById('armada-count').textContent = entries.length + ' Units';

                if (!shouldScroll || !entries.length) {
                    feedScroll.innerHTML = buildItemsHTML(entries);
                    feedScroll.style.transform = 'translateY(0) translateZ(0)';
                    posY  = 0;
                    loopH = 0;
                    return;
                }

                const singleHTML = buildItemsHTML(entries);
                feedScroll.innerHTML = singleHTML;

                requestAnimationFrame(() => {
                    const areaH   = feedWrapper.offsetHeight;
                    const singleH = feedScroll.scrollHeight;
                    const repeat  = calcRepeat(singleH, areaH);

                    let setHTML = '';
                    for (let i = 0; i < repeat; i++) setHTML += singleHTML;

                    feedScroll.innerHTML = setHTML + setHTML;

                    requestAnimationFrame(() => {
                        const newLoopH = feedScroll.scrollHeight / 2;
                        if (newLoopH > 0) {
                            posY  = loopH > 0 ? posY % newLoopH : 0;
                            loopH = newLoopH;
                        }
                        feedScroll.style.transform = `translateY(-${posY}px) translateZ(0)`;
                    });
                });
            }

            function animLoop(ts) {
                if (lastTs !== null && scrollEnabled && !isHovered && loopH > 0) {
                    const delta = (ts - lastTs) / 1000;
                    posY += SPEED * delta;
                    if (posY >= loopH) posY -= loopH;
                    feedScroll.style.transform = `translateY(-${posY}px) translateZ(0)`;
                }
                lastTs = ts;
                requestAnimationFrame(animLoop);
            }
            requestAnimationFrame(animLoop);

            function setScrollEnabled(val) {
                scrollEnabled    = val;
                toggleEl.checked = val;
            }

            const initEntries      = Object.entries(@json($platAggregates));
            const initShouldScroll = initEntries.length >= 4;

            updateFeedContent(initEntries, initShouldScroll);
            setScrollEnabled(initShouldScroll);
            toggleEl.disabled = !initShouldScroll;

            toggleEl.addEventListener('change', () => {
                if (toggleEl.checked && loopH > 0) setScrollEnabled(true);
                else setScrollEnabled(false);
            });
            feedWrapper.addEventListener('mouseenter', () => { isHovered = true;  });
            feedWrapper.addEventListener('mouseleave', () => { isHovered = false; });
            window.addEventListener('resize', () => {
                SPEED = getFeedSpeed();
                if (chartL) chartL.render();
            });


            /* ════════════════════════════════════════
               PLAT MARQUEE — rAF seamless
            ════════════════════════════════════════ */
            (function initPlatMarquee() {
                const strip = document.querySelector('.plat-strip');
                const inner = document.querySelector('.plat-strip-inner');
                if (!strip || !inner) return;

                const PLAT_SPEED = 30;
                let platPosX   = 0;
                let platLoopW  = 0;
                let platLastTs = null;

                function setupPlatClone() {
                    inner.querySelectorAll('.plat-clone').forEach(el => el.remove());

                    const originalChips = Array.from(inner.children);
                    if (!originalChips.length) return;

                    const singleW = inner.scrollWidth;
                    const stripW  = strip.offsetWidth;
                    if (singleW <= 0) return;

                    const repeat = Math.max(Math.ceil((stripW * 2.5) / singleW), 2);
                    for (let r = 0; r < repeat; r++) {
                        originalChips.forEach(chip => {
                            const clone = chip.cloneNode(true);
                            clone.classList.add('plat-clone');
                            clone.setAttribute('aria-hidden', 'true');
                            inner.appendChild(clone);
                        });
                    }

                    platLoopW = inner.scrollWidth / (1 + repeat);
                    platPosX  = platPosX % platLoopW;
                    inner.style.transform = `translateX(-${platPosX}px) translateZ(0)`;
                }

                setupPlatClone();
                window.addEventListener('resize', setupPlatClone);

                function platLoop(ts) {
                    if (platLastTs !== null && platLoopW > 0) {
                        const delta = (ts - platLastTs) / 1000;
                        platPosX += PLAT_SPEED * delta;
                        if (platPosX >= platLoopW) platPosX -= platLoopW;
                        inner.style.transform = `translateX(-${platPosX}px) translateZ(0)`;
                    }
                    platLastTs = ts;
                    requestAnimationFrame(platLoop);
                }
                requestAnimationFrame(platLoop);
            })();


                        /* ════════════════════════════════════════
               PLAT STRIP LIVE UPDATE
            ════════════════════════════════════════ */
            let lastPlatKeys = '';

            function rebuildPlatStrip(platAggregates) {
                const inner = document.querySelector('.plat-strip-inner');
                if (!inner) return;
                const newKeys = Object.keys(platAggregates).join(',');
                if (newKeys === lastPlatKeys) return;
                lastPlatKeys = newKeys;
                inner.innerHTML = '';
                Object.keys(platAggregates).forEach(plat => {
                    const chip = document.createElement('span');
                    chip.className = 'plat-chip';
                    chip.innerHTML = '<i class="fa-solid fa-circle"></i> ' + plat;
                    inner.appendChild(chip);
                });
                window.dispatchEvent(new Event('resize'));
            }

            /* ════════════════════════════════════════
               LIVE FETCH — setiap 4 detik
            ════════════════════════════════════════ */
            function doLive() {
                fetch(`{{ route('landing.live') }}?offset=${offset}`)
                    .then(r => r.json())
                    .then(d => {

                        // ── Chart ──
                        if (offset === 0 && chartL) {
                            chartL.updateOptions({ xaxis: { categories: d.lineLabels ?? [] } });
                            chartL.updateSeries([
                                { name: 'OK',          data: d.okData      ?? [] },
                                { name: 'NG',          data: d.ngData      ?? [] },
                                { name: 'Belum Dicek', data: d.unknownData ?? [] }
                            ]);
                            if (typeof d.todayOk !== 'undefined') {
                                document.getElementById('stat-ok').textContent    = d.todayOk;
                                document.getElementById('stat-ng').textContent    = d.todayNg;
                                document.getElementById('stat-unk').textContent   = d.todayUnknown;
                                document.getElementById('stat-total').textContent = d.todayOk + d.todayNg + d.todayUnknown;
                            } else {
                                updateStats(d.okData ?? [], d.ngData ?? [], d.unknownData ?? []);
                            }
                        }

                        // ── Stat cards ──
                        if (d.todayReports !== undefined) {
                            document.getElementById('qs-laporan').textContent     = d.todayReports;
                            document.getElementById('banner-laporan').textContent = d.todayReports;
                        }
                        if (d.armadaLoginCount !== undefined)
                            document.getElementById('qs-armada').textContent = d.armadaLoginCount;
                        if (d.totalDriver !== undefined)
                            document.getElementById('qs-driver').textContent = d.totalDriver;
                        if (d.totalItem !== undefined)
                            document.getElementById('qs-item').textContent = d.totalItem;

                        // ── Feed armada + plat strip ──
                        if (d.platAggregates) {
                            const entries      = Object.entries(d.platAggregates);
                            const shouldScroll = entries.length >= 4;
                            updateFeedContent(entries, shouldScroll);
                            rebuildPlatStrip(d.platAggregates);
                            if (shouldScroll) {
                                toggleEl.disabled = false;
                                if (!scrollEnabled) setScrollEnabled(true);
                            } else {
                                setScrollEnabled(false);
                                toggleEl.disabled = true;
                            }
                        }

                    })
                    .catch(() => {});
            }

            doLive();
            setInterval(doLive, 4000);

        });
    </script>
@endpush
