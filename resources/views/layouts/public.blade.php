<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Peta PAUD Balikpapan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <link href="https://unpkg.com/maplibre-gl@4.1.2/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@4.1.2/dist/maplibre-gl.js"></script>

    <style>
        /* Font ceria untuk anak-anak */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
        body { font-family: 'Nunito', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#e0f2fe] text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Navbar Baru (Putih Bersih) -->
    <header class="bg-white shadow-sm px-4 py-3 md:px-8 flex justify-between items-center z-50 relative">
        <a href="{{ route('beranda') }}" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
            <!-- Logo Pin Maps dengan Titik Kuning -->
            <div class="w-10 h-10">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#2563eb"/>
                    <circle cx="12" cy="9" r="3" fill="#facc15"/>
                </svg>
            </div>
            <div>
                <span class="font-extrabold text-xl text-[#1e3a8a] block leading-tight">Peta PAUD</span>
                <span class="text-sm font-bold text-[#1e3a8a] block">Balikpapan</span>
            </div>
        </a>
        <div>
            <a href="{{ route('login') }}" class="text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <i class="fa-solid fa-user"></i> Login Admin
            </a>
        </div>
    </header>

    <!-- Konten -->
    <main class="flex-1 flex flex-col relative w-full overflow-x-hidden">
        @yield('isi')
    </main>

    @stack('scripts')
</body>
</html>