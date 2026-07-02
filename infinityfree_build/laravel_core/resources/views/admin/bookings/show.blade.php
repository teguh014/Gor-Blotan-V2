<x-admin-layout title="Detail Booking">

    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Booking
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900">Booking #{{ $booking->id }}</h2>
                    @include('components.status-badge', ['status' => $booking->status])
                </div>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <dt class="text-gray-500">Lapangan</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->court->name }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <dt class="text-gray-500">Tanggal</dt>
                        <dd class="font-medium text-gray-800">{{ $booking->start_time->format('l, d F Y') }}</dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <dt class="text-gray-500">Jam Main</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}
                            <span class="text-gray-400 text-xs ml-1">
                                ({{ $booking->start_time->diffInMinutes($booking->end_time) / 60 }} jam)
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <dt class="text-gray-500">Harga/Jam</dt>
                        <dd class="text-gray-800">Rp {{ number_format($booking->court->price_per_hour, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between py-2">
                        <dt class="text-gray-500 font-semibold">Total Bayar</dt>
                        <dd class="font-bold text-lg text-green-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Actions Sidebar --}}
        <div class="space-y-4">
            {{-- Customer Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Customer</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm">
                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Admin Actions --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Aksi Admin</h3>
                <div class="space-y-2">
                    @if($booking->status === 'paid')
                        <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                                ✓ Tandai Selesai
                            </button>
                        </form>
                    @endif

                    @if(in_array($booking->status, ['pending','paid']))
                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}"
                              onsubmit="return confirm('Batalkan booking ini?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-4 py-2 rounded-lg transition">
                                ✕ Batalkan Booking
                            </button>
                        </form>
                    @endif

                    @if(in_array($booking->status, ['completed','cancelled']))
                        <p class="text-xs text-gray-400 text-center py-2">Tidak ada status baru yang tersedia.</p>
                    @endif

                    <hr class="my-4 border-gray-100">

                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini secara PERMANEN? Data yang dihapus tidak dapat dikembalikan.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                            Hapus Transaksi (Permanen)
                        </button>
                    </form>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-xs text-gray-400 space-y-1">
                <p>Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</p>
                <p>Diperbarui: {{ $booking->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

</x-admin-layout>
