
@extends('layouts.public')

@section('judul', $paud->nama . ' | Peta PAUD Balikpapan')

@push('styles')
<style>
    .detail-page {
        background: #f5f8fc;
    }

    .detail-panel {
        border: 1px solid #e5edf7;
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .045);
    }

    .school-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(120deg, #e8f2ff 0%, #f2f7ff 55%, #e8f8f1 100%);
        border: 1px solid #dbeafe;
    }

    .hero-icon {
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        box-shadow: 0 12px 30px rgba(37, 99, 235, .20);
    }

    .info-card {
        border: 1px solid #e5edf7;
        background: #f8faff;
        border-radius: 16px;
        padding: 17px;
        transition: all .25s ease;
    }

    .info-card:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        transform: translateY(-2px);
    }

    .info-icon {
        display: flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 13px;
        background: #dbeafe;
        color: #2563eb;
        font-size: 17px;
    }

    .description-box {
        background: linear-gradient(135deg, #f0f7ff, #f5fbff);
        border: 1px solid #dbeafe;
        border-radius: 18px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-icon {
        display: flex;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #dbeafe;
        color: #2563eb;
    }

    #peta-detail {
        width: 100%;
        height: 400px;
        border-radius: 17px;
        overflow: hidden;
    }

    .back-link {
        transition: all .2s ease;
    }

    .back-link:hover {
        transform: translateX(-4px);
    }

    @media (max-width: 640px) {
        #peta-detail {
            height: 320px;
        }
    }
</style>
@endpush

@section('isi')

@php
    $warnaJenis = match ($paud->jenis_satuan) {
        'TK' => 'bg-rose-100 text-rose-700',
        'KB' => 'bg-violet-100 text-violet-700',
        'TPA' => 'bg-amber-100 text-amber-700',
        'SPS' => 'bg-sky-100 text-sky-700',
        default => 'bg-blue-100 text-blue-700',
    };

    $warnaStatus = $paud->status === 'Negeri'
        ? 'bg-emerald-100 text-emerald-700'
        : 'bg-amber-100 text-amber-700';

    $namaBersih = preg_replace('/[^\pL\pN]+/u', ' ', trim($paud->nama));
    $kataNama = preg_split('/\s+/', $namaBersih);

    $inisial = count($kataNama) >= 2
        ? mb_substr($kataNama[0], 0, 1) . mb_substr($kataNama[1], 0, 1)
        : mb_substr($kataNama[0], 0, 2);

    $alamatLengkap = $paud->alamat ?: 'Alamat belum tersedia';

    if ($paud->kelurahan) {
        $alamatLengkap .= ', Kelurahan ' . $paud->kelurahan;
    }

    if ($paud->kecamatan) {
        $alamatLengkap .= ', Kecamatan ' . $paud->kecamatan->nama;
    }

    if ($paud->kode_pos) {
        $alamatLengkap .= ', ' . $paud->kode_pos;
    }

    $alamatLengkap .= ', Balikpapan';
@endphp

