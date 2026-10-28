@extends('layouts.admin')

@section('judul', $kecamatan->exists ? 'Ubah Kecamatan' : 'Tambah Kecamatan')

@section('isi')
@php
    $sedangUbah = $kecamatan->exists;

    $gaya = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10';

    $urlKembali = route('admin.kecamatan.index');
@endphp

<style>
    .kecamatan-page {
        color: #334155;
    }

    .kecamatan-banner {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        border-radius: 24px;
        padding: 30px;
        color: #fff;
        background: linear-gradient(120deg, #0284b8 0%, #0ea5e9 55%, #38bdf8 100%);
        box-shadow: 0 12px 28px rgba(14, 165, 233, .15);
    }

    .kecamatan-banner::before,
    .kecamatan-banner::after {
        content: "";
        position: absolute;
        z-index: -1;
        border-radius: 9999px;
        background: rgba(255,255,255,.12);
        pointer-events: none;
    }

    .kecamatan-banner::before {
        width: 230px;
        height: 230px;
        right: -65px;
        top: -100px;
    }

    .kecamatan-banner::after {
        width: 150px;
        height: 150px;
        right: 160px;
        bottom: -100px;
    }

    .kecamatan-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .kecamatan-card-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .kecamatan-icon {
        display: flex;
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eff6ff;
        color: #0284c7;
        font-size: 18px;
    }

    .kecamatan-card-body {
        padding: 24px;
    }

    .kecamatan-label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .kecamatan-help {
        margin-top: 7px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.7;
    }

    .kecamatan-required {
        color: #ef4444;
    }

    .kecamatan-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 9999px;
        padding: 7px 11px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.2);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .kecamatan-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
        cursor: pointer;
    }

    .kecamatan-action:disabled {
        cursor: wait;
        opacity: .65;
    }

    .kecamatan-action-primary {
        border: 1px solid #0284c7;
        background: #0284c7;
        color: #fff;
    }

    .kecamatan-action-primary:hover {
        border-color: #0369a1;
        background: #0369a1;
    }

    .kecamatan-action-light {
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #334155;
    }

    .kecamatan-action-light:hover {
        background: #f8fafc;
    }

    .kecamatan-alert {
        display: flex;
        gap: 12px;
        padding: 16px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.6;
    }

    .kecamatan-footer {
        position: sticky;
        bottom: 12px;
        z-index: 20;
        padding: 16px 20px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: rgba(255,255,255,.96);
        box-shadow: 0 8px 28px rgba(15,23,42,.10);
        backdrop-filter: blur(12px);
    }

    .kecamatan-input-error {
        border-color: #f87171 !important;
    }

    .kecamatan-color {
        width: 64px;
        height: 48px;
        cursor: pointer;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        padding: 4px;
    }

    @media (max-width: 640px) {
        .kecamatan-banner {
            padding: 22px;
        }

        .kecamatan-card-head {
            padding: 17px;
        }

        .kecamatan-card-body {
            padding: 17px;
        }

        .kecamatan-footer {
            padding: 13px;
        }
    }
</style>

