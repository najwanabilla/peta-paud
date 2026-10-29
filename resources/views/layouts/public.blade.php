
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('judul', 'Peta PAUD Balikpapan')</title>

    {{-- CSS dan JavaScript Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    {{-- MapLibre GL --}}
    <link
        rel="stylesheet"
        href="https://unpkg.com/maplibre-gl@4.1.2/dist/maplibre-gl.css"
    >

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        button {
            font-family: inherit;
        }
    </style>

    {{-- CSS tambahan setiap halaman --}}
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col bg-sky-50 text-slate-800 antialiased">

    {{-- Navbar --}}
    <header class="relative z-50 flex items-center justify-between border-b border-slate-100 bg-white px-4 py-3 shadow-sm md:px-8">

        {{-- Logo dan Nama Website --}}
        <a
            href="{{ route('beranda') }}"
            class="flex items-center gap-3 transition-opacity hover:opacity-80"
            aria-label="Peta PAUD Balikpapan - Beranda"
        >
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-9 w-9"
                    aria-hidden="true"
                >
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                        fill="#2563eb"
                    />
                    <circle cx="12" cy="9" r="3" fill="#facc15"/>
                </svg>
            </div>

            <div>
                <span class="block text-xl font-extrabold leading-tight text-blue-900">
                    Peta PAUD
                </span>

                <span class="block text-sm font-bold text-blue-700">
                    Balikpapan
                </span>
            </div>
        </a>

        {{-- Tombol Login Admin --}}
        <a
            href="{{ route('login') }}"
            class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:bg-blue-700 hover:shadow-lg sm:px-5"
        >
            <i class="fa-solid fa-user" aria-hidden="true"></i>
            <span>Login Admin</span>
        </a>

    </header>

    {{-- Konten Utama --}}
    <main class="relative flex w-full flex-1 flex-col overflow-x-hidden">
        @yield('isi')
    </main>

    {{-- JavaScript MapLibre --}}
    <script src="https://unpkg.com/maplibre-gl@4.1.2/dist/maplibre-gl.js"></script>

    {{-- JavaScript tambahan setiap halaman --}}
    @stack('scripts')

</body>
</html>
