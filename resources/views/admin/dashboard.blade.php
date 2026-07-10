<x-admin-layout title="Dashboard Admin">

{{-- ── Header ── --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 md:mb-8">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Selamat datang kembali! 👋</h2>
        <p class="text-gray-400 mt-1 text-sm">Berikut ringkasan aktivitas hari ini</p>
    </div>
    <a href="{{ route('admin.courts.create') }}" class="btn-primary self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Lapangan
    </a>
</div>

{{-- ── Primary Stats ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Total Booking --}}
    @php
        $t = $trends['total_bookings'];
        $d = $t['delta'];
        $isUp   = $d > 0;
        $isDown = $d < 0;
    @endphp
    <div class="stat-card card-hover">
        <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Total</span>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total_bookings'] }}</p>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Total Booking</p>
            <p class="text-xs font-semibold mt-2 {{ $isUp ? 'text-emerald-500' : ($isDown ? 'text-red-400' : 'text-gray-400') }}">
                {{ $isUp ? '↑ +' . $d : ($isDown ? '↓ ' . $d : '→ 0') }}
                <span class="text-gray-400 font-normal"> {{ $t['label'] }}</span>
            </p>
        </div>
    </div>

    {{-- Pending --}}
    @php
        $t = $trends['pending'];
        $d = $t['delta'];
        $isUp   = $d > 0;
        $isDown = $d < 0;
    @endphp
    <div class="stat-card card-hover">
        <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Pending</span>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Menunggu Bayar</p>
            <p class="text-xs font-semibold mt-2 {{ $isUp ? 'text-amber-500' : ($isDown ? 'text-emerald-500' : 'text-gray-400') }}">
                {{ $isUp ? '↑ +' . $d : ($isDown ? '↓ ' . $d : '→ 0') }}
                <span class="text-gray-400 font-normal"> {{ $t['label'] }}</span>
            </p>
        </div>
    </div>

    {{-- Completed --}}
    @php
        $t = $trends['completed'];
        $d = $t['delta'];
        $isUp   = $d > 0;
        $isDown = $d < 0;
    @endphp
    <div class="stat-card card-hover">
        <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Selesai</span>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $stats['completed'] }}</p>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Berhasil Diselesaikan</p>
            <p class="text-xs font-semibold mt-2 {{ $isUp ? 'text-emerald-500' : ($isDown ? 'text-red-400' : 'text-gray-400') }}">
                {{ $isUp ? '↑ +' . $d : ($isDown ? '↓ ' . $d : '→ 0') }}
                <span class="text-gray-400 font-normal"> {{ $t['label'] }}</span>
            </p>
        </div>
    </div>

    {{-- Revenue --}}
    @php
        $t   = $trends['total_revenue'];
        $pct = $t['pct'];
        $isUp   = $pct > 0;
        $isDown = $pct < 0;
    @endphp
    <div class="stat-card card-hover border-l-4 border-emerald-500">
        <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-emerald-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 font-medium mt-0.5">Total Pendapatan</p>
            <p class="text-xs font-semibold mt-2 {{ $isUp ? 'text-emerald-500' : ($isDown ? 'text-red-400' : 'text-gray-400') }}">
                {{ $isUp ? '↑ +' . $pct . '%' : ($isDown ? '↓ ' . $pct . '%' : '→ 0%') }}
                <span class="text-gray-400 font-normal"> {{ $t['label'] }}</span>
            </p>
        </div>
    </div>

</div>


