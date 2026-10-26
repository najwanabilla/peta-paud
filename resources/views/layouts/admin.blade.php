<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Dashboard') · Admin Peta PAUD Balikpapan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
<div class="min-h-screen md:flex">

    {{-- Bilah atas khusus HP --}}
    <header class="flex items-center justify-between bg-sky-700 px-4 py-3 text-white md:hidden">
        <span class="font-semibold"><i class="fa-solid fa-location-dot mr-2"></i>Peta PAUD Balikpapan</span>
        <button type="button" id="tombol-menu" class="rounded-lg bg-sky-800 px-3 py-2 text-sm" aria-label="Buka atau tutup menu">
            <i class="fa-solid fa-bars"></i> Menu
        </button>
    </header>

    {{-- Menu samping --}}
    <aside id="menu-samping" class="hidden shrink-0 bg-sky-700 text-white md:flex md:w-64 md:flex-col">
        <div class="hidden px-6 py-6 md:block">
            <p class="text-lg font-bold"><i class="fa-solid fa-location-dot mr-2"></i>Peta PAUD</p>
            <p class="text-sm text-sky-100">Panel Admin · Balikpapan</p>
        </div>

        @php
            $menuAktif = 'bg-white/20 font-semibold';
            $menuBiasa = 'hover:bg-white/10';
        @endphp

        <nav class="flex-1 space-y-1 px-3 pb-4">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-3 {{ request()->routeIs('admin.dashboard') ? $menuAktif : $menuBiasa }}">
                <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('admin.paud.index') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-3 {{ request()->routeIs('admin.paud.*') ? $menuAktif : $menuBiasa }}">
                <i class="fa-solid fa-school w-5 text-center"></i> Data PAUD
            </a>
            <a href="{{ route('admin.kecamatan.index') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-3 {{ request()->routeIs('admin.kecamatan.*') ? $menuAktif : $menuBiasa }}">
                <i class="fa-solid fa-map-location-dot w-5 text-center"></i> Kecamatan
            </a>
            <span class="flex cursor-not-allowed items-center gap-3 rounded-lg px-3 py-3 text-sky-200/70" title="Dikerjakan di Tahap 6">
                <i class="fa-solid fa-file-excel w-5 text-center"></i> Impor Excel
                <small class="ml-auto">Segera</small>
            </span>
        </nav>

        <div class="border-t border-white/20 p-3">
            <p class="truncate px-3 pb-2 text-sm text-sky-100">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-left hover:bg-white/10">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Isi halaman --}}
    <main class="min-w-0 flex-1 p-4 md:p-8">
        <h1 class="mb-6 text-2xl font-bold">@yield('judul')</h1>

        @if (session('sukses'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800" role="status">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('sukses') }}</span>
            </div>
        @endif

        @if (session('galat'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800" role="alert">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <span>{{ session('galat') }}</span>
            </div>
        @endif

        @yield('isi')
    </main>
</div>

<script>
    // Buka/tutup menu di HP
    document.getElementById('tombol-menu')?.addEventListener('click', function () {
        document.getElementById('menu-samping').classList.toggle('hidden');
    });

    // Indikator loading: tombol simpan dinonaktifkan agar tidak terklik dua kali
    document.querySelectorAll('form[data-loading]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var tombol = form.querySelector('button[type="submit"]:not([form])');
            if (tombol) {
                tombol.disabled = true;
                tombol.textContent = 'Memproses...';
            }
        });
    });
</script>
</body>
</html>