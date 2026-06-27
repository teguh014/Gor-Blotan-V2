<x-customer-layout title="Booking Lapangan">

<div class="mb-6">
    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

    {{-- ── Booking Form ── --}}
    <div class="lg:col-span-3">
        <div class="card p-8">
            <div class="mb-7">
                <h1 class="text-xl font-extrabold text-gray-900 mb-1">Booking Lapangan</h1>
                <p class="text-sm text-gray-400">Pilih lapangan dan tentukan jadwal bermain Anda</p>
            </div>

            <form method="POST" action="{{ route('customer.bookings.store') }}"
                  x-data="bookingForm()" x-init="init()">
                @csrf

                {{-- ── Court Selection ── --}}
                <div class="mb-7">
                    <label class="form-label">Pilih Lapangan <span class="text-red-400">*</span></label>
                    <div class="space-y-3">
                        @foreach($courts as $court)
                        <label class="flex items-center gap-4 border-2 rounded-2xl p-4 cursor-pointer transition-all duration-200"
                               :class="selectedCourt == {{ $court->id }}
                                   ? 'border-emerald-400 bg-emerald-50/50 shadow-sm shadow-emerald-100'
                                   : 'border-gray-100 hover:border-gray-200 hover:shadow-sm'">

                            <input type="radio" name="court_id" value="{{ $court->id }}"
                                   x-model="selectedCourt"
                                   @change="updatePrice({{ $court->price_per_hour }})"
                                   class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500"
                                   {{ old('court_id') == $court->id ? 'checked' : '' }}>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $court->name }}</p>
                                        @if($court->description)
                                        <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ $court->description }}</p>
                                        @endif
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            @foreach(($court->facilities ?? []) as $f)
                                            <span class="text-[10px] font-semibold bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">{{ $f }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-base font-extrabold text-emerald-600">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">/jam</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('court_id')
                    <p class="text-xs text-red-500 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Datetime Pickers ── --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="form-label">Jam Mulai <span class="text-red-400">*</span></label>
                        <input type="datetime-local" name="start_time"
                               value="{{ old('start_time') }}" step="3600"
                               x-model="startTime" @change="calculateTotal()"
                               class="form-input {{ $errors->has('start_time') ? 'form-input-error' : '' }}">
                        @error('start_time')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Jam Selesai <span class="text-red-400">*</span></label>
                        <input type="datetime-local" name="end_time"
                               value="{{ old('end_time') }}" step="3600"
                               x-model="endTime" @change="calculateTotal()"
                               class="form-input {{ $errors->has('end_time') ? 'form-input-error' : '' }}">
                        @error('end_time')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ── Price Preview ── --}}
                <div x-show="estimatedTotal > 0" x-transition
                     class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-5 mb-6">
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-3">Estimasi Biaya</p>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-500">Durasi</span>
                        <span class="font-semibold text-gray-800" x-text="estimatedHours + ' jam'"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 text-sm">Total Estimasi</span>
                        <span class="text-2xl font-extrabold text-emerald-600"
                              x-text="'Rp ' + estimatedTotal.toLocaleString('id-ID')"></span>
                    </div>
                    <p class="text-[10px] text-emerald-500 mt-2">* Total final dihitung server saat konfirmasi</p>
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 text-base justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Konfirmasi Booking
                </button>
            </form>
        </div>
    </div>

    {{-- ── Info Sidebar ── --}}
    <div class="lg:col-span-2 space-y-4">
        {{-- How it works --}}
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
                <span class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                Cara Booking
            </h3>
            <ol class="space-y-4">
                @foreach([
                    ['num'=>'1','text'=>'Pilih lapangan yang tersedia'],
                    ['num'=>'2','text'=>'Tentukan jam mulai & selesai'],
                    ['num'=>'3','text'=>'Klik Konfirmasi → status Pending'],
                    ['num'=>'4','text'=>'Lakukan pembayaran → status Paid'],
                    ['num'=>'5','text'=>'Admin konfirmasi → selesai!'],
                ] as $step)
                <li class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-emerald-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $step['num'] }}</span>
                    <span class="text-sm text-gray-600 leading-relaxed">{{ $step['text'] }}</span>
                </li>
                @endforeach
            </ol>
        </div>

        {{-- Warning --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-amber-800 mb-1">Jadwal Tidak Bisa Double</p>
                    <p class="text-xs text-amber-600 leading-relaxed">Sistem kami otomatis mencegah bentrok jadwal. Lapangan yang sudah dipesan tidak dapat dipilih kembali di jam yang sama.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bookingForm() {
    return {
        selectedCourt: {{ old('court_id', 'null') }},
        pricePerHour: 0,
        startTime: '{{ old('start_time', '') }}',
        endTime: '{{ old('end_time', '') }}',
        estimatedTotal: 0,
        estimatedHours: 0,
        init() { this.calculateTotal(); },
        updatePrice(price) { this.pricePerHour = price; this.calculateTotal(); },
        calculateTotal() {
            if (!this.startTime || !this.endTime || !this.pricePerHour) { this.estimatedTotal = 0; return; }
            const diff = new Date(this.endTime) - new Date(this.startTime);
            if (diff <= 0) { this.estimatedTotal = 0; return; }
            const hours = diff / 3600000;
            this.estimatedHours = Math.round(hours * 10) / 10;
            this.estimatedTotal = Math.round(hours * this.pricePerHour);
        }
    }
}
</script>
@endpush

</x-customer-layout>