{{-- ── Secondary Stats ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    @foreach([
        ['label'=>'Total Lapangan','value'=>$stats['total_courts'],'color'=>'purple','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
        ['label'=>'Total Customer','value'=>$stats['total_customers'],'color'=>'blue','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['label'=>'Dibatalkan','value'=>$stats['cancelled'],'color'=>'red','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ] as $s)
    <div class="card p-5 flex items-center gap-4 card-hover">
        <div class="w-12 h-12 rounded-2xl bg-{{ $s['color'] }}-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-{{ $s['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-gray-900">{{ $s['value'] }}</p>
            <p class="text-xs text-gray-400 font-medium">{{ $s['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Charts ── --}}

{{-- Filter Bar --}}
<div class="card p-4 mb-4">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-end gap-3">
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-500">Tahun</label>
            <select name="year" id="filter-year"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 pr-8"
                    onchange="this.form.month.value=''; syncMonth(this.value)">
                <option value="">-- Semua --</option>
                @foreach($availableYears as $yr)
                    <option value="{{ $yr }}" {{ $filterYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-500">Bulan</label>
            <select name="month" id="filter-month"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 pr-8 disabled:opacity-40 disabled:cursor-not-allowed"
                    {{ !$filterYear ? 'disabled' : '' }}>
                <option value="">-- Semua Bulan --</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $filterMonth == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Terapkan
        </button>

        @if($filterYear || $filterMonth)
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-600 font-medium transition-colors py-2 px-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Reset
        </a>
        @endif

        <span class="ml-auto text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-2 rounded-xl">
            📅 {{ $chartPeriodLabel }}
        </span>
    </form>
</div>

{{-- Chart Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

    {{-- Pendapatan --}}
    <div class="card p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-900 text-sm">Pendapatan</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $chartPeriodLabel }} – paid & completed</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
        <div class="relative" style="height:200px">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Booking per Lapangan --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-900 text-sm">Booking per Lapangan</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $chartPeriodLabel }}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
            </div>
        </div>
        <div class="relative flex items-center justify-center" style="height:200px">
            @if(count($chartCourtLabels) > 0)
                <canvas id="courtChart"></canvas>
            @else
                <p class="text-xs text-gray-300">Belum ada data pada periode ini</p>
            @endif
        </div>
    </div>

</div>

{{-- Booking per Periode (full width) --}}
<div class="card p-5 mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-bold text-gray-900 text-sm">Jumlah Booking</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $chartPeriodLabel }} – semua status</p>
        </div>
        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
    </div>
    <div class="relative" style="height:180px">
        <canvas id="bookingsChart"></canvas>
    </div>
</div>


{{-- ── Recent Bookings Table ── --}}
<div class="card overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-900">Booking Terbaru</h3>
            <p class="text-xs text-gray-400 mt-0.5">10 transaksi terakhir</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}"
           class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
            Lihat Semua
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Lapangan</th>
                    <th>Jadwal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr>
                    <td class="text-gray-400 font-mono text-xs">#{{ $booking->id }}</td>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800 text-sm">{{ $booking->user->name }}</span>
                        </div>
                    </td>
                    <td class="text-gray-600 text-sm font-medium">{{ $booking->court->name }}</td>
                    <td class="text-xs text-gray-500">
                        <p class="font-medium text-gray-700">{{ $booking->start_time->format('d M Y') }}</p>
                        <p>{{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}</p>
                    </td>
                    <td class="font-bold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>@include('components.status-badge', ['status' => $booking->status])</td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                           class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition-colors">
                            Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm">Belum ada booking</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Chart.js ── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ─── Shared data dari PHP ───────────────────────────────
    const labels   = @json($chartLabels);
    const revenue  = @json($chartRevenue);
    const bookings = @json($chartBookings);
    const courtLabels = @json($chartCourtLabels);
    const courtData   = @json($chartCourtData);

    const palette = ['#10b981','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#f97316'];

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#9ca3af';

    // ─── 1. Pendapatan Bar Chart ────────────────────────────
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenue,
                backgroundColor: 'rgba(16,185,129,0.2)',
                borderColor: '#10b981',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { maxRotation: 0 } },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: v => 'Rp ' + (v >= 1000000
                            ? (v / 1000000).toFixed(1) + 'jt'
                            : v >= 1000 ? (v / 1000).toFixed(0) + 'rb' : v)
                    }
                }
            }
        }
    });

    // ─── 2. Booking per Hari Line Chart ────────────────────
    new Chart(document.getElementById('bookingsChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Jumlah Booking',
                data: bookings,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { maxRotation: 0 } },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 1, precision: 0 },
                    beginAtZero: true,
                }
            }
        }
    });

    // ─── 3. Booking per Lapangan Doughnut ──────────────────
    const courtCanvas = document.getElementById('courtChart');
    if (courtCanvas && courtData.length > 0) {
        new Chart(courtCanvas, {
            type: 'doughnut',
            data: {
                labels: courtLabels,
                datasets: [{
                    data: courtData,
                    backgroundColor: palette.slice(0, courtData.length),
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 12, boxWidth: 10, boxHeight: 10, borderRadius: 3 }
                    }
                }
            }
        });
    }
})();

// ─── Filter UX helpers ──────────────────────────────────
function syncMonth(yearVal) {
    const monthSel = document.getElementById('filter-month');
    if (yearVal) {
        monthSel.disabled = false;
        monthSel.classList.remove('opacity-40', 'cursor-not-allowed');
    } else {
        monthSel.disabled = true;
        monthSel.value    = '';
        monthSel.classList.add('opacity-40', 'cursor-not-allowed');
    }
}
</script>

</x-admin-layout>
