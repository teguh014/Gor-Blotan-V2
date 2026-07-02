<x-customer-layout title="Detail Booking">

    <div class="mb-6">
        <a href="{{ route('customer.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">

            <div class="flex items-start justify-between mb-6 gap-4 flex-wrap">
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                    <p class="text-sm text-gray-400">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    @include('components.status-badge', ['status' => $booking->status])
                    @if($booking->status !== 'cancelled')
                    <a href="{{ route('customer.bookings.receipt', $booking) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700
                              bg-emerald-50 hover:bg-emerald-100 border border-emerald-200
                              px-3 py-1.5 rounded-lg transition-all duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Nota
                    </a>
                    @endif
                </div>
            </div>

            {{-- Court Info --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Lapangan</p>
                <p class="font-bold text-gray-900 text-base">{{ $booking->court->name }}</p>
                @if($booking->court->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $booking->court->description }}</p>
                @endif
                @if($booking->court->facilities)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($booking->court->facilities as $facility)
                            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ $facility }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Booking Details --}}
            <dl class="space-y-3 text-sm mb-6">
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Tanggal</dt>
                    <dd class="font-medium text-gray-800">{{ $booking->start_time->format('l, d F Y') }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Jam</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}
                        <span class="text-gray-400 text-xs ml-1">({{ $booking->start_time->diffInMinutes($booking->end_time) / 60 }} jam)</span>
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Harga/Jam</dt>
                    <dd class="text-gray-800">Rp {{ number_format($booking->court->price_per_hour, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="font-semibold text-gray-700">Total Pembayaran</dt>
                    <dd class="font-bold text-xl text-green-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                </div>
            </dl>

            {{-- Actions --}}
            @if($booking->status === 'pending')
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    @if($booking->payment_url)
                        <div class="flex-1 flex gap-2">
                            <a href="{{ $booking->payment_url }}" target="_blank"
                               class="flex-[2] text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition text-sm flex items-center justify-center">
                                💳 Bayar Sekarang via Xendit
                            </a>
                            @if(app()->environment('local'))
                            <form method="POST" action="{{ route('customer.bookings.check-status', $booking) }}" class="flex-1 flex">
                                @csrf
                                <button type="submit" class="w-full bg-blue-100 text-blue-700 hover:bg-blue-200 font-bold py-3 rounded-xl transition text-sm">
                                    Cek Status (Lokal)
                                </button>
                            </form>
                            @endif
                        </div>
                    @else
                        <div class="flex-1 bg-gray-100 text-gray-500 font-bold py-3 rounded-xl text-center text-sm">
                            Link Pembayaran Tidak Tersedia
                        </div>
                    @endif
                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}"
                          onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-3 px-5 rounded-xl transition text-sm">
                            Batalkan
                        </button>
                    </form>
                </div>
            @elseif($booking->status === 'paid')
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                    ✅ Pembayaran diterima. Menunggu konfirmasi dari admin.
                </div>
            @elseif($booking->status === 'completed')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-800">
                    🏸 Booking selesai. Terima kasih sudah menggunakan layanan kami!
                </div>
            @elseif($booking->status === 'cancelled')
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-500">
                    ✕ Booking ini telah dibatalkan.
                </div>
            @endif
        </div>
    </div>

</x-customer-layout>
