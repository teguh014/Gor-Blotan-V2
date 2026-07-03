{{--
    Court Schedule Calendar Component
    Usage: <x-court-calendar :courts="$courts" />

    Props:
      - $courts : Collection of Court models (id, name)
--}}
@props(['courts' => collect()])

@php $calId = 'cal-' . uniqid(); @endphp

{{-- FullCalendar v6 CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="cal-wrapper" id="{{ $calId }}-wrapper">

    {{-- ── Gradient Header Card ── --}}
    <div class="cal-header-card">
        <div class="cal-header-bg-glow"></div>
        <div class="cal-header-content">
            {{-- Left: Icon + Title --}}
            <div class="cal-header-left">
                <div class="cal-icon-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="cal-title">Jadwal Lapangan Live</h2>
                    <p class="cal-subtitle">
                        <span class="cal-live-dot"></span>
                        Update real-time · Pilih lapangan untuk filter
                    </p>
                </div>
            </div>

            {{-- Right: Stats Pills --}}
            <div class="cal-header-stats">
                <div class="cal-stat-pill">
                    <span class="cal-stat-dot" style="background:#f59e0b"></span>
                    <span>Pending</span>
                </div>
                <div class="cal-stat-pill">
                    <span class="cal-stat-dot" style="background:#3b82f6"></span>
                    <span>Terbayar</span>
                </div>
                <div class="cal-stat-pill">
                    <span class="cal-stat-dot" style="background:#10b981"></span>
                    <span>Selesai</span>
                </div>
            </div>
        </div>

        {{-- Court Filter Tabs --}}
        <div class="cal-filter-bar">
            <div class="cal-filter-scroll">
                <button class="cal-filter-pill active" onclick="filterCourt_{{ $calId }}('', this)">
                    🏟️ Semua Lapangan
                </button>
                @foreach($courts as $court)
                    <button class="cal-filter-pill" onclick="filterCourt_{{ $calId }}('{{ $court->id }}', this)">
                        🏸 {{ $court->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Calendar Body ── --}}
    <div class="cal-body">
        {{-- Loading overlay --}}
        <div class="cal-loading" id="{{ $calId }}-loading">
            <div class="cal-spinner"></div>
            <span>Memuat jadwal...</span>
        </div>
        <div id="{{ $calId }}" class="cal-fc-container"></div>
    </div>

    {{-- ── Empty State (shown when no events) ── --}}
    <div class="cal-empty" id="{{ $calId }}-empty" style="display:none">
        <div class="cal-empty-icon">🏸</div>
        <p class="cal-empty-title">Belum ada booking</p>
        <p class="cal-empty-sub">Lapangan ini masih tersedia penuh!</p>
    </div>

</div>

{{-- ── Rich Tooltip ── --}}
<div id="{{ $calId }}-tooltip" class="cal-tooltip" style="display:none">
    <div class="cal-tt-header">
        <span class="cal-tt-court" id="{{ $calId }}-tt-court">🏸 Lapangan</span>
        <span class="cal-tt-badge" id="{{ $calId }}-tt-badge">pending</span>
    </div>
    <div class="cal-tt-divider"></div>
    <div class="cal-tt-rows">
        <div class="cal-tt-row">
            <span class="cal-tt-icon">👤</span>
            <div>
                <div class="cal-tt-label">Penyewa</div>
                <div class="cal-tt-val" id="{{ $calId }}-tt-user">—</div>
            </div>
        </div>
        <div class="cal-tt-row">
            <span class="cal-tt-icon">📅</span>
            <div>
                <div class="cal-tt-label">Tanggal</div>
                <div class="cal-tt-val" id="{{ $calId }}-tt-date">—</div>
            </div>
        </div>
        <div class="cal-tt-row">
            <span class="cal-tt-icon">⏰</span>
            <div>
                <div class="cal-tt-label">Waktu</div>
                <div class="cal-tt-val" id="{{ $calId }}-tt-time">—</div>
            </div>
        </div>
    </div>
    <div class="cal-tt-footer">Klik untuk detail</div>
</div>


<style>
/* ═══════════════════════════════════════════════════════════
   WRAPPER
═══════════════════════════════════════════════════════════ */
.cal-wrapper {
    font-family: 'Inter', system-ui, sans-serif;
    border-radius: 24px;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(16,185,129,0.12),
        0 8px 40px rgba(0,0,0,0.10),
        0 2px 8px rgba(0,0,0,0.06);
    background: #ffffff;
}

/* ═══════════════════════════════════════════════════════════
   HEADER CARD
═══════════════════════════════════════════════════════════ */
.cal-header-card {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 100%);
    padding: 28px 28px 0;
    position: relative;
    overflow: hidden;
}

/* Decorative glow blob */
.cal-header-bg-glow {
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: radial-gradient(circle, rgba(52,211,153,0.25) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.cal-header-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    position: relative;
    z-index: 1;
    margin-bottom: 24px;
}

.cal-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.cal-icon-badge {
    width: 52px; height: 52px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cal-icon-badge svg {
    width: 26px; height: 26px;
    stroke: #ffffff;
}

.cal-title {
    font-size: 1.2rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}

.cal-subtitle {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.65);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.cal-live-dot {
    display: inline-block;
    width: 7px; height: 7px;
    background: #4ade80;
    border-radius: 50%;
    animation: livePulse 1.8s ease-in-out infinite;
}
@keyframes livePulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}

/* Stats pills (legend) */
.cal-header-stats {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.cal-stat-pill {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 20px;
    padding: 5px 12px;
    font-size: 0.72rem;
    font-weight: 600;
    color: rgba(255,255,255,0.9);
    white-space: nowrap;
}
.cal-stat-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════════════════════
   FILTER BAR (tab pills)
═══════════════════════════════════════════════════════════ */
.cal-filter-bar {
    position: relative;
    z-index: 1;
    margin: 0 -28px;
    padding: 0 28px;
    border-top: 1px solid rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.12);
}
.cal-filter-scroll {
    display: flex;
    gap: 4px;
    padding: 10px 0;
    overflow-x: auto;
    scrollbar-width: none;
}
.cal-filter-scroll::-webkit-scrollbar { display: none; }

.cal-filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    border: 1.5px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.7);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
    font-family: inherit;
}
.cal-filter-pill:hover {
    background: rgba(255,255,255,0.15);
    color: #ffffff;
    border-color: rgba(255,255,255,0.4);
}
.cal-filter-pill.active {
    background: #ffffff;
    color: #065f46;
    border-color: #ffffff;
    box-shadow: 0 2px 12px rgba(0,0,0,0.2);
}

