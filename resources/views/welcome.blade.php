<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Sewa Lapangan Badminton Online</title>
    <meta name="description" content="Sistem pemesanan lapangan badminton online. Booking mudah, cepat, dan anti-bentrok jadwal.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ── Card hover ── */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .badge-pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ══════════════════════════════════════
           HERO SLIDESHOW
        ══════════════════════════════════════ */
        .hero-slideshow {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Each slide layer */
        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            opacity: 0;
            animation: heroFadeKenBurns 18s ease-in-out infinite;
            will-change: opacity, transform;
        }

        /* Stagger each slide — 3 slides × 6s each = 18s total */
        .hero-slide:nth-child(1) { animation-delay: 0s;   background-image: url('/images/hero/court1.png'); }
        .hero-slide:nth-child(2) { animation-delay: 6s;   background-image: url('/images/hero/court2.png'); }
        .hero-slide:nth-child(3) { animation-delay: 12s;  background-image: url('/images/hero/court3.png'); }

        @keyframes heroFadeKenBurns {
            0%   { opacity: 0;   transform: scale(1.08); }
            8%   { opacity: 1;   transform: scale(1.05); }
            30%  { opacity: 1;   transform: scale(1.0);  }
            38%  { opacity: 0;   transform: scale(0.98); }
            100% { opacity: 0;   transform: scale(1.08); }
        }

        /* Dark overlay for text readability */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(4, 47, 30, 0.72) 0%,
                rgba(4, 47, 30, 0.60) 50%,
                rgba(4, 47, 30, 0.80) 100%
            );
            z-index: 1;
        }

        /* Content sits above overlay */
        .hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        /* Dot indicators */
        .hero-dots {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: flex;
            gap: 10px;
        }
        .hero-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            border: none;
            cursor: pointer;
            padding: 0;
            transition: background 0.3s, transform 0.3s;
            animation: dotActivate 18s linear infinite;
        }
        .hero-dot:nth-child(1) { animation-delay: 0s;  }
        .hero-dot:nth-child(2) { animation-delay: 6s;  }
        .hero-dot:nth-child(3) { animation-delay: 12s; }
        @keyframes dotActivate {
            0%,  5%  { background: rgba(255,255,255,1); transform: scale(1.4); }
            33%, 100%{ background: rgba(255,255,255,0.4); transform: scale(1); }
        }

        /* Slide counter */
        .hero-slide-counter {
            position: absolute;
            top: 28px;
            right: 32px;
            z-index: 3;
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.1em;
            animation: counterCycle 18s linear infinite;
        }
        @keyframes counterCycle {
            0%   { content: '01 / 03'; }
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">

    {{-- ── Navigation ── --}}
    <nav class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 text-base">{{ config('app.name') }}</span>
            </div>
            <div class="flex items-center gap-2 md:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-3 md:px-4 py-2 rounded-lg transition">
                        Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden sm:block text-sm font-medium text-gray-600 hover:text-gray-900 transition px-3 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-3 md:px-4 py-2 rounded-lg transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>


    {{-- ── Hero Section with Slideshow Background ── --}}
    <section class="hero-slideshow text-white py-0" style="min-height:88vh">

        {{-- Background slides --}}
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>

        {{-- Dark overlay --}}
        <div class="hero-overlay"></div>

        {{-- Content --}}
        <div class="hero-content py-28 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-sm font-medium mb-8 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-green-400 badge-pulse"></span>
                    Sistem Booking Online — Tersedia 24/7
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-5 drop-shadow-lg">
                    Booking Lapangan<br>
                    <span class="text-green-300">Badminton</span> Jadi Mudah
                </h1>
                <p class="text-green-100/90 text-lg max-w-2xl mx-auto mb-10 leading-relaxed drop-shadow">
                    Pesan lapangan badminton kapan saja dan di mana saja. Sistem kami secara otomatis
                    mencegah bentrok jadwal sehingga Anda tidak perlu khawatir.
                </p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    @auth
                        <a href="{{ route('customer.bookings.create') }}"
                           class="bg-white text-green-700 font-bold px-8 py-3.5 rounded-xl hover:bg-green-50 transition shadow-xl text-sm">
                            🏸 Booking Sekarang
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="bg-white text-green-700 font-bold px-8 py-3.5 rounded-xl hover:bg-green-50 transition shadow-xl text-sm">
                            🏸 Mulai Booking Gratis
                        </a>
                        <a href="{{ route('login') }}"
                           class="border border-white/40 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition text-sm backdrop-blur-sm">
                            Sudah punya akun? Masuk →
                        </a>
                    @endauth
                </div>

                {{-- Scroll indicator --}}
                <div class="mt-16 flex flex-col items-center gap-2 opacity-60 animate-bounce">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Dot indicators --}}
        <div class="hero-dots">
            <button class="hero-dot" onclick="goSlide(0)" aria-label="Slide 1"></button>
            <button class="hero-dot" onclick="goSlide(1)" aria-label="Slide 2"></button>
            <button class="hero-dot" onclick="goSlide(2)" aria-label="Slide 3"></button>
        </div>

    </section>

    {{-- ── Stats Bar ── --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-6 md:py-8 grid grid-cols-3 gap-4 md:gap-8 text-center">
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-green-600">3+</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Lapangan Tersedia</p>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-green-600">24/7</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Booking Online</p>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-green-600">0</p>
                <p class="text-xs md:text-sm text-gray-500 mt-1">Bentrok Jadwal</p>
            </div>
        </div>
    </section>

    {{-- ── How It Works ── --}}
    <section class="max-w-6xl mx-auto px-4 md:px-6 py-12 md:py-20">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Cara Booking</h2>
            <p class="text-gray-500 text-sm md:text-base">Hanya 4 langkah mudah untuk bermain badminton</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach([
                ['icon' => '📝', 'step' => '01', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dalam hitungan detik'],
                ['icon' => '🏟️', 'step' => '02', 'title' => 'Pilih Lapangan', 'desc' => 'Lihat fasilitas & harga setiap lapangan'],
                ['icon' => '📅', 'step' => '03', 'title' => 'Pilih Jadwal', 'desc' => 'Tentukan jam mulai & selesai bebas bentrok'],
                ['icon' => '💳', 'step' => '04', 'title' => 'Bayar & Main', 'desc' => 'Konfirmasi pembayaran dan siap bermain!'],
            ] as $item)
            <div class="card-hover bg-white border border-gray-200 rounded-2xl p-4 md:p-6 text-center">
                <div class="text-2xl md:text-3xl mb-2 md:mb-3">{{ $item['icon'] }}</div>
                <span class="text-xs font-bold text-green-500 tracking-widest">STEP {{ $item['step'] }}</span>
                <h3 class="font-bold text-gray-900 text-sm mt-1 mb-1">{{ $item['title'] }}</h3>
                <p class="text-xs md:text-sm text-gray-400 leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>


    {{-- ── Schedule Calendar Section ── --}}
    <section class="max-w-6xl mx-auto px-4 md:px-6 py-10 md:py-20">
        <div class="text-center mb-8 md:mb-10">
            <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-4 py-1.5 rounded-full tracking-widest mb-4 uppercase">Live Schedule</span>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Cek Jadwal Lapangan</h2>
            <p class="text-gray-500 text-sm md:text-base max-w-xl mx-auto">Lihat secara langsung jadwal yang sudah dipesan sebelum melakukan booking. Pilih lapangan yang ingin Anda cek.</p>
        </div>
        <x-court-calendar :courts="$courts" />
    </section>

    {{-- ── Features ── --}}
    <section class="bg-gray-900 text-white py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-3">Kenapa Pilih Kami?</h2>
                <p class="text-gray-400">Dirancang untuk kemudahan Anda</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => '🔒', 'title' => 'Anti-Bentrok Jadwal', 'desc' => 'Sistem kami secara otomatis memblokir jadwal yang sudah dipesan, tanpa kemungkinan double booking.'],
                    ['icon' => '⚡', 'title' => 'Real-time Estimasi Harga', 'desc' => 'Lihat estimasi harga secara langsung saat Anda memilih lapangan dan durasi main.'],
                    ['icon' => '📱', 'title' => 'Manajemen Booking Mudah', 'desc' => 'Bayar, batalkan, atau lihat riwayat semua booking Anda dalam satu halaman dashboard.'],
                ] as $f)
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                    <div class="text-3xl mb-4">{{ $f['icon'] }}</div>
                    <h3 class="font-bold text-white text-base mb-2">{{ $f['title'] }}</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA Section (photo background) ── --}}
    <section class="relative text-white py-24 px-6 text-center overflow-hidden">
        {{-- Background photo --}}
        <div style="position:absolute;inset:0;background-image:url('/images/hero/court2.png');background-size:cover;background-position:center;filter:brightness(0.35);"></div>
        {{-- Green tint overlay --}}
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(4,47,30,0.7),rgba(5,96,68,0.6));"></div>
        {{-- Content --}}
        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold mb-4 drop-shadow-lg">Siap Bermain Badminton? 🏸</h2>
            <p class="text-green-100 mb-8 text-base max-w-xl mx-auto">Daftar sekarang dan nikmati kemudahan booking lapangan online. Anti-bentrok, bayar mudah, main langsung!</p>
            @guest
                <a href="{{ route('register') }}"
                   class="inline-block bg-white text-green-700 font-bold px-10 py-4 rounded-xl hover:bg-green-50 transition shadow-xl text-sm">
                    Daftar Gratis Sekarang →
                </a>
            @endguest
            @auth
                <a href="{{ route('customer.bookings.create') }}"
                   class="inline-block bg-white text-green-700 font-bold px-10 py-4 rounded-xl hover:bg-green-50 transition shadow-xl text-sm">
                    🏸 Booking Lapangan →
                </a>
            @endauth
        </div>
    </section>

    {{-- ── Footer ── --}}
    <footer class="bg-gray-900 text-gray-400 py-8 px-6 text-center text-sm">
        <p>© {{ date('Y') }} <span class="text-white font-semibold">{{ config('app.name') }}</span>. Sistem Sewa Lapangan Badminton Online.</p>
    </footer>

    {{-- Slideshow JS helper --}}
    <script>
    // goSlide: manually jump to a slide by restarting animations with offset
    // Called by dot buttons; animation is CSS-driven so we just restart it.
    function goSlide(index) {
        const slides = document.querySelectorAll('.hero-slide');
        const dots   = document.querySelectorAll('.hero-dot');
        const total  = slides.length;
        const duration = 18; // seconds per full cycle
        const perSlide = duration / total;

        slides.forEach((s, i) => {
            s.style.animation = 'none';
            void s.offsetWidth; // reflow
            const delay = ((i - index + total) % total) * perSlide;
            s.style.animation = `heroFadeKenBurns ${duration}s ease-in-out ${-delay === 0 ? '0s' : -delay + 's'} infinite`;
        });
        dots.forEach((d, i) => {
            d.style.animation = 'none';
            void d.offsetWidth;
            const delay = ((i - index + total) % total) * perSlide;
            d.style.animation = `dotActivate ${duration}s linear ${-delay === 0 ? '0s' : -delay + 's'} infinite`;
        });
    }
    </script>

</body>
</html>
