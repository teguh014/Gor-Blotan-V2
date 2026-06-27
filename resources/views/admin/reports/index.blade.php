<x-admin-layout title="Laporan Keuangan">

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Laporan Keuangan</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan pendapatan dari transaksi yang telah dibayar atau selesai.</p>
        </div>
        
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-end gap-3">
            <div>
                <label for="month" class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Pilih Bulan</label>
                <input type="month" name="month" id="month" value="{{ $month }}" 
                       class="border-gray-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-2"
                       onchange="this.form.submit()">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2 rounded-xl text-sm transition-colors shadow-sm">
                Filter
            </button>
        </form>
    </div>

    {{-- ── Summary Cards ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Total Revenue --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-200 z-10">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="z-10">
                <p class="text-sm font-semibold text-gray-500 mb-1">Total Pendapatan</p>
                <h3 class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Total Bookings --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-center gap-5 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-200 z-10">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="z-10">
                <p class="text-sm font-semibold text-gray-500 mb-1">Transaksi Sukses</p>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalBookings }} <span class="text-base font-medium text-gray-400">booking</span></h3>
            </div>
        </div>
    </div>

    {{-- ── Data Table ── --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Rincian Transaksi</h3>
            <button onclick="window.print()" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Tanggal & Waktu</th>
                        <th class="px-6 py-4 font-semibold">Penyewa</th>
                        <th class="px-6 py-4 font-semibold">Lapangan</th>
                        <th class="px-6 py-4 font-semibold text-right">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">
                                #{{ $booking->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $booking->start_time->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                        {{ strtoupper(substr($booking->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $booking->user->name ?? 'Tamu' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $booking->court->name }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p>Tidak ada transaksi sukses pada bulan ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        @media print {
            /* Sembunyikan elemen yang tidak perlu dicetak */
            aside, header, form, button, .cal-header-bg-glow {
                display: none !important;
            }
            
            /* Reset body dan container untuk print */
            body, html {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .flex-1 { overflow: visible !important; }
            main { padding: 0 !important; }

            /* Atur ulang tampilan grid untuk kertas */
            .grid { 
                display: flex !important; 
                gap: 20px !important; 
            }
            .grid > div { 
                flex: 1 !important; 
                border: 1px solid #000 !important; 
                box-shadow: none !important; 
                padding: 15px !important;
            }

            /* Hapus background warna dan shadow */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .shadow-sm, .shadow-lg { box-shadow: none !important; }
            
            /* Tabel lebih tegas */
            table { border-collapse: collapse !important; width: 100% !important; border: 1px solid #000 !important; }
            th, td { border: 1px solid #000 !important; padding: 10px !important; color: #000 !important; }
            thead { background: #f3f4f6 !important; }
            
            /* Sembunyikan elemen hiasan */
            .absolute { display: none !important; }
        }
    </style>
</x-admin-layout>
