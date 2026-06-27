<x-admin-layout title="Semua Booking">

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.bookings.index') }}"
          class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Semua Status</option>
                @foreach(['pending','paid','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Lapangan</label>
            <select name="court_id"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Semua Lapangan</option>
                @foreach($courts as $court)
                    <option value="{{ $court->id }}" {{ request('court_id') == $court->id ? 'selected' : '' }}>
                        {{ $court->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-1.5 rounded-lg transition">
            Filter
        </button>
        @if(request()->hasAny(['status','court_id']))
            <a href="{{ route('admin.bookings.index') }}"
               class="text-sm text-gray-400 hover:text-gray-600 py-1.5">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-900">Daftar Booking</h2>
            <span class="text-xs text-gray-400">{{ $bookings->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs text-gray-500 font-semibold uppercase tracking-wide">
                        <th class="px-6 py-3 text-left">#ID</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Lapangan</th>
                        <th class="px-6 py-3 text-left">Jadwal</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-gray-400 font-mono text-xs">#{{ $booking->id }}</td>
                        <td class="px-6 py-3">
                            <p class="font-medium text-gray-800">{{ $booking->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $booking->court->name }}</td>
                        <td class="px-6 py-3 text-xs text-gray-600">
                            {{ $booking->start_time->format('d M Y') }}<br>
                            <span class="text-gray-400">{{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-3 font-semibold text-gray-800">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-3">
                            @include('components.status-badge', ['status' => $booking->status])
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                   class="text-xs text-blue-600 font-medium hover:underline">Detail</a>

                                @if($booking->status === 'paid')
                                    <form method="POST" action="{{ route('admin.bookings.complete', $booking) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-xs text-green-600 font-medium hover:underline">Selesaikan</button>
                                    </form>
                                @endif

                                @if(in_array($booking->status, ['pending','paid']))
                                    <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}"
                                          onsubmit="return confirm('Batalkan booking ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-xs text-orange-500 font-medium hover:underline">Batalkan</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini secara PERMANEN? Data yang dihapus tidak dapat dikembalikan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-600 font-medium hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">Tidak ada booking ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
    </div>

</x-admin-layout>
