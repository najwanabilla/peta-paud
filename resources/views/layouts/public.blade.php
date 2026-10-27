<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Peta PAUD Balikpapan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Pustaka Peta OpenFreeMap (MapLibre) --}}
    <link href="https://unpkg.com/maplibre-gl@4.1.2/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@4.1.2/dist/maplibre-gl.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen flex flex-col overflow-hidden">

    {{-- Bilah Navigasi Atas --}}
    <header class="bg-sky-700 text-white px-4 py-3 md:px-6 flex justify-between items-center shadow-md z-10">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-location-dot text-xl"></i>
            <div>
                <span class="font-bold text-lg block leading-tight">Peta PAUD Balikpapan</span>
                <span class="text-xs text-sky-100 block">Sistem Informasi Sebaran PAUD</span>
            </div>
        </div>
        <div>
            <a href="{{ route('login') }}" class="text-sm bg-sky-800 hover:bg-sky-900 px-4 py-2 rounded-lg transition-colors">
                <i class="fa-solid fa-user-lock mr-1"></i> Masuk Admin
            </a>
        </div>
    </header>

    {{-- Area Isi (Peta) --}}
    <main class="flex-1 relative flex">
        @yield('isi')
    </main>

    {{-- Wadah untuk menyisipkan JavaScript khusus halaman --}}
    @stack('scripts')

</body>
</html>