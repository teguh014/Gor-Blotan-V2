<x-customer-layout title="Dashboard Saya">

{{-- ── Hero Banner ── --}}
<div class="bg-hero rounded-2xl md:rounded-3xl p-5 md:p-8 mb-6 md:mb-8 relative overflow-hidden">
    {{-- Decorative circles --}}
    <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 -translate-y-32 translate-x-32"></div>
    <div class="absolute bottom-0 right-20 w-40 h-40 rounded-full bg-emerald-400/10 translate-y-20"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs md:text-sm font-semibold mb-1">🏸 {{ now()->format('l, d F Y') }}</p>
            <h1 class="text-xl md:text-2xl font-extrabold text-white mb-1 md:mb-2">Selamat datang, {{ auth()->user()->name }}!</h1>
            <p class="text-green-200 text-xs md:text-sm max-w-sm">Kelola semua booking lapangan badminton Anda dengan mudah.</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('customer.bookings.create') }}"
               class="inline-flex items-center gap-2 bg-white text-emerald-700 font-bold px-4 md:px-5 py-2.5 md:py-3 rounded-xl hover:bg-emerald-50 transition-all duration-200 shadow-lg shadow-black/20 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Booking Sekarang
            </a>
        </div>
    </div>
</div>

{{-- ── Schedule Calendar ── --}}
<div class="mb-8">
    <x-court-calendar :courts="$courts" />
</div>

{{-- ── Booking List ── --}}
<div class="flex items-center justify-between mb-5">
    <h2 class="text-lg font-bold text-gray-900">Riwayat Booking</h2>
    <span class="text-xs font-medium text-gray-400">{{ $bookings->total() }} booking</span>
</div>

@if($bookings->isEmpty())
    <div class="card py-20 text-center card-hover">
        <div class="w-20 h-20 rounded-3xl bg-emerald-50 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-800 mb-1">Belum ada booking</h3>
        <p class="text-sm text-gray-400 mb-6">Yuk pesan lapangan badminton favorit Anda!</p>
        <a href="{{ route('customer.bookings.create') }}" class="btn-primary mx-auto">
            🏸 Booking Lapangan Sekarang
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($bookings as $booking)
        <div class="card p-4 md:p-6 card-hover">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                {{-- Court + Status --}}
                <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-200">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <h3 class="font-bold text-gray-900 text-sm md:text-base">{{ $booking->court->name }}</h3>
                            @include('components.status-badge', ['status' => $booking->status])
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $booking->start_time->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Price --}}
                <div class="sm:text-right flex-shrink-0">
                    <p class="text-lg md:text-xl font-extrabold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 font-mono mt-0.5">#{{ $booking->id }}</p>
                </div>
            </div>

            {{-- Actions --}}
            @if($booking->status === 'pending')
            <div class="mt-5 pt-4 border-t border-gray-50 flex items-center gap-3"
                 x-data="{
                     expireAt: new Date('{{ $booking->created_at->addMinutes(30)->toIso8601String() }}').getTime(),
                     expired: false,
                     timeDisplay: '...',
                     init() {
                         this.update();
                         setInterval(() => this.update(), 1000);
                     },
                     update() {
                         let diff = this.expireAt - new Date().getTime();
                         if (diff <= 0) {
                             this.expired = true;
                             this.timeDisplay = '00:00';
                         } else {
                             let m = Math.floor(diff / 60000);
                             let s = Math.floor((diff % 60000) / 1000);
                             this.timeDisplay = m.toString().padStart(2, '0') + ':' + s.toString().padStart(2, '0');
                         }
                     }
                 }">
                
                {{-- Block Before Expired --}}
                <div x-show="!expired" class="flex items-center gap-3 w-full">
                    @if($booking->payment_url)
                    <a href="{{ $booking->payment_url }}" target="_blank" class="btn-primary text-xs py-2 shadow-md shadow-emerald-200 inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Bayar (<span x-text="timeDisplay"></span>)
                    </a>
                    
                    @if(app()->environment('local'))
                    <form method="POST" action="{{ route('customer.bookings.check-status', $booking) }}">
                        @csrf
                        <button type="submit" class="bg-blue-100 text-blue-700 hover:bg-blue-200 font-bold px-3 py-2 rounded-xl transition text-xs shadow-sm">
                            Cek Status (Lokal)
                        </button>
                    </form>
                    @endif
                    @endif
                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}"
                          onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-danger text-xs py-2">Batalkan</button>
                    </form>
                    <a href="{{ route('customer.bookings.show', $booking) }}"
                       class="ml-auto text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition-colors">
                        Lihat Detail →
                    </a>
                </div>

                {{-- Block After Expired --}}
                <div x-show="expired" class="flex items-center gap-3 w-full" style="display: none;">
                    <span class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Waktu Habis
                    </span>
                    <a href="{{ route('customer.bookings.show', $booking) }}"
                       class="ml-auto text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition-colors">
                        Lihat Detail →
                    </a>
                </div>
            </div>
            @elseif($booking->status === 'paid')
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-blue-600 font-medium">
                    <div class="w-4 h-4 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Menunggu konfirmasi admin
                </div>
                <a href="{{ route('customer.bookings.show', $booking) }}"
                   class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Lihat Detail →</a>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-end">
                <a href="{{ route('customer.bookings.show', $booking) }}"
                   class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Lihat Detail →</a>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $bookings->links() }}</div>
@endif

</x-customer-layout>
