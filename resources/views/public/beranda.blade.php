
@extends('layouts.public')

@section('judul', 'Peta PAUD Balikpapan')

@push('styles')
<style>
    :root {
        --paud-blue: #2563eb;
        --paud-text: #1e293b;
        --paud-muted: #64748b;
    }

    /* BAGIAN ATAS */
    .home-hero {
        background:
            radial-gradient(ellipse at 8% 12%, rgba(219, 234, 254, .65), transparent 42%),
            linear-gradient(
                110deg,
                #ffffff 0%,
                #f8fbff 45%,
                #eff6ff 75%,
                #ecfdf5 100%
            );
    }

    .hero-content {
        max-width: 1240px;
        margin-inline: auto;
    }

    /* KOTAK PENCARIAN */
    .search-panel {
        box-sizing: border-box;
        width: 100%;
        max-width: 1240px;
        margin-inline: auto;
        padding: 30px;
        background: rgba(255, 255, 255, .97);
        border: 1px solid #e2eaf5;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(37, 99, 235, .06);
    }

    .search-field {
        min-width: 0;
    }

    .search-field label {
        display: block;
        margin-bottom: 9px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .search-control {
        box-sizing: border-box;
        display: block;
        width: 100%;
        height: 54px;
        padding: 0 15px;
        color: var(--paud-text);
        background: #fff;
        border: 1px solid #d8e2f0;
        border-radius: 12px;
        outline: none;
        font-family: inherit;
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s;
    }

    .search-control::placeholder {
        color: #94a3b8;
    }

    .search-control:hover {
        border-color: #b9cbed;
    }

    .search-control:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, .10);
    }

    .search-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        height: 54px;
        padding: 0 16px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 5px 12px rgba(37, 99, 235, .14);
        transition: transform .2s, box-shadow .2s;
    }

    .search-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(37, 99, 235, .20);
    }

    .reset-link {
        color: #2563eb;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .reset-link:hover {
        color: #1e40af;
    }

    /* TOMBOL TAUTAN CEPAT */
    .quick-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 12px 18px;
        border: 1px solid #dbe5f2;
        border-radius: 999px;
        background: #fff;
        color: #475569;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all .2s;
    }

    .quick-link:hover {
        transform: translateY(-2px);
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .quick-link-green {
        border-color: #d1fae5;
        background: #ecfdf5;
        color: #047857;
    }

    .quick-link-green:hover {
        border-color: #a7f3d0;
        background: #d1fae5;
        color: #065f46;
    }

    /* BAGIAN BAWAH: PUTIH KE BIRU MUDA */
    .home-section {
        background: linear-gradient(
            110deg,
            #ffffff 0%,
            #ffffff 15%,
            #fdfdff 28%,
            #f8fbff 43%,
            #f1f6ff 60%,
            #e6efff 82%,
            #dbeafe 100%
        );
    }

    .section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 13px;
        border: 1px solid #dbeafe;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    /* KARTU JENIS SEKOLAH */
    .jenis-card {
        display: block;
        box-sizing: border-box;
        height: 100%;
        padding: 25px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: rgba(255, 255, 255, .88);
        text-decoration: none;
        transition:
            transform .2s,
            box-shadow .2s,
            border-color .2s;
    }

    .jenis-card:hover {
        transform: translateY(-4px);
        border-color: #bfdbfe;
        box-shadow: 0 12px 28px rgba(51, 91, 145, .09);
    }

    .jenis-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: 19px;
        border-radius: 15px;
        font-size: 21px;
    }

    .jenis-arrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        transition: gap .2s;
    }

    .jenis-card:hover .jenis-arrow {
        gap: 12px;
    }

    .jenis-card:focus-visible,
    .quick-link:focus-visible,
    .search-button:focus-visible {
        outline: 3px solid #93c5fd;
        outline-offset: 3px;
    }

    @media (max-width: 640px) {
        .search-panel {
            padding: 19px;
            border-radius: 18px;
        }

        .jenis-card {
            padding: 20px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .jenis-card,
        .search-button,
        .quick-link,
        .jenis-arrow {
            transition: none;
        }
    }
</style>
@endpush

@section('isi')
<div class="min-h-screen bg-white">

    {{-- BAGIAN ATAS DAN PENCARIAN --}}
    <section class="home-hero px-5 py-14 md:py-20">
        <div class="hero-content">

            <div class="mb-10 text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white px-4 py-2 text-sm font-semibold text-blue-700 shadow-sm">
                    <i class="fa-solid fa-location-dot"></i>
                    Peta PAUD Balikpapan
                </span>

                <h1 class="mt-6 text-3xl font-extrabold leading-tight tracking-tight text-slate-800 sm:text-4xl md:text-5xl">
                    Temukan Sekolah PAUD
                    <span class="text-blue-600">Pilihanmu</span>
                </h1>

                <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-500 md:text-lg">
                    Jelajahi informasi sekolah PAUD di Kota Balikpapan.
                    Cari berdasarkan nama, kecamatan, dan jenis sekolah.
                </p>
            </div>

            {{-- KOTAK PENCARIAN --}}
            <form action="{{ route('paud.index') }}" method="GET" class="search-panel">

                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Cari Sekolah PAUD
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Atur pencarian sesuai kebutuhanmu.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

                    {{-- NAMA SEKOLAH --}}
                    <div class="search-field">
                        <label for="q">Nama Sekolah</label>
                        <input
                            id="q"
                            name="q"
                            type="text"
                            value="{{ request('q') }}"
                            placeholder="Contoh: TK Melati"
                            class="search-control"
                        >
                    </div>

                    {{-- KECAMATAN --}}
                    <div class="search-field">
                        <label for="kecamatan">Pilih Kecamatan</label>
                        <select
                            id="kecamatan"
                            name="kecamatan"
                            class="search-control"
                        >
                            <option value="">Semua Kecamatan</option>

                            @foreach ($kecamatans as $kecamatan)
                                <option
                                    value="{{ $kecamatan->slug }}"
                                    @selected(request('kecamatan') === $kecamatan->slug)
                                >
                                    {{ $kecamatan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JENIS SEKOLAH --}}
                    <div class="search-field">
                        <label for="jenis">Jenis Sekolah</label>
                        <select id="jenis" name="jenis" class="search-control">
                            <option value="">Semua Jenis</option>

                            @foreach (\App\Models\Paud::JENIS_SATUAN as $kode => $namaJenis)
                                <option
                                    value="{{ $kode }}"
                                    @selected(request('jenis') === $kode)
                                >
                                    {{ $namaJenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOMBOL CARI --}}
                    <div class="search-field flex flex-col justify-end">
                        <label class="invisible hidden xl:block" aria-hidden="true">
                            Cari sekolah
                        </label>

                        <button type="submit" class="search-button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Cari Sekolah
                        </button>
                    </div>

                </div>

                <div class="mt-5 flex flex-col justify-between gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center">
                    <p class="text-sm leading-6 text-slate-500">
                        <i class="fa-solid fa-circle-info mr-1 text-blue-500"></i>
                        Pilih kecamatan atau jenis sekolah untuk mempersempit pencarian.
                    </p>

                    <a href="{{ route('beranda') }}" class="reset-link shrink-0">
                        <i class="fa-solid fa-rotate-left mr-1"></i>
                        Reset pencarian
                    </a>
                </div>

            </form>

            {{-- TAUTAN CEPAT --}}
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <a href="{{ route('paud.index') }}" class="quick-link">
                    <i class="fa-solid fa-school"></i>
                    Lihat Semua Sekolah
                </a>

                <a href="{{ route('peta') }}" class="quick-link quick-link-green">
                    <i class="fa-solid fa-map-location-dot"></i>
                    Lihat Peta Sebaran
                </a>
            </div>

        </div>
    </section>

    {{-- BAGIAN BAWAH: JENIS SEKOLAH --}}
    <section class="home-section px-5 py-14 md:py-20">
        <div class="mx-auto max-w-7xl">

            <div class="mb-10 text-center">
                <span class="section-label">
                    <i class="fa-solid fa-shapes"></i>
                    PILIHAN SEKOLAH
                </span>

                <h2 class="mt-4 text-2xl font-extrabold text-slate-800 md:text-3xl">
                    Jelajahi Jenis Sekolah
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-500 md:text-base">
                    Pilih kategori sekolah untuk melihat daftar PAUD yang tersedia di Balikpapan.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- TK --}}
                <a href="{{ route('paud.index', ['jenis' => 'TK']) }}" class="jenis-card">
                    <div class="jenis-icon bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-shapes"></i>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800">TK</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Taman Kanak-kanak untuk mendukung perkembangan dan kesiapan belajar anak.
                    </p>

                    <div class="jenis-arrow mt-5 text-blue-600">
                        Lihat sekolah
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                {{-- KB --}}
                <a href="{{ route('paud.index', ['jenis' => 'KB']) }}" class="jenis-card">
                    <div class="jenis-icon bg-emerald-50 text-emerald-600">
                        <i class="fa-solid fa-puzzle-piece"></i>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800">KB</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Kelompok Bermain dengan kegiatan belajar yang menyenangkan melalui permainan.
                    </p>

                    <div class="jenis-arrow mt-5 text-emerald-600">
                        Lihat sekolah
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                {{-- TPA --}}
                <a href="{{ route('paud.index', ['jenis' => 'TPA']) }}" class="jenis-card">
                    <div class="jenis-icon bg-sky-50 text-sky-600">
                        <i class="fa-solid fa-child-reaching"></i>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800">TPA</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Taman Penitipan Anak yang mendukung pengasuhan dan perkembangan anak.
                    </p>

                    <div class="jenis-arrow mt-5 text-sky-600">
                        Lihat sekolah
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                {{-- SPS --}}
                <a href="{{ route('paud.index', ['jenis' => 'SPS']) }}" class="jenis-card">
                    <div class="jenis-icon bg-teal-50 text-teal-600">
                        <i class="fa-solid fa-seedling"></i>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800">SPS</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Satuan PAUD Sejenis untuk mendukung pendidikan anak usia dini.
                    </p>

                    <div class="jenis-arrow mt-5 text-teal-600">
                        Lihat sekolah
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

            </div>
        </div>
    </section>

    {{-- BANNER BIRU BESAR DI BAWAH SUDAH DIHAPUS --}}

</div>
@endsection
