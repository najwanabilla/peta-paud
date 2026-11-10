<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Judul Dinamis --}}
    <title>@yield('judul', 'Peta PAUD Balikpapan')</title>

    {{-- FAVICON BARU (Logo Peta Biru-Kuning) --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Font Awesome (Ikon) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Google Fonts (Nunito) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack untuk CSS Tambahan per halaman --}}
    @stack('styles')
</head>
<body class="bg-white font-sans text-slate-800 antialiased overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 border-b border-slate-100 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5">
            
            {{-- Logo & Teks --}}
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 group">
                {{-- Ikon Logo --}}
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 transition-transform group-hover:scale-105">
                    <svg viewBox="0 0 100 100" class="h-8 w-8">
                        <path d="M50 15 C 29 15 16 29 16 47 C 16 70 50 90 50 90 C 50 90 84 70 84 47 C 84 29 71 15 50 15 Z" fill="#4d7ef9"/>
                        <circle cx="50" cy="47" r="12" fill="#fcd116"/>
                    </svg>
                </div>
                {{-- Teks --}}
                <div>
                    <span class="block text-lg font-black leading-tight text-slate-800">Peta PAUD</span>
                    <span class="block text-xs font-bold text-slate-500">Balikpapan</span>
                </div>
            </a>

            {{-- Tombol Login / Menu --}}
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700 active:scale-95">
                            <i class="fa-solid fa-gauge"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-700 active:scale-95">
                            <i class="fa-solid fa-user"></i> Login Admin
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="min-h-screen">
        @yield('isi')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-100 bg-white py-8 mt-auto">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-5 md:flex-row">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <span class="text-lg font-black text-slate-800">Peta PAUD</span>
            </div>
            <p class="text-sm font-semibold text-slate-500">
                &copy; {{ date('Y') }} Sistem Informasi PAUD Balikpapan. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- Stack untuk JS Tambahan per halaman --}}
    @stack('scripts')
</body>
</html>