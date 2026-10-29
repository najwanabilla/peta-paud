@extends('layouts.public')

@section('judul', 'Peta PAUD Balikpapan')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .font-playful { font-family: 'Nunito', sans-serif; }

    .hero-section {
        background-color: #f0f7ff;
        background-image:
            radial-gradient(circle at 85% 10%, rgba(191, 219, 254, 0.85) 0%, transparent 50%),
            radial-gradient(circle at 5% 95%, rgba(224, 242, 254, 0.9) 0%, transparent 45%);
    }

    .titik-peta {
        background-image: radial-gradient(rgba(255, 255, 255, 0.28) 1.5px, transparent 1.5px);
        background-size: 22px 22px;
    }

    .card-lift { transition: transform .25s ease, box-shadow .25s ease; }
    .card-lift:hover { transform: translateY(-4px); box-shadow: 0 18px 30px -12px rgba(15, 23, 42, .18); }
</style>
@endpush

@section('isi')
@php
    // Idealnya angka ini dikirim dari controller. Ditaruh di sini supaya langsung jalan.
    $totalPaud       = \App\Models\Paud::count();
    $jumlahKelurahan = \App\Models\Paud::whereNotNull('kelurahan')->distinct()->count('kelurahan');
    $perKecamatan    = \App\Models\Paud::selectRaw('kecamatan_id, COUNT(*) as n')->groupBy('kecamatan_id')->pluck('n', 'kecamatan_id');
    $perJenis        = \App\Models\Paud::selectRaw('jenis_satuan, COUNT(*) as n')->groupBy('jenis_satuan')->pluck('n', 'jenis_satuan');
    $terbanyak       = max(1, (int) $perKecamatan->max());

    // Warna jenis disamakan dengan legenda di halaman peta
    $jenisWarna = ['KB' => 'bg-blue-500', 'TK' => 'bg-red-500', 'TPA' => 'bg-emerald-500', 'SPS' => 'bg-purple-500'];
@endphp

<div class="font-playful bg-white">

    {{-- HERO --}}
    <section class="hero-section px-5 pb-20 pt-12 md:pb-24 md:pt-16">
        <div class="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-2">

            <div>
                <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white px-4 py-1.5 text-sm font-bold text-blue-700 shadow-sm">
                    <i class="fa-solid fa-location-dot text-blue-500"></i>
                    Sistem Informasi PAUD Balikpapan
                </span>

                <h1 class="mb-4 text-4xl font-black leading-tight tracking-tight text-slate-800 sm:text-5xl">
                    Cari Sekolah PAUD<br>
                    <span class="text-blue-600">Dekat Rumahmu</span>
                </h1>

                <p class="mb-8 max-w-xl text-lg font-semibold text-slate-500">
                    Temukan ratusan PAUD di Balikpapan dengan mudah. Dilengkapi peta lokasi, profil sekolah, dan petunjuk jalan.
                </p>

                {{-- PENCARIAN --}}
                <div class="max-w-xl rounded-2xl border border-slate-100 bg-white p-4 shadow-xl shadow-blue-900/5 sm:p-5">
                    <form action="{{ route('paud.index') }}" method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
                        <div>
                            <label for="q" class="mb-1.5 block text-sm font-bold text-slate-700">Nama Sekolah</label>
                            <input id="q" name="q" type="text" value="{{ request('q') }}" placeholder="Ketik nama..."
                                   class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="kecamatan" class="mb-1.5 block text-sm font-bold text-slate-700">Kecamatan</label>
                            <select id="kecamatan" name="kecamatan"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->slug }}" @selected(request('kecamatan') === $kecamatan->slug)>{{ $kecamatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                                class="flex h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 font-bold text-white transition-colors hover:bg-blue-700">
                            <i class="fa-solid fa-magnifying-glass"></i> Cari
                        </button>
                    </form>
                </div>

                {{-- JENIS (jalan pintas) --}}
                <div class="mt-5 flex max-w-xl flex-wrap items-center gap-2">
                    <span class="mr-1 text-sm font-bold text-slate-500">Jenis:</span>
                    @foreach (\App\Models\Paud::JENIS_SATUAN as $kode => $namaJenis)
                        <a href="{{ route('paud.index', ['jenis' => $kode]) }}" title="{{ $namaJenis }}"
                           class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-sm font-extrabold text-slate-700 transition-colors hover:border-blue-300 hover:text-blue-700">
                            <span class="h-2.5 w-2.5 rounded-full {{ $jenisWarna[$kode] ?? 'bg-slate-400' }}"></span>
                            {{ $kode }}
                            <span class="font-bold text-slate-400">{{ number_format($perJenis[$kode] ?? 0, 0, ',', '.') }}</span>
                        </a>
                    @endforeach
                </div>

                <a href="{{ route('peta') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl border-2 border-blue-600 px-5 py-2.5 text-sm font-extrabold text-blue-700 transition-colors hover:bg-blue-600 hover:text-white">
                    <i class="fa-solid fa-map-location-dot"></i> Lihat Peta Sebaran
                </a>
            </div>

            {{-- ILUSTRASI (dekoratif) --}}
            <div class="mx-auto hidden w-full max-w-md lg:block" aria-hidden="true">
                <div class="titik-peta relative aspect-square overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 shadow-2xl shadow-blue-600/25">
                    <span class="absolute left-[16%] top-[20%] flex h-11 w-11 items-center justify-center rounded-full bg-white text-blue-500 shadow-lg"><i class="fa-solid fa-location-dot"></i></span>
                    <span class="absolute right-[18%] top-[16%] flex h-10 w-10 items-center justify-center rounded-full bg-white text-red-500 shadow-lg"><i class="fa-solid fa-location-dot"></i></span>
                    <span class="absolute bottom-[22%] right-[12%] flex h-11 w-11 items-center justify-center rounded-full bg-white text-emerald-500 shadow-lg"><i class="fa-solid fa-location-dot"></i></span>
                    <span class="absolute bottom-[18%] left-[14%] flex h-10 w-10 items-center justify-center rounded-full bg-white text-purple-500 shadow-lg"><i class="fa-solid fa-location-dot"></i></span>

                    <div class="absolute inset-x-10 top-1/2 -translate-y-1/2 rounded-2xl bg-white p-5 text-center shadow-2xl">
                        <div class="text-4xl font-black text-blue-600">{{ number_format($totalPaud, 0, ',', '.') }}</div>
                        <div class="text-sm font-bold text-slate-500">PAUD tersebar di {{ $jumlahKelurahan }} kelurahan</div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- PER KECAMATAN --}}
    <section class="bg-slate-50 px-5 py-16">
        <div class="mx-auto max-w-6xl">
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-black text-slate-800">Jelajahi per Kecamatan</h2>
                <p class="mt-2 font-semibold text-slate-500">Lihat PAUD yang ada di wilayah terdekat Anda.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($kecamatans as $kec)
                    @php $n = (int) ($perKecamatan[$kec->id] ?? 0); @endphp
                    <a href="{{ route('paud.index', ['kecamatan' => $kec->slug]) }}"
                       class="card-lift rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="h-3 w-3 rounded-full" style="background: {{ $kec->warna }}"></span>
                                <h3 class="font-extrabold text-slate-800">{{ $kec->nama }}</h3>
                            </div>
                            <i class="fa-solid fa-arrow-right text-sm text-slate-400"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-500"><span class="text-xl font-black text-slate-800">{{ $n }}</span> PAUD</p>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full" style="width: {{ round($n / $terbanyak * 100) }}%; background: {{ $kec->warna }}"></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</div>
@endsection