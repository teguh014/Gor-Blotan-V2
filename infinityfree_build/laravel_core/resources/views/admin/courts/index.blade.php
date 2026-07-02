<x-admin-layout title="Kelola Lapangan">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Daftar Lapangan</h2>
            <p class="text-sm text-gray-500">Kelola semua lapangan badminton</p>
        </div>
        <a href="{{ route('admin.courts.create') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lapangan
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 font-semibold uppercase tracking-wide">
                    <th class="px-6 py-3 text-left">Lapangan</th>
                    <th class="px-6 py-3 text-left">Harga/Jam</th>
                    <th class="px-6 py-3 text-left">Fasilitas</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($courts as $court)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                <img src="{{ $court->photo_url }}" alt="{{ $court->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $court->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $court->description }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @foreach(($court->facilities ?? []) as $facility)
                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">{{ $facility }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($court->is_active)
                            <span class="inline-flex items-center gap-1 text-xs bg-green-50 text-green-700 font-semibold px-2 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-500 font-semibold px-2 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.courts.edit', $court) }}"
                               class="text-xs text-blue-600 font-medium hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.courts.destroy', $court) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 font-medium hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada lapangan. Tambah sekarang!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $courts->links() }}
        </div>
    </div>

</x-admin-layout>
