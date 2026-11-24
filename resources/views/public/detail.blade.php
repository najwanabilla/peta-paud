@extends('layouts.public')

@section('judul', $paud->nama . ' | Peta PAUD Balikpapan')

@push('styles')
<style>
    .detail-page {
        min-height: 100vh;
        background: #f8fafc;
    }

    .detail-container {
        max-width: 1080px;
        margin: 0 auto;
    }

    .detail-section {
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 14px;
    }

    .school-photo,
    .school-placeholder {
        width: 104px;
        height: 104px;
        flex: 0 0 104px;
        border-radius: 12px;
    }

    .school-photo {
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    .school-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        background: #f1f5f9;
        font-size: 34px;
    }

    .section-icon {
        display: flex;
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: #2563eb;
        background: #eff6ff;
        font-size: 14px;
    }

    .identity-row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .identity-row:last-child {
        border-bottom: 0;
    }

    #peta-detail {
        width: 100%;
        height: 360px;
    }

    .map-frame {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        background: #f1f5f9;
    }

    /* Desain Tombol Kembali (Hanya Teks Biru) */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1d4ed8; /* Warna teks biru */
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .btn-back:hover {
        color: #1e3a8a; /* Biru lebih gelap saat di-hover */
    }

    .btn-back i {
        font-size: 14px;
        transition: transform 0.2s ease;
    }

    .btn-back:hover i {
        transform: translateX(-4px); /* Ikon panah bergeser saat di-hover */
    }

    .direction-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid #dbeafe;
        border-radius: 9px;
        color: #1d4ed8;
        background: #eff6ff;
        font-size: 13px;
        font-weight: 600;
        transition: background .2s ease;
    }

    .direction-link:hover {
        background: #dbeafe;
    }

    @media (max-width: 640px) {
        .identity-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        #peta-detail {
            height: 290px;
        }

        .school-photo,
        .school-placeholder {
            width: 88px;
            height: 88px;
            flex-basis: 88px;
        }
    }
</style>
@endpush

@section('isi')
@php
    $warnaStatus = $paud->status === 'Negeri'
        ? 'bg-emerald-50 text-emerald-700'
        : 'bg-slate-100 text-slate-600';

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

