<x-admin-layout title="Edit Lapangan">

    <div class="mb-6">
        <a href="{{ route('admin.courts.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Lapangan
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-6">Edit: {{ $court->name }}</h2>

            <form method="POST" action="{{ route('admin.courts.update', $court) }}" enctype="multipart/form-data"
                  x-data="{ facilities: {{ json_encode(old('facilities', $court->facilities ?? [])) }}, newFacility: '' }">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $court->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $court->description) }}</textarea>
                </div>

                {{-- Foto Lapangan --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Lapangan</label>
                    @if($court->photo_path)
                    <div class="mb-2">
                        <img src="{{ $court->photo_url }}" alt="{{ $court->name }}" class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                    </div>
                    @endif
                    <input type="file" name="photo" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('photo') border-red-400 @enderror">
                    <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, WEBP. Maks 2MB.</p>
                    @error('photo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Jam (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_hour" value="{{ old('price_per_hour', $court->price_per_hour) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('price_per_hour') border-red-400 @enderror"
                           min="1000">
                    @error('price_per_hour') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" x-model="newFacility"
                               @keydown.enter.prevent="if(newFacility.trim()) { facilities.push(newFacility.trim()); newFacility = ''; }"
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                               placeholder="Ketik fasilitas + Enter">
                        <button type="button"
                                @click="if(newFacility.trim()) { facilities.push(newFacility.trim()); newFacility = ''; }"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-2 rounded-lg transition">Tambah</button>
                    </div>
                    <div class="flex flex-wrap gap-2 min-h-[36px]">
                        <template x-for="(item, index) in facilities" :key="index">
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                <span x-text="item"></span>
                                <input type="hidden" :name="'facilities[' + index + ']'" :value="item">
                                <button type="button" @click="facilities.splice(index, 1)" class="ml-1 hover:text-blue-900">×</button>
                            </span>
                        </template>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500"
                               {{ old('is_active', $court->is_active) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Lapangan Aktif (bisa dipesan)</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-2 rounded-lg transition">
                        Perbarui
                    </button>
                    <a href="{{ route('admin.courts.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2 rounded-lg transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
