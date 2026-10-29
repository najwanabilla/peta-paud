
@extends('layouts.public')

@section('judul', $paud->nama . ' | Peta PAUD Balikpapan')

@push('styles')
<style>
    .detail-panel {
        box-shadow: 0 8px 30px rgba(15, 23, 42, .05);
    }

    #peta-detail {
        width: 100%;
        height: 380px;
        border-radius: 16px;
        overflow: hidden;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
</style>
@endpush

@section('isi')
<div class="min-h-screen bg-slate-50 px-5 py-10">
    <div class="mx-auto max-w-5xl">

        {{-- NAVIGASI --}}
        <a href="{{ route('paud.index', ['jenis' => $paud->jenis_satuan]) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-900">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke daftar sekolah
        </a>

        {{-- INFORMASI UTAMA --}}
        <section class="detail-panel mt-6 rounded-2xl border border-slate-200 bg-white p-6 md:p-8">

            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-blue-100 px-3 py-1.5 text-sm font-bold text-blue-700">
                    {{ $paud->jenis_satuan ?? 'PAUD' }}
                </span>

                @if ($paud->status)
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700">
                        {{ $paud->status }}
                    </span>
                @endif
            </div>

            <h1 class="mt-5 text-2xl font-extrabold leading-tight text-slate-800 md:text-4xl">
                {{ $paud->nama }}
            </h1>

            <p class="mt-4 flex items-start gap-3 leading-7 text-slate-600">
                <i class="fa-solid fa-location-dot mt-1 text-emerald-600"></i>
                <span>
                    {{ $paud->alamat ?: 'Alamat belum tersedia' }}
                    @if ($paud->kelurahan)
                        , Kelurahan {{ $paud->kelurahan }}
                    @endif
                    @if ($paud->kecamatan)
                        , Kecamatan {{ $paud->kecamatan->nama }}
                    @endif
                    @if ($paud->kode_pos)
                        , {{ $paud->kode_pos }}
                    @endif
                    , Balikpapan
                </span>
            </p>

            @if ($paud->deskripsi)
                <div class="mt-6 border-t border-slate-100 pt-5">
                    <h2 class="text-lg font-bold text-slate-800">
                        Tentang Sekolah
                    </h2>
                    <p class="mt-3 whitespace-pre-line leading-7 text-slate-600">{{ $paud->deskripsi }}</p>
                </div>
            @endif

            {{-- INFORMASI TAMBAHAN --}}
            <div class="mt-7 grid grid-cols-1 gap-5 border-t border-slate-100 pt-6 sm:grid-cols-2">

                @if ($paud->npsn)
                    <div class="info-row">
                        <i class="fa-solid fa-id-card mt-1 text-blue-600"></i>
                        <div>
                            <p class="text-sm text-slate-500">NPSN</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $paud->npsn }}</p>
                        </div>
                    </div>
                @endif

                @if ($paud->kepala_sekolah)
                    <div class="info-row">
                        <i class="fa-solid fa-user-tie mt-1 text-blue-600"></i>
                        <div>
                            <p class="text-sm text-slate-500">Kepala Sekolah</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $paud->kepala_sekolah }}</p>
                        </div>
                    </div>
                @endif

                @if ($paud->telepon)
                    <div class="info-row">
                        <i class="fa-solid fa-phone mt-1 text-blue-600"></i>
                        <div>
                            <p class="text-sm text-slate-500">Telepon</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $paud->telepon }}</p>
                        </div>
                    </div>
                @endif

                @if ($paud->kecamatan)
                    <div class="info-row">
                        <i class="fa-solid fa-map mt-1 text-blue-600"></i>
                        <div>
                            <p class="text-sm text-slate-500">Kecamatan</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $paud->kecamatan->nama }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </section>

        {{-- PETA LOKASI --}}
        <section class="detail-panel mt-6 rounded-2xl border border-slate-200 bg-white p-6 md:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">
                        Lokasi Sekolah
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Peta lokasi sekolah di Balikpapan.
                    </p>
                </div>

                @if ($punyaLokasiValid)
                    <a
                        href="https://www.google.com/maps/dir/?api=1&destination={{ $paud->latitude }},{{ $paud->longitude }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-800"
                    >
                        <i class="fa-solid fa-diamond-turn-right"></i>
                        Petunjuk Arah
                    </a>
                @endif
            </div>

            @if ($punyaLokasiValid)
                <div id="peta-detail" class="mt-5"></div>

                <p class="mt-3 text-xs text-slate-500">
                    Jika peta tidak muncul, periksa koneksi internet.
                </p>
            @else
                <div class="mt-5 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
                    <i class="fa-solid fa-map-location-dot text-3xl text-slate-400"></i>
                    <p class="mt-3 font-semibold text-slate-700">
                        Lokasi peta belum tersedia
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        Koordinat sekolah belum tersedia atau perlu diperiksa oleh admin.
                    </p>
                </div>
            @endif
        </section>

    </div>
</div>
@endsection

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

            map.addControl(new maplibregl.NavigationControl(), 'top-right');

            new maplibregl.Marker({ color: '#2563eb' })
                .setLngLat([longitude, latitude])
                .setPopup(
                    new maplibregl.Popup({ offset: 25 })
                        .setText(@json($paud->nama))
                )
                .addTo(map);

            map.on('load', function () {
                map.resize();
            });
        });
    </script>
    @endpush
@endif