<div class="detail-page min-h-screen px-4 py-8 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-6xl">

        {{-- NAVIGASI --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">

            <a href="{{ route('paud.index', ['jenis' => $paud->jenis_satuan]) }}"
               class="back-link inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-900">

                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke daftar sekolah

            </a>

            <span class="text-xs font-medium text-slate-400">
                Peta PAUD / Profil Sekolah
            </span>

        </div>


        {{-- HERO PROFIL SEKOLAH --}}
        <section class="school-hero relative rounded-3xl p-6 sm:p-8 lg:p-10">

            <div class="relative z-10 flex flex-col items-center gap-6 md:flex-row">

                {{-- IKON SEKOLAH --}}
                <div class="hero-icon flex h-24 w-24 shrink-0 items-center justify-center rounded-3xl text-white sm:h-28 sm:w-28">

                    <i class="fa-solid fa-school text-5xl sm:text-6xl"></i>

                </div>

                {{-- INFORMASI UTAMA --}}
                <div class="min-w-0 flex-1 text-center md:text-left">

                    <div class="mb-4 flex flex-wrap items-center justify-center gap-2 md:justify-start">

                        <span class="rounded-full bg-white px-4 py-2 text-xs font-extrabold text-blue-700 shadow-sm">
                            {{ $paud->jenis_satuan ?? 'PAUD' }}
                        </span>

                        @if ($paud->status)
                            <span class="rounded-full bg-white px-4 py-2 text-xs font-bold shadow-sm
                                {{ $warnaStatus }}">
                                {{ $paud->status }}
                            </span>
                        @endif

                        <span class="rounded-full bg-white/80 px-4 py-2 text-xs font-semibold text-slate-600">
                            <i class="fa-solid fa-location-dot mr-1 text-emerald-600"></i>
                            Balikpapan
                        </span>

                    </div>

                    <h1 class="break-words text-3xl font-extrabold leading-tight text-slate-800 sm:text-4xl lg:text-5xl">
                        {{ $paud->nama }}
                    </h1>

                    <p class="mt-4 flex items-start justify-center gap-2 text-sm leading-7 text-slate-600 md:justify-start sm:text-base">

                        <i class="fa-solid fa-map-marker-alt mt-1 shrink-0 text-emerald-600"></i>

                        <span>{{ $alamatLengkap }}</span>

                    </p>

                </div>

            </div>

            {{-- DEKORASI --}}
            <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-200/30"></div>

            <div class="pointer-events-none absolute -bottom-24 right-1/3 h-56 w-56 rounded-full bg-emerald-200/30"></div>

        </section>


        {{-- INFORMASI IDENTITAS SEKOLAH --}}
        <section class="mt-6">

            <div class="mb-4 section-heading">

                <div class="section-icon">
                    <i class="fa-solid fa-id-card"></i>
                </div>

                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">
                        Identitas Sekolah
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Informasi umum satuan pendidikan.
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                {{-- NPSN --}}
                <div class="info-card">

                    <div class="flex items-center gap-3">

                        <div class="info-icon">
                            <i class="fa-solid fa-id-card"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-medium text-slate-500">
                                NPSN
                            </p>

                            <p class="mt-1 break-words font-extrabold text-slate-800">
                                {{ $paud->npsn ?: 'Belum tersedia' }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- KEPALA SEKOLAH --}}
                <div class="info-card">

                    <div class="flex items-center gap-3">

                        <div class="info-icon">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-medium text-slate-500">
                                Kepala Sekolah
                            </p>

                            <p class="mt-1 break-words font-extrabold text-slate-800">
                                {{ $paud->kepala_sekolah ?: 'Belum tersedia' }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- TELEPON --}}
                <div class="info-card">

                    <div class="flex items-center gap-3">

                        <div class="info-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-medium text-slate-500">
                                Telepon
                            </p>

                            <p class="mt-1 break-words font-extrabold text-slate-800">
                                {{ $paud->telepon ?: 'Belum tersedia' }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- KECAMATAN --}}
                <div class="info-card">

                    <div class="flex items-center gap-3">

                        <div class="info-icon">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-medium text-slate-500">
                                Kecamatan
                            </p>

                            <p class="mt-1 break-words font-extrabold text-slate-800">
                                {{ $paud->kecamatan?->nama ?? 'Belum tersedia' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- TENTANG SEKOLAH --}}
        <section class="detail-panel mt-7 p-6 sm:p-8">

            <div class="section-heading">

                <div class="section-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">
                        Tentang Sekolah
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Profil dan gambaran umum sekolah.
                    </p>
                </div>

            </div>


            <div class="description-box mt-6 p-5 sm:p-7">

                <div class="mb-4 flex items-center gap-2 text-blue-700">
                    <i class="fa-solid fa-quote-left text-xl"></i>

                    <span class="text-sm font-bold">
                        Profil {{ $paud->nama }}
                    </span>
                </div>

                @if ($paud->deskripsi)

                    <p class="whitespace-pre-line text-sm leading-8 text-slate-700 sm:text-base">
                        {{ $paud->deskripsi }}
                    </p>

                @else

                    <div class="py-3 text-center">

                        <i class="fa-solid fa-file-circle-info text-3xl text-blue-300"></i>

                        <p class="mt-3 font-semibold text-slate-600">
                            Deskripsi sekolah belum tersedia.
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Informasi profil sekolah akan ditampilkan setelah dilengkapi oleh admin.
                        </p>

                    </div>

                @endif

            </div>

        </section>


        {{-- LOKASI SEKOLAH --}}
        <section class="detail-panel mt-7 p-6 sm:p-8">

            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>

                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800">
                            Lokasi Sekolah
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Temukan lokasi {{ $paud->nama }} pada peta.
                        </p>
                    </div>

                </div>


                @if ($punyaLokasiValid)

                    <a
                        href="https://www.google.com/maps/dir/?api=1&destination={{ $paud->latitude }},{{ $paud->longitude }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-md shadow-blue-200 transition hover:-translate-y-0.5 hover:bg-blue-800"
                    >
                        <i class="fa-solid fa-diamond-turn-right"></i>
                        Petunjuk Arah
                    </a>

                @endif

            </div>


            @if ($punyaLokasiValid)

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">

                    <div id="peta-detail"></div>

                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">

                    <i class="fa-solid fa-circle-info text-blue-500"></i>

                    <span>
                        Lokasi sekolah berdasarkan koordinat yang tersimpan dalam database.
                    </span>

                </div>

            @else

                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-14 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">

                        <i class="fa-solid fa-map-location-dot text-3xl"></i>

                    </div>

                    <h3 class="mt-4 font-extrabold text-slate-700">
                        Lokasi peta belum tersedia
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Koordinat sekolah belum tersedia atau perlu diperiksa oleh admin.
                    </p>

                </div>

            @endif

        </section>


        {{-- FOOTER --}}
        <footer class="mt-10 border-t border-slate-200 py-7 text-center">

            <div class="flex items-center justify-center gap-2 text-blue-700">

                <i class="fa-solid fa-location-dot"></i>

                <span class="font-extrabold">
                    Peta PAUD Balikpapan
                </span>

            </div>

            <p class="mt-2 text-xs text-slate-500">
                Informasi lokasi dan profil Pendidikan Anak Usia Dini di Kota Balikpapan.
            </p>

            <p class="mt-2 text-xs text-slate-400">
                Pendidikan Anak Usia Dini untuk Masa Depan yang Lebih Baik.
            </p>

        </footer>

    </div>

</div>

@endsection


{{-- SCRIPT PETA --}}
@if ($punyaLokasiValid)

    @push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const latitude = Number(@json($paud->latitude));
            const longitude = Number(@json($paud->longitude));

            if (typeof maplibregl === 'undefined') {
                console.error('MapLibre belum dimuat. Periksa layouts/public.blade.php.');
                return;
            }

            const map = new maplibregl.Map({
                container: 'peta-detail',
                style: 'https://tiles.openfreemap.org/styles/liberty',
                center: [longitude, latitude],
                zoom: 15
            });

            map.addControl(
                new maplibregl.NavigationControl(),
                'top-right'
            );

            new maplibregl.Marker({
                color: '#2563eb'
            })
                .setLngLat([longitude, latitude])
                .setPopup(
                    new maplibregl.Popup({
                        offset: 25
                    }).setText(@json($paud->nama))
                )
                .addTo(map);

            map.on('load', function () {
                map.resize();
            });

            window.addEventListener('resize', function () {
                map.resize();
            });

        });
    </script>

    @endpush

@endif
