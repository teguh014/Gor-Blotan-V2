<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex items-center justify-center px-4 py-12">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center mx-auto shadow-md shadow-emerald-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                </svg>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-wide uppercase">{{ config('app.name') }}</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-3">Lupa Password?</h1>
        <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset password.</p>
    </div>

    {{-- Session status --}}
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm mb-4">
            {{ session('status') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('email') border-red-400 ring-2 ring-red-200 @enderror"
                       placeholder="nama@email.com">
                @error('email')
                    <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition text-sm shadow-sm mb-4">
                Kirim Tautan Reset Password
            </button>

            <a href="{{ route('login') }}" class="block text-center text-sm font-semibold text-gray-500 hover:text-green-600 transition">
                ← Kembali ke halaman masuk
            </a>
        </form>
    </div>

</div>

</body>
</html>
