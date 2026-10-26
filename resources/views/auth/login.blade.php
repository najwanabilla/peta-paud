<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Admin · Peta PAUD Balikpapan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-sky-50 px-4 py-8 text-slate-800 antialiased">
<main class="w-full max-w-md">
    <div class="mb-6 text-center">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-600 text-2xl text-white">
            <i class="fa-solid fa-location-dot"></i>
        </div>
        <h1 class="text-2xl font-bold">Masuk Admin</h1>
        <p class="text-slate-600">Peta PAUD Balikpapan</p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @if (session('sukses'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800" role="status">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('sukses') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" data-loading class="space-y-4">
            @csrf

            <div>
                <label for="email" class="mb-1 block font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block font-medium">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300">
                Ingat saya di perangkat ini
            </label>

            <button type="submit" class="w-full rounded-xl bg-sky-600 px-4 py-3 font-semibold text-white hover:bg-sky-700 disabled:opacity-60">
                Masuk
            </button>
        </form>
    </div>

    <p class="mt-4 text-center text-sm">
        <a href="{{ route('beranda') }}" class="text-sky-700 hover:underline">← Kembali ke beranda</a>
    </p>
</main>
</body>
</html>