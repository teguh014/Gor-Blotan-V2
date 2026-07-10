<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | {{ \App\Models\Setting::get('venue_name', config('app.name')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden bg-gray-50">

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden"
         style="display:none"></div>

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static inset-y-0 left-0 z-30 flex flex-col w-64 flex-shrink-0 sidebar-bg text-white
                  transform transition-transform duration-200 ease-in-out">

        <div class="flex items-center justify-between gap-3 px-6 py-5 border-b border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-green-600
                            flex items-center justify-center shadow-lg shadow-emerald-900/50 overflow-hidden flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white leading-tight">
                        {{ \App\Models\Setting::get('venue_name', config('app.name')) }}
                    </p>
                    <p class="text-[10px] font-semibold text-emerald-400 uppercase tracking-widest">
                        {{ \App\Models\Setting::get('venue_tagline', 'Admin Panel') ?: 'Admin Panel' }}
                    </p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded-lg hover:bg-white/10">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen=false"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.courts.index') }}" @click="sidebarOpen=false"
               class="nav-item {{ request()->routeIs('admin.courts*') ? 'nav-item-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Kelola Lapangan</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}" @click="sidebarOpen=false"
               class="nav-item {{ request()->routeIs('admin.bookings*') ? 'nav-item-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Semua Booking</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" @click="sidebarOpen=false"
               class="nav-item {{ request()->routeIs('admin.reports*') ? 'nav-item-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Laporan Keuangan</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" @click="sidebarOpen=false"
               class="nav-item {{ request()->routeIs('admin.settings*') ? 'nav-item-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pengaturan Gedung</span>
            </a>
        </nav>

        {{-- User Profile --}}
        <div class="px-3 py-4 border-t border-white/5">
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-white/5 transition-colors mb-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-red-400 hover:bg-red-500/10 hover:text-red-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Bar --}}
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-sm md:text-base font-bold text-gray-800 truncate">{{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
                <span class="text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 md:px-3 py-1.5 rounded-full">
                    ✓ Admin
                </span>
                <div class="hidden sm:block text-xs text-gray-400">{{ now()->format('d M Y') }}</div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error'))
        <div class="px-4 md:px-8 pt-5">
            @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3.5 text-sm animate-fade-in">
                <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-3.5 text-sm animate-fade-in">
                <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-8 py-6 animate-fade-in">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
