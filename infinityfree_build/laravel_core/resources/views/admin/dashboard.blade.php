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
        </div>
    </div>

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
        </div>
    </div>

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
        </div>
    </div>

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

</x-admin-layout>