<div class="detail-page px-4 py-6 sm:px-6 sm:py-8">
    <main class="detail-container">

        {{-- Navigasi (Hanya Teks Biru) --}}
        <div class="mb-6">
            <a href="{{ route('paud.index', ['jenis' => $paud->jenis_satuan]) }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar PAUD
            </a>
        </div>

        {{-- Profil sekolah --}}
        <section class="detail-section p-5 sm:p-7">
            <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start">
                @if ($paud->fotoUtama)
                    <img src="{{ $paud->fotoUtama->url }}"
                         alt="Foto {{ $paud->nama }}"
                         class="school-photo">
                @else
                    <div class="school-placeholder">
                        <i class="fa-solid fa-school"></i>
                    </div>
                @endif

                <div class="min-w-0 flex-1 text-center sm:text-left">
                    <div class="mb-2 flex flex-wrap items-center justify-center gap-2 sm:justify-start">
                        <span class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                            {{ $paud->jenis_satuan ?? 'PAUD' }}
                        </span>

                        @if ($paud->status)
                            <span class="rounded-md px-2.5 py-1 text-xs font-medium {{ $warnaStatus }}">
                                {{ $paud->status }}
                            </span>
                        @endif
                    </div>

                    <h1 class="break-words text-2xl font-semibold leading-snug text-slate-800 sm:text-3xl">
                        {{ $paud->nama }}
                    </h1>

                    <p class="mt-2 flex items-start justify-center gap-2 text-sm leading-6 text-slate-500 sm:justify-start">
                        <i class="fa-solid fa-location-dot mt-1 shrink-0 text-slate-400"></i>
                        <span>{{ $alamatLengkap }}</span>
                    </p>
                </div>
            </div>
        </section>

        {{-- Identitas sekolah --}}
        <section class="detail-section mt-5 p-5 sm:p-7">
            <div class="mb-3 flex items-center gap-3">
                <div class="section-icon"><i class="fa-regular fa-id-card"></i></div>
                <div>
                    <h2 class="font-semibold text-slate-800">Identitas Sekolah</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Informasi umum satuan pendidikan.</p>
                </div>
            </div>

            <div class="sm:pl-12">
                <div class="identity-row">
                    <p class="text-sm text-slate-500">NPSN</p>
                    <p class="break-words text-sm font-medium text-slate-700">{{ $paud->npsn ?: 'Belum tersedia' }}</p>
                </div>

                <div class="identity-row">
                    <p class="text-sm text-slate-500">Kepala Sekolah</p>
                    <p class="break-words text-sm font-medium text-slate-700">{{ $paud->kepala_sekolah ?: 'Belum tersedia' }}</p>
                </div>

                <div class="identity-row">
                    <p class="text-sm text-slate-500">Telepon</p>
                    <p class="break-words text-sm font-medium text-slate-700">{{ $paud->telepon ?: 'Belum tersedia' }}</p>
                </div>

                <div class="identity-row">
                    <p class="text-sm text-slate-500">Kecamatan</p>
                    <p class="break-words text-sm font-medium text-slate-700">{{ $paud->kecamatan?->nama ?? 'Belum tersedia' }}</p>
                </div>
            </div>
        </section>

        {{-- Tentang sekolah --}}
        <section class="detail-section mt-5 p-5 sm:p-7">
            <div class="flex items-center gap-3">
                <div class="section-icon"><i class="fa-regular fa-file-lines"></i></div>
                <div>
                    <h2 class="font-semibold text-slate-800">Tentang Sekolah</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Profil dan gambaran umum sekolah.</p>
                </div>
            </div>

            @if ($paud->deskripsi)
                <div class="mt-4 border-t border-slate-100 pt-4 sm:ml-12">
                    <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $paud->deskripsi }}</p>
                </div>
            @else
                <p class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-400 sm:ml-12">
                    Deskripsi sekolah belum tersedia.
                </p>
            @endif
        </section>

        {{-- Lokasi sekolah --}}
        <section class="detail-section mt-5 p-5 sm:p-7">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div class="flex items-center gap-3">
                    <div class="section-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <h2 class="font-semibold text-slate-800">Lokasi Sekolah</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Lokasi sekolah pada peta.</p>
                    </div>
                </div>

                @if ($punyaLokasiValid)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $paud->latitude }},{{ $paud->longitude }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="direction-link">
                        <i class="fa-solid fa-diamond-turn-right"></i>
                        Petunjuk arah
                    </a>
                @endif
            </div>

            @if ($punyaLokasiValid)
                <div class="map-frame mt-4">
                    <div id="peta-detail"></div>
                </div>
                <p class="mt-2 text-xs text-slate-400">
                    Lokasi berdasarkan koordinat yang tersimpan dalam database.
                </p>
            @else
                <div class="mt-4 rounded-lg border border-dashed border-slate-200 px-5 py-10 text-center">
                    <i class="fa-solid fa-map-location-dot text-2xl text-slate-300"></i>
                    <h3 class="mt-2 text-sm font-medium text-slate-700">Lokasi peta belum tersedia</h3>
                    <p class="mt-1 text-xs text-slate-500">
                        Koordinat sekolah belum tersedia atau masih perlu diperiksa oleh admin.
                    </p>
                </div>
            @endif
        </section>

        {{-- Footer --}}
        <footer class="mt-7 border-t border-slate-200 py-5 text-center">
            <p class="text-xs text-slate-500">Peta PAUD Balikpapan</p>
        </footer>
    </main>
</div>

@endsection

{{-- Script peta --}}
@if ($punyaLokasiValid)

    @push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const latitude = Number(@json($paud->latitude));
            const longitude = Number(@json($paud->longitude));
            const container = document.getElementById('peta-detail');

            if (!container) return;

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
                .setPopup(new maplibregl.Popup({ offset: 25 }).setText(@json($paud->nama)))
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