@extends('layouts.public')

@section('judul', 'Daftar PAUD | Peta PAUD Balikpapan')

@push('styles')
<style>
    .school-card {
        transition: all .25s ease;
    }

    .school-card:hover {
        transform: translateY(-2px);
        border-color: #93c5fd;
        box-shadow: 0 10px 25px rgba(37, 99, 235, .08);
    }

    .school-avatar {
        transition: transform .25s ease;
    }

    .school-card:hover .school-avatar {
        transform: scale(1.05);
    }

    .hero-school {
        background: linear-gradient(120deg, #eaf5ff 0%, #f4f8ff 55%, #e8f8f0 100%);
    }

    .filter-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
    }
</style>
@endpush

@section('isi')

@php
    $warnaAvatar = [
        ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700'],
        ['bg' => 'bg-rose-100', 'text' => 'text-rose-700'],
        ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
        ['bg' => 'bg-sky-100', 'text' => 'text-sky-700'],
        ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
        ['bg' => 'bg-violet-100', 'text' => 'text-violet-700'],
        ['bg' => 'bg-teal-100', 'text' => 'text-teal-700'],
        ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    ];
@endphp

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- HEADER KEMBALI --}}
        <a href="{{ route('beranda') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 transition hover:text-blue-900">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Beranda
        </a>

        {{-- HERO --}}
        <section class="hero-school relative mt-6 overflow-hidden rounded-3xl border border-blue-100 px-6 py-9 sm:px-10 sm:py-12">

            <div class="relative z-10 flex flex-col items-center justify-between gap-7 md:flex-row">

                <div class="max-w-2xl">

                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-blue-700 shadow-sm">
                        <i class="fa-solid fa-graduation-cap"></i>
                        PENDIDIKAN ANAK USIA DINI
                    </div>

                    <h1 class="text-3xl font-extrabold leading-tight text-slate-800 sm:text-4xl lg:text-5xl">
                        Daftar PAUD
                        <span class="text-blue-700">di Balikpapan</span>
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-600 sm:text-base">
                        Temukan informasi sekolah PAUD di Kota Balikpapan.
                        Pilih sekolah yang sesuai dan lihat informasi lengkapnya.
                    </p>

                </div>

                {{-- ILUSTRASI --}}
                <div class="flex h-32 w-32 shrink-0 items-center justify-center rounded-full bg-white/80 shadow-lg shadow-blue-100 sm:h-40 sm:w-40">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-100 text-blue-600 sm:h-32 sm:w-32">
                        <i class="fa-solid fa-school text-5xl sm:text-6xl"></i>
                    </div>
                </div>

            </div>

            <div class="absolute -right-12 -top-16 h-52 w-52 rounded-full bg-blue-200/30"></div>
            <div class="absolute -bottom-20 left-1/3 h-48 w-48 rounded-full bg-emerald-200/30"></div>

        </section>

        {{-- PENCARIAN DAN FILTER --}}
        <section class="mt-7 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="mb-5">
                <h2 class="text-lg font-bold text-slate-800">
                    Temukan Sekolah
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Cari berdasarkan nama, kecamatan, atau jenis sekolah.
                </p>
            </div>

            <form action="{{ route('paud.index') }}" method="GET">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    {{-- PENCARIAN --}}
                    <div>
                        <label for="q" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nama atau alamat sekolah
                        </label>

                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input
                                id="q"
                                type="text"
                                name="q"
                                value="{{ $kataKunci }}"
                                placeholder="Cari nama sekolah..."
                                class="filter-input w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition"
                            >
                        </div>
                    </div>

                    {{-- KECAMATAN --}}
                    <div>
                        <label for="kecamatan" class="mb-2 block text-sm font-semibold text-slate-700">
                            Kecamatan
                        </label>

                        <select
                            id="kecamatan"
                            name="kecamatan"
                            class="filter-input w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition"
                        >
                            <option value="">Semua Kecamatan</option>

                            @foreach ($kecamatans as $kecamatan)
                                <option
                                    value="{{ $kecamatan->slug }}"
                                    @selected($kecamatanTerpilih === $kecamatan->slug)
                                >
                                    {{ $kecamatan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JENIS --}}
                    <div>
                        <label for="jenis" class="mb-2 block text-sm font-semibold text-slate-700">
                            Jenis sekolah
                        </label>

                        <select
                            id="jenis"
                            name="jenis"
                            class="filter-input w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition"
                        >
                            <option value="">Semua Jenis</option>

                            @foreach (\App\Models\Paud::JENIS_SATUAN as $kode => $namaJenis)
                                <option
                                    value="{{ $kode }}"
                                    @selected($jenisTerpilih === $kode)
                                >
                                    {{ $namaJenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- TOMBOL FILTER --}}
                <div class="mt-5 flex flex-wrap gap-3">

                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-md shadow-blue-200 transition hover:bg-blue-800">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Cari Sekolah
                    </button>

                    <a href="{{ route('paud.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset Filter
                    </a>

                </div>

            </form>

        </section>

        {{-- JUDUL HASIL --}}
        <section class="mt-9">

            <div class="flex flex-wrap items-end justify-between gap-4">

                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                            <i class="fa-solid fa-school"></i>
                        </div>

                        <h2 class="text-xl font-extrabold text-slate-800 sm:text-2xl">
                            Daftar Sekolah PAUD
                        </h2>
                    </div>

                    @if ($kecamatanTerpilih)
                        @php
                            $namaKecamatanTerpilih = $kecamatans
                                ->firstWhere('slug', $kecamatanTerpilih)?->nama;
                        @endphp

                        <p class="mt-2 text-sm text-slate-500">
                            Kecamatan {{ $namaKecamatanTerpilih }}
                        </p>
                    @else
                        <p class="mt-2 text-sm text-slate-500">
                            Informasi sekolah PAUD di seluruh kecamatan Balikpapan.
                        </p>
                    @endif

                </div>

                <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-2">
                    <span class="text-sm font-bold text-blue-700">
                        {{ $pauds->total() }} Sekolah
                    </span>
                </div>

            </div>

            {{-- DAFTAR SEKOLAH --}}
            @if ($pauds->count() > 0)

                {{-- SATU KOLOM / SATU BARIS PENUH --}}
                <div class="mt-5 flex flex-col gap-3">

                    @foreach ($pauds as $sekolah)

                        @php
                            // Membuat inisial otomatis dari nama sekolah
                            $namaBersih = preg_replace('/[^\pL\pN]+/u', ' ', trim($sekolah->nama));
                            $kataNama = preg_split('/\s+/', $namaBersih);

                            if (count($kataNama) >= 2) {
                                $inisial = mb_substr($kataNama[0], 0, 1)
                                         . mb_substr($kataNama[1], 0, 1);
                            } else {
                                $inisial = mb_substr($kataNama[0], 0, 2);
                            }

                            $inisial = mb_strtoupper($inisial);

                            // Warna avatar bergantian
                            $warna = $warnaAvatar[$loop->index % count($warnaAvatar)];

                            // Warna berdasarkan jenis sekolah
                            $warnaJenis = match ($sekolah->jenis_satuan) {
                                'TK' => 'bg-rose-100 text-rose-700',
                                'KB' => 'bg-violet-100 text-violet-700',
                                'TPA' => 'bg-amber-100 text-amber-700',
                                'SPS' => 'bg-sky-100 text-sky-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp

                        <article class="school-card group relative flex w-full items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:gap-5 sm:p-5">

                            {{-- NOMOR --}}
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-500 sm:h-8 sm:w-8">
                                {{ $pauds->firstItem() + $loop->index }}
                            </div>

                            {{-- BAGIAN AVATAR / FOTO (DIMODIFIKASI) --}}
                            @if ($sekolah->fotoUtama)
                                {{-- Jika ada foto, tampilkan gambar --}}
                                <div class="school-avatar flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl shadow-sm sm:h-[68px] sm:w-[68px] sm:rounded-2xl">
                                    <img src="{{ $sekolah->fotoUtama->url }}" alt="{{ $sekolah->nama }}" class="h-full w-full object-cover">
                                </div>
                            @else
                                {{-- Jika tidak ada foto, tetap tampilkan inisial seperti desain asli --}}
                                <div class="school-avatar flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $warna['bg'] }} {{ $warna['text'] }} shadow-sm sm:h-[68px] sm:w-[68px] sm:rounded-2xl">
                                    <span class="text-lg font-extrabold tracking-wide sm:text-2xl">
                                        {{ $inisial }}
                                    </span>
                                </div>
                            @endif

                            {{-- INFORMASI SEKOLAH --}}
                            <div class="min-w-0 flex-1">

                                <a href="{{ route('paud.show', $sekolah) }}"
                                   class="block break-words text-sm font-extrabold leading-snug text-slate-800 transition hover:text-blue-700 sm:text-lg">
                                    {{ $sekolah->nama }}
                                </a>

                                {{-- BADGE --}}
                                <div class="mt-2 flex flex-wrap items-center gap-2">

                                    @if ($sekolah->jenis_satuan)
                                        <span class="rounded-lg px-3 py-1 text-xs font-bold {{ $warnaJenis }}">
                                            {{ $sekolah->jenis_satuan }}
                                        </span>
                                    @endif

                                    @if ($sekolah->status)
                                        <span class="rounded-lg px-3 py-1 text-xs font-bold
                                            {{ $sekolah->status === 'Negeri'
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-amber-100 text-amber-700' }}">
                                            {{ $sekolah->status }}
                                        </span>
                                    @endif

                                </div>

                                {{-- ALAMAT --}}
                                <div class="mt-2 flex items-start gap-2 text-xs leading-5 text-slate-500 sm:text-sm">

                                    <i class="fa-solid fa-location-dot mt-1 shrink-0 text-emerald-600"></i>

                                    <p class="line-clamp-2">
                                        @if ($sekolah->kelurahan)
                                            {{ $sekolah->kelurahan }},
                                        @endif

                                        @if ($sekolah->kecamatan)
                                            {{ $sekolah->kecamatan->nama }}
                                        @else
                                            Kecamatan belum tersedia
                                        @endif
                                    </p>

                                </div>

                            </div>

                            {{-- TOMBOL DETAIL --}}
                            <a href="{{ route('paud.show', $sekolah) }}"
                               aria-label="Lihat profil {{ $sekolah->nama }}"
                               class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-700 transition hover:bg-blue-700 hover:text-white sm:h-11 sm:w-11">

                                <i class="fa-solid fa-arrow-right"></i>

                            </a>

                        </article>

                    @endforeach

                </div>

                {{-- PAGINATION --}}
                <div class="mt-8">
                    {{ $pauds->withQueryString()->links() }}
                </div>

            @else

                {{-- SEKOLAH TIDAK DITEMUKAN --}}
                <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-white px-5 py-16 text-center">

                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-school text-3xl"></i>
                    </div>

                    <h3 class="mt-5 text-xl font-extrabold text-slate-800">
                        Sekolah tidak ditemukan
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Maaf, sekolah yang kamu cari belum ditemukan.
                        Coba gunakan kata kunci atau filter yang berbeda.
                    </p>

                    <a href="{{ route('paud.index') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <i class="fa-solid fa-rotate-left"></i>
                        Tampilkan Semua Sekolah
                    </a>

                </div>

            @endif

        </section>

        {{-- FOOTER --}}
        <div class="mt-12 border-t border-slate-200 py-6 text-center">
            <p class="text-sm font-medium text-slate-500">
                <i class="fa-solid fa-heart mr-1 text-rose-400"></i>
                Pendidikan Anak Usia Dini untuk Masa Depan yang Lebih Baik.
            </p>
            <p class="mt-1 text-xs text-slate-400">
                Peta PAUD Balikpapan
            </p>
        </div>

    </div>

</div>

@endsection