<div class="kecamatan-page mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
    <section class="kecamatan-banner">
        <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <p class="mb-3 flex items-center gap-2 text-sm font-semibold text-blue-50">
                    <i class="fa-solid fa-map-location-dot"></i>
                    PANEL ADMINISTRATOR
                </p>

                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                    {{ $sedangUbah ? 'Ubah Data Kecamatan' : 'Tambah Kecamatan Baru' }}
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-50">
                    {{ $sedangUbah
                        ? 'Perbarui informasi dan pengaturan wilayah kecamatan di Peta PAUD Balikpapan.'
                        : 'Tambahkan wilayah kecamatan untuk mengelompokkan data PAUD di Kota Balikpapan.' }}
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="kecamatan-chip">
                        <i class="fa-solid fa-map"></i>
                        Data wilayah
                    </span>

                    <span class="kecamatan-chip">
                        <i class="fa-solid fa-palette"></i>
                        Warna peta
                    </span>

                    <span class="kecamatan-chip">
                        <i class="fa-solid fa-draw-polygon"></i>
                        Batas wilayah
                    </span>
                </div>
            </div>

            <a href="{{ $urlKembali }}"
               class="kecamatan-action shrink-0 self-start border border-white/30 bg-white/10 text-white hover:bg-white/20 sm:self-center">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </section>

    {{-- PETUNJUK --}}
    <div class="kecamatan-alert border border-blue-100 bg-blue-50 text-blue-900">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
            <i class="fa-solid fa-circle-info text-lg"></i>
        </div>

        <div>
            <p class="font-bold">Petunjuk pengisian</p>
            <p class="mt-1">
                Isi nama kecamatan dengan benar. Pilih warna yang mudah dibedakan
                pada peta. Data batas wilayah GeoJSON boleh dikosongkan jika belum tersedia.
            </p>
        </div>
    </div>

    {{-- VALIDASI --}}
    @if ($errors->any())
        <div class="kecamatan-alert border border-red-200 bg-red-50 text-red-800" role="alert">
            <i class="fa-solid fa-triangle-exclamation mt-1 text-lg"></i>

            <div>
                <p class="font-bold">Data belum bisa disimpan</p>
                <p class="mt-1">Periksa kembali bagian berikut:</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORM UTAMA --}}
    <form
        id="form-kecamatan"
        method="POST"
        action="{{ $sedangUbah ? route('admin.kecamatan.update', $kecamatan) : route('admin.kecamatan.store') }}"
        data-loading
        class="space-y-6"
    >
        @csrf

        @if ($sedangUbah)
            @method('PUT')
        @endif

        {{-- IDENTITAS KECAMATAN --}}
        <section class="kecamatan-card">
            <div class="kecamatan-card-head">
                <div class="kecamatan-icon">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">Identitas Kecamatan</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Informasi dasar wilayah administrasi.
                    </p>
                </div>

                <span class="ml-auto rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600">
                    * Wajib
                </span>
            </div>

            <div class="kecamatan-card-body space-y-5">
                <div>
                    <label for="nama" class="kecamatan-label">
                        Nama Kecamatan <span class="kecamatan-required">*</span>
                    </label>

                    <input
                        id="nama"
                        name="nama"
                        type="text"
                        value="{{ old('nama', $kecamatan->nama) }}"
                        required
                        maxlength="100"
                        placeholder="Contoh: Balikpapan Selatan"
                        class="{{ $gaya }} @error('nama') kecamatan-input-error @enderror"
                    >

                    @error('nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- PENGATURAN WARNA --}}
        <section class="kecamatan-card">
            <div class="kecamatan-card-head">
                <div class="kecamatan-icon">
                    <i class="fa-solid fa-palette"></i>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">Warna Wilayah</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Warna untuk membedakan kecamatan pada peta.
                    </p>
                </div>
            </div>

            <div class="kecamatan-card-body space-y-4">
                @if ($sedangUbah)
                    {{-- Warna tidak dapat diubah setelah kecamatan dibuat --}}
                    <p class="kecamatan-label">Warna di Peta</p>

                    <div class="flex flex-wrap items-center gap-4">
                        <span
                            class="h-12 w-12 rounded-xl border border-slate-200 shadow-sm"
                            style="background-color: {{ $kecamatan->warna }}"
                        ></span>

                        <div>
                            <p class="font-bold text-slate-800">
                                {{ $kecamatan->warna }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                Warna yang digunakan kecamatan ini.
                            </p>
                        </div>
                    </div>

                    <div class="kecamatan-alert border border-amber-200 bg-amber-50 text-amber-900">
                        <i class="fa-solid fa-lock mt-1"></i>
                        <p>
                            Warna ditetapkan saat kecamatan dibuat dan tidak bisa diubah.
                        </p>
                    </div>

                    @error('warna')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @else
                    <div>
                        <label for="warna" class="kecamatan-label">
                            Pilih Warna <span class="kecamatan-required">*</span>
                        </label>

                        <div class="flex flex-wrap items-center gap-4">
                            <input
                                id="warna"
                                name="warna"
                                type="color"
                                value="{{ old('warna', $kecamatan->warna ?: '#0284c7') }}"
                                required
                                class="kecamatan-color"
                            >

                            <div>
                                <p class="text-sm font-bold text-slate-800">
                                    Warna kecamatan
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Pilih warna yang jelas dan berbeda dari wilayah lainnya.
                                </p>

                                <p id="kode-warna" class="mt-1 font-mono text-sm text-sky-700">
                                    {{ old('warna', $kecamatan->warna ?: '#0284c7') }}
                                </p>
                            </div>
                        </div>

                        <p class="kecamatan-help">
                            Setelah disimpan, warna tidak bisa diubah lagi.
                        </p>

                        @error('warna')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        </section>

        {{-- STATUS KECAMATAN --}}
        <section class="kecamatan-card">
            <div class="kecamatan-card-head">
                <div class="kecamatan-icon">
                    <i class="fa-solid fa-toggle-on"></i>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">Status Kecamatan</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Atur apakah wilayah ditampilkan pada peta publik.
                    </p>
                </div>
            </div>

            <div class="kecamatan-card-body">
                <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50/50">
                    <input
                        id="is_active"
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="mt-1 h-5 w-5 rounded border-slate-300 text-sky-600 focus:ring-sky-500"
                        {{ old('is_active', $kecamatan->is_active) ? 'checked' : '' }}
                    >

                    <span>
                        <span class="block font-bold text-slate-800">
                            Kecamatan Aktif
                        </span>

                        <span class="mt-1 block text-sm leading-relaxed text-slate-500">
                            Jika diaktifkan, kecamatan dapat ditampilkan pada peta publik.
                            Nonaktifkan jika wilayah tidak ingin ditampilkan.
                        </span>
                    </span>
                </label>
            </div>
        </section>

        {{-- BATAS WILAYAH --}}
        <section class="kecamatan-card">
            <div class="kecamatan-card-head">
                <div class="kecamatan-icon">
                    <i class="fa-solid fa-draw-polygon"></i>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">Batas Wilayah</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Data geografis kecamatan dalam format GeoJSON.
                    </p>
                </div>
            </div>

            <div class="kecamatan-card-body space-y-4">
                <div>
                    <label for="geojson" class="kecamatan-label">
                        Data GeoJSON
                    </label>

                    <textarea
                        id="geojson"
                        name="geojson"
                        rows="10"
                        spellcheck="false"
                        placeholder='Contoh: {"type":"FeatureCollection","features":[]}'
                        class="{{ $gaya }} resize-y font-mono text-xs leading-6 @error('geojson') kecamatan-input-error @enderror"
                    >{{ old('geojson', $kecamatan->geojson) }}</textarea>

                    <p class="kecamatan-help">
                        Boleh dikosongkan dulu. Isi setelah data batas wilayah didapat
                        dari sumber yang legal. Gunakan sistem koordinat WGS84 (EPSG:4326)
                        dengan urutan koordinat [longitude, latitude].
                    </p>

                    @error('geojson')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- TOMBOL SIMPAN --}}
        <div class="kecamatan-footer">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs leading-relaxed text-slate-500">
                    <i class="fa-solid fa-shield-halved mr-1 text-sky-600"></i>
                    Periksa kembali data sebelum menyimpan.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ $urlKembali }}"
                        class="kecamatan-action kecamatan-action-light flex-1 sm:flex-none"
                    >
                        <i class="fa-solid fa-xmark"></i>
                        Batal
                    </a>

                    <button
                        id="tombol-simpan"
                        type="submit"
                        class="kecamatan-action kecamatan-action-primary flex-1 sm:flex-none"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>{{ $sedangUbah ? 'Simpan Perubahan' : 'Simpan Kecamatan' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-kecamatan');
    const tombolSimpan = document.getElementById('tombol-simpan');
    const inputWarna = document.getElementById('warna');
    const kodeWarna = document.getElementById('kode-warna');

    // Perbarui kode warna ketika pengguna memilih warna baru.
    if (inputWarna && kodeWarna) {
        inputWarna.addEventListener('input', function () {
            kodeWarna.textContent = inputWarna.value;
        });
    }

    // Validasi form dan cegah pengiriman berkali-kali.
    if (form && tombolSimpan) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            form.dataset.submitting = 'true';
            tombolSimpan.disabled = true;

            tombolSimpan.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i><span>Menyimpan...</span>';
        });
    }
});
</script>
@endsection