/* ═══════════════════════════════════════════════════════════
   CALENDAR BODY
═══════════════════════════════════════════════════════════ */
.cal-body {
    padding: 24px 28px 28px;
    position: relative;
    min-height: 200px;
}

/* Loading Spinner */
.cal-loading {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(4px);
    z-index: 10;
    border-radius: 0 0 24px 24px;
    font-size: 0.8rem;
    color: #6b7280;
    font-weight: 500;
}
.cal-loading.hidden { display: none !important; }
.cal-spinner {
    width: 32px; height: 32px;
    border: 3px solid #d1fae5;
    border-top-color: #10b981;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Empty State */
.cal-empty {
    text-align: center;
    padding: 48px 20px;
}
.cal-empty-icon { font-size: 3rem; margin-bottom: 12px; }
.cal-empty-title { font-size: 1rem; font-weight: 700; color: #374151; margin: 0 0 4px; }
.cal-empty-sub { font-size: 0.8rem; color: #9ca3af; margin: 0; }

/* ─── FullCalendar Overrides ─────────────────────────────── */
.cal-fc-container { border-radius: 16px; overflow: hidden; }

/* Toolbar */
.cal-fc-container .fc-toolbar {
    padding: 0 0 16px !important;
    gap: 8px;
    flex-wrap: wrap;
}
.cal-fc-container .fc-toolbar-title {
    font-size: 1rem !important;
    font-weight: 800 !important;
    color: #111827 !important;
    letter-spacing: -0.01em !important;
}

/* Navigation Buttons */
.cal-fc-container .fc-button-group {
    gap: 6px !important;
}
.cal-fc-container .fc-button-group > .fc-button {
    margin: 0 !important;
}

.cal-fc-container .fc-button-primary {
    background: #065f46 !important;
    border-color: #065f46 !important;
    color: #fff !important;
    font-weight: 600 !important;
    border-radius: 10px !important;
    padding: 6px 14px !important;
    font-size: 0.78rem !important;
    transition: all 0.2s !important;
    box-shadow: 0 1px 4px rgba(6,95,70,0.3) !important;
    font-family: inherit !important;
    text-transform: capitalize !important;
}
.cal-fc-container .fc-button-primary:hover {
    background: #047857 !important;
    border-color: #047857 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(6,95,70,0.3) !important;
}
.cal-fc-container .fc-button-primary:active,
.cal-fc-container .fc-button-primary:focus,
.cal-fc-container .fc-button-active {
    background: #064e3b !important;
    border-color: #064e3b !important;
    box-shadow: none !important;
    transform: none !important;
}

/* Today Button Disabled State */
.cal-fc-container .fc-button-primary:disabled {
    background: #e5e7eb !important;
    border-color: #e5e7eb !important;
    color: #9ca3af !important;
    box-shadow: none !important;
    cursor: not-allowed !important;
}

/* Day Header */
.cal-fc-container .fc-col-header-cell {
    background: #f9fafb !important;
    border-color: #f3f4f6 !important;
}
.cal-fc-container .fc-col-header-cell-cushion {
    font-size: 0.75rem !important;
    font-weight: 700 !important;
    color: #6b7280 !important;
    text-decoration: none !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 8px 4px !important;
}

/* Day cells */
.cal-fc-container .fc-daygrid-day-number {
    font-size: 0.78rem !important;
    font-weight: 600 !important;
    color: #9ca3af !important;
    text-decoration: none !important;
    padding: 6px 8px !important;
}
.cal-fc-container .fc-daygrid-day:hover {
    background: #f9fafb !important;
}

/* Today */
.cal-fc-container .fc-daygrid-day.fc-day-today {
    background: linear-gradient(135deg, #ecfdf5, #f0fdf4) !important;
}
.cal-fc-container .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
    color: #ffffff !important;
    font-weight: 800 !important;
    background: #10b981;
    border-radius: 8px;
    padding: 3px 7px !important;
    margin: 4px;
}

/* Events */
.cal-fc-container .fc-event {
    border: none !important;
    border-radius: 8px !important;
    padding: 2px 7px !important;
    font-size: 0.7rem !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: transform 0.15s, box-shadow 0.15s !important;
    box-shadow: 0 1px 4px rgba(0,0,0,0.15) !important;
}
.cal-fc-container .fc-event:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
    filter: brightness(1.08) !important;
}
.cal-fc-container .fc-daygrid-event {
    margin: 1px 4px 2px !important;
}

/* Time Grid */
.cal-fc-container .fc-timegrid-slot {
    height: 40px !important;
}
.cal-fc-container .fc-timegrid-slot-label-cushion {
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    color: #9ca3af !important;
}

/* List View */
.cal-fc-container .fc-list-event:hover td { background: #f9fafb !important; }
.cal-fc-container .fc-list-day-cushion {
    background: linear-gradient(90deg, #ecfdf5, #f0fdf4) !important;
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    color: #065f46 !important;
}
.cal-fc-container .fc-list-event-title a {
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    color: #1f2937 !important;
    text-decoration: none !important;
}

/* Grid borders */
.cal-fc-container .fc-scrollgrid,
.cal-fc-container .fc-scrollgrid td,
.cal-fc-container .fc-scrollgrid th {
    border-color: #f3f4f6 !important;
}

/* ═══════════════════════════════════════════════════════════
   RICH TOOLTIP
═══════════════════════════════════════════════════════════ */
.cal-tooltip {
    position: fixed;
    z-index: 9999;
    background: #0f172a;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 0;
    min-width: 220px;
    max-width: 260px;
    box-shadow:
        0 20px 60px rgba(0,0,0,0.5),
        0 4px 16px rgba(0,0,0,0.3),
        inset 0 1px 0 rgba(255,255,255,0.06);
    pointer-events: none;
    overflow: hidden;
    animation: tooltipIn 0.15s ease;
}
@keyframes tooltipIn {
    from { opacity: 0; transform: translateY(4px) scale(0.97); }
    to   { opacity: 1; transform: none; }
}
.cal-tt-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 12px 14px 10px;
    background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(5,150,105,0.08));
}
.cal-tt-court {
    font-size: 0.83rem;
    font-weight: 800;
    color: #ecfdf5;
    line-height: 1.2;
}
.cal-tt-badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
    background: rgba(245,158,11,0.2);
    color: #fbbf24;
    border: 1px solid rgba(245,158,11,0.3);
}
.cal-tt-badge.paid   { background: rgba(59,130,246,0.2); color: #60a5fa; border-color: rgba(59,130,246,0.3); }
.cal-tt-badge.completed { background: rgba(16,185,129,0.2); color: #34d399; border-color: rgba(16,185,129,0.3); }
.cal-tt-badge.cancelled { background: rgba(107,114,128,0.2); color: #9ca3af; border-color: rgba(107,114,128,0.3); }

.cal-tt-divider {
    height: 1px;
    background: rgba(255,255,255,0.06);
}
.cal-tt-rows {
    padding: 10px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.cal-tt-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.cal-tt-icon {
    font-size: 0.85rem;
    margin-top: 1px;
    flex-shrink: 0;
}
.cal-tt-label {
    font-size: 0.67rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 1px;
}
.cal-tt-val {
    font-size: 0.8rem;
    color: #e5e7eb;
    font-weight: 600;
    line-height: 1.3;
}
.cal-tt-footer {
    background: rgba(255,255,255,0.04);
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 7px 14px;
    font-size: 0.67rem;
    color: #4b5563;
    font-weight: 500;
    text-align: center;
    letter-spacing: 0.03em;
}

/* ═══════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════ */
@media (max-width: 640px) {
    .cal-header-card { padding: 20px 16px 0; }
    .cal-header-bg-glow { display: none; }
    .cal-header-content { gap: 12px; }
    .cal-header-stats { display: none; }
    .cal-filter-bar { margin: 0 -16px; padding: 0 16px; }
    .cal-body { padding: 16px; }
    .cal-fc-container .fc-toolbar { flex-direction: column; align-items: flex-start !important; }
    .cal-fc-container .fc-toolbar-chunk { width: 100%; }
}
</style>


<script>
(function () {
    const CAL_ID      = '{{ $calId }}';
    const calendarEl  = document.getElementById(CAL_ID);
    const tooltip     = document.getElementById(CAL_ID + '-tooltip');
    const loadingEl   = document.getElementById(CAL_ID + '-loading');
    let currentCourtId = '';
    let calendarInstance;
    let hideTimer;

    /* ── Status badge colour map ── */
    const STATUS_MAP = {
        pending:   { label: 'Menunggu',    cls: 'pending'   },
        paid:      { label: 'Terkonfirmasi', cls: 'paid'    },
        completed: { label: 'Selesai',      cls: 'completed' },
        cancelled: { label: 'Dibatalkan',   cls: 'cancelled' },
    };

    function buildUrl(info) {
        let url = '{{ route("schedule.events") }}?start=' + (info?.startStr ?? '') + '&end=' + (info?.endStr ?? '');
        if (currentCourtId) url += '&court_id=' + currentCourtId;
        return url;
    }

    function showTooltip(e, event) {
        clearTimeout(hideTimer);
        const p    = event.extendedProps;
        const stat = STATUS_MAP[p.status] || { label: p.status, cls: 'pending' };

        document.getElementById(CAL_ID + '-tt-court').textContent  = '🏸 ' + (p.court ?? event.title);
        document.getElementById(CAL_ID + '-tt-user').textContent   = p.user ?? 'Tamu';
        document.getElementById(CAL_ID + '-tt-date').textContent   = p.date  ?? '—';
        document.getElementById(CAL_ID + '-tt-time').textContent   = (p.start ?? '?') + ' – ' + (p.end ?? '?');

        const badge = document.getElementById(CAL_ID + '-tt-badge');
        badge.textContent  = stat.label;
        badge.className    = 'cal-tt-badge ' + stat.cls;

        positionTooltip(e);
        tooltip.style.display = 'block';
    }

    function positionTooltip(e) {
        const gap = 14;
        let x = e.clientX + gap;
        let y = e.clientY + gap;
        if (x + 270 > window.innerWidth)  x = e.clientX - 270 - gap;
        if (y + 180 > window.innerHeight) y = e.clientY - 180 - gap;
        tooltip.style.left = x + 'px';
        tooltip.style.top  = y + 'px';
    }

    function hideTooltip() {
        hideTimer = setTimeout(() => { tooltip.style.display = 'none'; }, 80);
    }

    function initCalendar() {
        if (!calendarEl) return;

        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 640 ? 'listWeek' : 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,timeGridWeek,listWeek',
            },
            buttonText: {
                today: 'Hari ini',
                month: 'Bulan',
                week:  'Minggu',
                list:  'Daftar',
            },
            navLinks: true,
            navLinkDayClick: 'listDay',
            dateClick: function (info) {
                calendarInstance.changeView('listDay', info.dateStr);
            },
            eventDisplay: 'block', // Force all events to render as solid blocks
            height: 'auto',
            loading: function (isLoading) {
                if (loadingEl) {
                    loadingEl.classList.toggle('hidden', !isLoading);
                }
            },
            events: function (info, successCallback, failureCallback) {
                fetch(buildUrl(info))
                    .then(r => r.json())
                    .then(data => successCallback(data))
                    .catch(failureCallback);
            },
            eventDidMount: function (info) {
                const el = info.el;
                el.addEventListener('mouseenter', e => showTooltip(e, info.event));
                el.addEventListener('mousemove',  e => positionTooltip(e));
                el.addEventListener('mouseleave', hideTooltip);
            },
            eventClick: function () {
                tooltip.style.display = 'none';
            },
            /* Style events text properly based on view */
            eventContent: function (arg) {
                if (arg.view.type.includes('list')) {
                    return {
                        html: `<div style="display:flex; flex-direction:column; gap:2px; padding: 2px 0;">
                            <div style="font-weight:700; color:#1f2937; font-size:0.85rem;">${arg.event.title}</div>
                            <div style="font-size:0.75rem; color:#6b7280; font-weight:500;">
                                <span style="margin-right:8px;">👤 ${arg.event.extendedProps.user}</span>
                                <span>🏷️ ${arg.event.extendedProps.status}</span>
                            </div>
                        </div>`
                    };
                }
                return {
                    html: `<div style="display:flex;align-items:center;padding:1px 3px;overflow:hidden;color:#ffffff;">
                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${arg.event.title}</span>
                    </div>`
                };
            },
        });
        calendarInstance.render();
    }

    /* ── Court Filter (pill tabs) ── */
    window['filterCourt_' + CAL_ID] = function (courtId, btn) {
        currentCourtId = courtId;

        // Update active pill
        const wrapper = document.getElementById(CAL_ID + '-wrapper');
        wrapper.querySelectorAll('.cal-filter-pill').forEach(p => p.classList.remove('active'));
        if (btn) btn.classList.add('active');

        if (calendarInstance) calendarInstance.refetchEvents();
    };

    /* Boot */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCalendar);
    } else {
        initCalendar();
    }
})();
</script>
