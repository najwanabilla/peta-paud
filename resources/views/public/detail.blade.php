@extends('layouts.public')

@section('judul', $paud->nama . ' | Peta PAUD Balikpapan')

@push('styles')
<style>
    .detail-panel {
        box-shadow: 0 10px 40px -10px rgba(15, 23, 42, .06);
        border: 1px solid #f1f5f9;
    }

    #peta-detail {
        width: 100%;
        height: 400px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 0 0 1px #e2e8f0;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        transition: background 0.2s;
    }
    
    .info-row:hover {
        background: #f1f5f9;
    }
</style>
@endpush

@section('isi')
<div class="min-h-screen bg-slate-50 px-5 py-12">
    <div class="mx-auto max-w-5xl">

        <a href="{{ route('paud.index', ['jenis' => $paud->jenis_satuan]) }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:text-blue-900 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke daftar sekolah
        </a>

        {{-- INFORMASI UTAMA --}}
        <section class="detail-panel mt-6 rounded-3xl bg-white p-6 md:p-10">
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-blue-100 px-4 py-1.5 text-sm font-extrabold tracking-wide text-blue-700">
                    {{ $paud->jenis_satuan ?? 'PAUD' }}
                </span>
                @if ($paud->status)
                    <span class="rounded-full bg-emerald-50 px-4 py-1.5 text-sm font-bold text-emerald-700">
                        {{ $paud->status }}
                    </span>
                @endif
            </div>

            <h1 class="mt-6 text-3xl font-extrabold leading-tight text-slate-800 md:text-4xl">
                {{ $paud->nama }}
            </h1>

            <p class="mt-5 flex items-start gap-3 text-base leading-relaxed text-slate-600">
                <i class="fa-solid fa-location-dot mt-1.5 text-emerald-500 text-lg"></i>
                <span>
                    {{ $paud->alamat ?: 'Alamat belum tersedia' }}
                    @if ($paud->kelurahan), Kelurahan {{ $paud->kelurahan }} @endif
                    @if ($paud->kecamatan), Kecamatan {{ $paud->kecamatan->nama }} @endif
                    @if ($paud->kode_pos), {{ $paud->kode_pos }} @endif
                </span>
            </p>

            @if ($paud->deskripsi)
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <h2 class="text-xl font-bold text-slate-800">Tentang Sekolah</h2>
                    <p class="mt-4 whitespace-pre-line text-base leading-relaxed text-slate-600">{{ $paud->deskripsi }}</p>
                </div>
            @endif

            {{-- INFORMASI TAMBAHAN GRID --}}
            <div class="mt-8 grid grid-cols-1 gap-4 border-t border-slate-100 pt-8 sm:grid-cols-2">
                @if ($paud->npsn)
                    <div class="info-row">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wide text-slate-500 uppercase">NPSN</p>
                            <p class="mt-1 text-lg font-extrabold text-slate-800">{{ $paud->npsn }}</p>
                        </div>
                    </div>
                @endif

                @if ($paud->kepala_sekolah)
                    <div class="info-row">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wide text-slate-500 uppercase">Kepala Sekolah</p>
                            <p class="mt-1 text-base font-bold text-slate-800">{{ $paud->kepala_sekolah }}</p>
                        </div>
                    </div>
                @endif

                @if ($paud->telepon)
                    <div class="info-row">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wide text-slate-500 uppercase">Telepon</p>
                            <p class="mt-1 text-base font-bold text-slate-800">{{ $paud->telepon }}</p>
                        </div>
                    </div>
                @endif

                @if ($paud->kecamatan)
                    <div class="info-row">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <i class="fa-solid fa-map"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wide text-slate-500 uppercase">Kecamatan</p>
                            <p class="mt-1 text-base font-bold text-slate-800">{{ $paud->kecamatan->nama }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- PETA LOKASI --}}
        <section class="detail-panel mt-6 rounded-3xl bg-white p-6 md:p-10">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Lokasi Sekolah</h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">Peta lokasi sekolah di Balikpapan.</p>
                </div>

                @if ($punyaLokasiValid)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $paud->latitude }},{{ $paud->longitude }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-md transition-all hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-lg">
                        <i class="fa-solid fa-diamond-turn-right"></i> Petunjuk Arah
                    </a>
                @endif
            </div>

            @if ($punyaLokasiValid)
                <div id="peta-detail" class="mt-6"></div>
            @else
                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-16 text-center">
                    <i class="fa-solid fa-map-location-dot text-4xl text-slate-300"></i>
                    <p class="mt-4 text-lg font-bold text-slate-700">Lokasi peta belum tersedia</p>
                    <p class="mt-2 text-sm text-slate-500">Koordinat sekolah belum tersedia atau perlu diperiksa oleh admin.</p>
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
                zoom: 16 // Sedikit di-zoom in agar lebih dekat
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