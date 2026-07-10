<x-admin-layout title="Pengaturan Gedung">

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 md:mb-8">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Pengaturan Gedung</h2>
        <p class="text-gray-400 mt-1 text-sm">Kelola nama dan tagline yang tampil di semua halaman</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-2xl space-y-6">
    @csrf
    @method('PUT')

    {{-- ── Nama Gedung ── --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 text-xs font-bold">1</span>
            Identitas Gedung
        </h3>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="venue_name">
                    Nama Gedung <span class="text-red-400">*</span>
                </label>
                <input type="text" id="venue_name" name="venue_name"
                       value="{{ old('venue_name', $venueName) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all"
                       placeholder="Nama GOR / Gedung Olahraga" required>
                @error('venue_name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="venue_tagline">
                    Tagline
                    <span class="text-xs font-normal text-gray-400">(opsional — muncul di sidebar admin)</span>
                </label>
                <input type="text" id="venue_tagline" name="venue_tagline"
                       value="{{ old('venue_tagline', $venueTagline) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800
                              focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all"
                       placeholder="Booking Lapangan Online">
            </div>
        </div>
    </div>



    {{-- ── Submit ── --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold
                       px-6 py-2.5 rounded-xl transition-colors text-sm shadow-sm shadow-emerald-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Pengaturan
        </button>
        <a href="{{ route('admin.dashboard') }}"
           class="text-sm text-gray-400 hover:text-gray-600 font-medium transition-colors">
            Batal
        </a>
    </div>
</form>



</x-admin-layout>
