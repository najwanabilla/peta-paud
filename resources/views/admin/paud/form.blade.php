@extends('layouts.admin')

@section('judul', $paud->exists ? 'Ubah PAUD' : 'Tambah PAUD')

@section('isi')
@php
    $sedangUbah = $paud->exists;
    $urlKembali = $sedangUbah
        ? route('admin.paud.show', $paud)
        : route('admin.paud.index');

    $gaya = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 transition focus:border-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-500/10';
@endphp

<style>
    .paud-page { color: #334155; }

    /* Banner biru seperti dashboard admin */
    .paud-banner {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        border-radius: 24px;
        padding: 30px;
        color: #fff;
        background: linear-gradient(110deg, #0277B5 0%, #129FE0 55%, #29B6F6 100%);
        box-shadow: 0 12px 28px rgba(2, 119, 181, .14);
    }

    .paud-banner::before,
    .paud-banner::after {
        content: "";
        position: absolute;
        z-index: -1;
        border-radius: 9999px;
        background: rgba(255,255,255,.10);
        pointer-events: none;
    }

    .paud-banner::before {
        width: 230px;
        height: 230px;
        right: -65px;
        top: -100px;
    }

    .paud-banner::after {
        width: 150px;
        height: 150px;
        right: 160px;
        bottom: -100px;
    }

    .paud-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .paud-card-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .paud-icon {
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

    .paud-card-body { padding: 24px; }

    .paud-label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .paud-help {
        margin-top: 7px;
        color: #64748b;
        font-size: 12px;
    }

    .paud-required { color: #ef4444; }

    .paud-action {
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

    .paud-action:disabled {
        cursor: wait;
        opacity: .65;
    }

    .paud-action-light {
        color: #334155;
        background: #fff;
        border: 1px solid #e2e8f0;
    }

    .paud-action-light:hover { background: #f8fafc; }

    .paud-action-primary {
        color: #fff;
        background: #0284c7;
        border: 1px solid #0284c7;
    }

    .paud-action-primary:hover {
        background: #0369a1;
        border-color: #0369a1;
    }

    .paud-action-danger {
        color: #dc2626;
        background: #fef2f2;
    }

    .paud-action-danger:hover { background: #fee2e2; }

    .paud-chip {
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

    .paud-map {
        position: relative;
        z-index: 0;
        width: 100%;
        height: 360px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #f1f5f9;
    }

    .paud-alert {
        display: flex;
        gap: 12px;
        padding: 16px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.6;
    }

    .paud-footer {
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

    .paud-input-error {
        border-color: #f87171 !important;
    }

    @media (max-width: 640px) {
        .paud-banner { padding: 22px; }
        .paud-card-head { padding: 17px; }
        .paud-card-body { padding: 17px; }
        .paud-map { height: 300px; }
        .paud-footer { padding: 13px; }
    }
</style>

<div class="paud-page mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
    <section class="paud-banner">
        <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <p class="mb-3 flex items-center gap-2 text-sm font-semibold text-blue-50">
                    <i class="fa-solid fa-school"></i>
                    PANEL ADMINISTRATOR
                </p>

                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                    {{ $sedangUbah ? 'Ubah Data PAUD' : 'Tambah PAUD Baru' }}
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-50">
                    {{ $sedangUbah
                        ? 'Perbarui informasi, lokasi, kontak, dan dokumentasi sekolah PAUD.'
                        : 'Lengkapi informasi sekolah untuk menambahkan data PAUD ke Peta PAUD Balikpapan.' }}
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="paud-chip">
                        <i class="fa-solid fa-school"></i> Data sekolah
                    </span>
                    <span class="paud-chip">
                        <i class="fa-solid fa-map-location-dot"></i> Lokasi peta
                    </span>
                    <span class="paud-chip">
                        <i class="fa-solid fa-images"></i> Galeri foto
                    </span>
                </div>
            </div>

            <a href="{{ $urlKembali }}"
               class="paud-action shrink-0 self-start border border-white/30 bg-white/10 text-white hover:bg-white/20 sm:self-center">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </section>

    {{-- PETUNJUK --}}
    <div class="paud-alert border border-blue-100 bg-blue-50 text-blue-900">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
            <i class="fa-solid fa-circle-info text-lg"></i>
        </div>
        <div>
            <p class="font-bold">Petunjuk pengisian</p>
            <p class="mt-1">
                Isi data dengan benar. Kolom bertanda
                <span class="font-bold text-red-500">*</span> wajib diisi.
                Pastikan kecamatan dan koordinat sesuai lokasi sekolah.
            </p>
        </div>
    </div>

    {{-- VALIDASI --}}
    @if ($errors->any())
        <div class="paud-alert border border-red-200 bg-red-50 text-red-800" role="alert">
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
    <form id="form-paud"
          method="POST"
          action="{{ $sedangUbah ? route('admin.paud.update', $paud) : route('admin.paud.store') }}"
          enctype="multipart/form-data"
          class="space-y-6">

        @csrf

        @if ($sedangUbah)
            @method('PUT')
        @endif

        {{-- IDENTITAS PAUD --}}
        <section class="paud-card">
            <div class="paud-card-head">
                <div class="paud-icon"><i class="fa-solid fa-school"></i></div>
                <div>
                    <h2 class="font-bold text-slate-800">Identitas PAUD</h2>
                    <p class="mt-1 text-xs text-slate-500">Informasi dasar dan identitas sekolah.</p>
                </div>
                <span class="ml-auto rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-600">
                    * Wajib
                </span>
            </div>

            <div class="paud-card-body space-y-5">
                <div>
                    <label for="nama" class="paud-label">Nama PAUD <span class="paud-required">*</span></label>
                    <input id="nama" name="nama" type="text" required maxlength="255"
                           value="{{ old('nama', $paud->nama) }}"
                           placeholder="Contoh: TK Negeri Pembina Balikpapan"
                           class="{{ $gaya }} @error('nama') paud-input-error @enderror">
                    @error('nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="npsn" class="paud-label">NPSN</label>
                        <input id="npsn" name="npsn" type="text" inputmode="numeric" maxlength="8"
                               value="{{ old('npsn', $paud->npsn) }}"
                               placeholder="8 angka"
                               class="{{ $gaya }} @error('npsn') paud-input-error @enderror">
                        <p class="paud-help">Kosongkan jika NPSN belum tersedia.</p>
                        @error('npsn')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_satuan" class="paud-label">Jenis Satuan</label>
                        <select id="jenis_satuan" name="jenis_satuan"
                                class="{{ $gaya }} @error('jenis_satuan') paud-input-error @enderror">
                            <option value="">Pilih jenis satuan</option>
                            @foreach ($jenisSatuan as $kode => $label)
                                <option value="{{ $kode }}" @selected(old('jenis_satuan', $paud->jenis_satuan) === $kode)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_satuan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="paud-label">Status Sekolah</label>
                        <select id="status" name="status"
                                class="{{ $gaya }} @error('status') paud-input-error @enderror">
                            <option value="">Pilih status</option>
                            @foreach ($statusList as $s)
                                <option value="{{ $s }}" @selected(old('status', $paud->status) === $s)>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- ALAMAT SEKOLAH --}}
        <section class="paud-card">
            <div class="paud-card-head">
                <div class="paud-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                <div>
                    <h2 class="font-bold text-slate-800">Alamat Sekolah</h2>
                    <p class="mt-1 text-xs text-slate-500">Wilayah administrasi dan alamat lengkap PAUD.</p>
                </div>
            </div>

            <div class="paud-card-body space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="kecamatan_id" class="paud-label">
                            Kecamatan <span class="paud-required">*</span>
                        </label>
                        <select id="kecamatan_id" name="kecamatan_id" required
                                class="{{ $gaya }} @error('kecamatan_id') paud-input-error @enderror">
                            <option value="">Pilih kecamatan</option>
                            @foreach ($kecamatans as $k)
                                <option value="{{ $k->id }}" @selected(old('kecamatan_id', $paud->kecamatan_id) == $k->id)>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kecamatan_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelurahan" class="paud-label">Kelurahan</label>
                        <input id="kelurahan" name="kelurahan" type="text" maxlength="100"
                               value="{{ old('kelurahan', $paud->kelurahan) }}"
                               placeholder="Nama kelurahan"
                               class="{{ $gaya }} @error('kelurahan') paud-input-error @enderror">
                        @error('kelurahan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="alamat" class="paud-label">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" maxlength="1000"
                              placeholder="Nama jalan, nomor bangunan, RT/RW, atau petunjuk lokasi..."
                              class="{{ $gaya }} resize-y @error('alamat') paud-input-error @enderror">{{ old('alamat', $paud->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:max-w-xs">
                    <label for="kode_pos" class="paud-label">Kode Pos</label>
                    <input id="kode_pos" name="kode_pos" type="text" inputmode="numeric" maxlength="5"
                           value="{{ old('kode_pos', $paud->kode_pos) }}"
                           placeholder="Contoh: 76114"
                           class="{{ $gaya }} @error('kode_pos') paud-input-error @enderror">
                    @error('kode_pos')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- LOKASI PETA --}}
        <section class="paud-card">
            <div class="paud-card-head">
                <div class="paud-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
                <div>
                    <h2 class="font-bold text-slate-800">Lokasi pada Peta</h2>
                    <p class="mt-1 text-xs text-slate-500">Tentukan posisi sekolah berdasarkan koordinat.</p>
                </div>
            </div>

            <div class="paud-card-body space-y-5">
                <div class="paud-alert border border-blue-100 bg-blue-50 text-blue-900">
                    <i class="fa-solid fa-circle-info mt-1"></i>
                    <p>
                        Klik peta atau isi latitude dan longitude secara langsung.
                        Kosongkan jika lokasi belum diketahui.
                    </p>
                </div>

                <div id="peta-pemilih" class="paud-map"></div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="latitude" class="paud-label">Latitude</label>
                        <input id="latitude" name="latitude" type="text" inputmode="decimal"
                               value="{{ old('latitude', $paud->latitude) }}"
                               placeholder="Contoh: -1.2654000"
                               class="{{ $gaya }} font-mono @error('latitude') paud-input-error @enderror">
                        @error('latitude')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="paud-label">Longitude</label>
                        <input id="longitude" name="longitude" type="text" inputmode="decimal"
                               value="{{ old('longitude', $paud->longitude) }}"
                               placeholder="Contoh: 116.8312000"
                               class="{{ $gaya }} font-mono @error('longitude') paud-input-error @enderror">
                        @error('longitude')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <button type="button" id="hapus-koordinat"
                                class="paud-action w-full border border-slate-200 bg-slate-50 text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                            <i class="fa-solid fa-eraser"></i> Hapus Koordinat
                        </button>
                    </div>
                </div>
            </div>
        </section>

        {{-- KONTAK DAN DESKRIPSI --}}
        <section class="paud-card">
            <div class="paud-card-head">
                <div class="paud-icon"><i class="fa-solid fa-address-card"></i></div>
                <div>
                    <h2 class="font-bold text-slate-800">Kontak dan Deskripsi</h2>
                    <p class="mt-1 text-xs text-slate-500">Informasi pengelola dan profil singkat sekolah.</p>
                </div>
            </div>

            <div class="paud-card-body space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="kepala_sekolah" class="paud-label">Kepala Sekolah</label>
                        <input id="kepala_sekolah" name="kepala_sekolah" type="text" maxlength="150"
                               value="{{ old('kepala_sekolah', $paud->kepala_sekolah) }}"
                               placeholder="Nama kepala sekolah"
                               class="{{ $gaya }} @error('kepala_sekolah') paud-input-error @enderror">
                        @error('kepala_sekolah')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telepon" class="paud-label">Nomor Telepon</label>
                        <input id="telepon" name="telepon" type="text" inputmode="tel" maxlength="30"
                               value="{{ old('telepon', $paud->telepon) }}"
                               placeholder="Contoh: 081234567890"
                               class="{{ $gaya }} @error('telepon') paud-input-error @enderror">
                        @error('telepon')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="paud-label">Deskripsi PAUD</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="5000"
                              placeholder="Profil sekolah, fasilitas, program belajar, atau informasi lainnya..."
                              class="{{ $gaya }} resize-y @error('deskripsi') paud-input-error @enderror">{{ old('deskripsi', $paud->deskripsi) }}</textarea>
                    <p class="paud-help">Maksimal 5.000 karakter.</p>
                    @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- GALERI FOTO --}}
        <section class="paud-card">
            <div class="paud-card-head">
                <div class="paud-icon"><i class="fa-solid fa-images"></i></div>
                <div>
                    <h2 class="font-bold text-slate-800">Galeri Foto PAUD</h2>
                    <p class="mt-1 text-xs text-slate-500">Dokumentasi bangunan dan lingkungan sekolah.</p>
                </div>
            </div>

            <div class="paud-card-body space-y-6">
                @if ($sedangUbah && $paud->fotos->isNotEmpty())
                    <div>
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-sm font-bold text-slate-700">Foto yang sudah tersimpan</h3>
                            <span class="rounded-full bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700">
                                {{ $paud->fotos->count() }} foto
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach ($paud->fotos as $foto)
                                <figure class="overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="relative overflow-hidden bg-slate-100">
                                        <img src="{{ $foto->url }}"
                                             alt="Foto {{ $paud->nama }}"
                                             loading="lazy"
                                             class="aspect-square w-full object-cover">

                                        @if ($foto->is_primary)
                                            <span class="absolute left-2 top-2 rounded-full bg-sky-600 px-2.5 py-1.5 text-xs font-bold text-white shadow">
                                                <i class="fa-solid fa-star"></i> Foto Utama
                                            </span>
                                        @endif
                                    </div>

                                    <figcaption class="space-y-2 p-3">
                                        @unless ($foto->is_primary)
                                            <button type="submit"
                                                    form="utama-foto-{{ $foto->id }}"
                                                    class="paud-action w-full bg-blue-50 px-2 text-xs text-blue-800 hover:bg-blue-100">
                                                <i class="fa-solid fa-star"></i> Jadikan Utama
                                            </button>
                                        @endunless

                                        <button type="submit"
                                                form="hapus-foto-{{ $foto->id }}"
                                                class="paud-action w-full bg-red-50 px-2 text-xs text-red-700 hover:bg-red-100">
                                            <i class="fa-solid fa-trash"></i> Hapus Foto
                                        </button>
                                    </figcaption>
                                </figure>
                            @endforeach
                        </div>

                        <div class="paud-alert mt-4 border border-amber-200 bg-amber-50 text-amber-900">
                            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                            <p>
                                Aksi foto diproses langsung. Simpan perubahan data lainnya
                                sebelum melakukan tindakan foto.
                            </p>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="fotos" class="paud-label">
                        {{ $sedangUbah ? 'Tambah Foto Baru' : 'Unggah Foto Sekolah' }}
                    </label>

                    <div class="rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50/50 p-5 sm:p-6">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-700">Pilih foto dari perangkat</p>
                                <p class="mt-1 text-xs text-slate-500">Bisa memilih beberapa foto sekaligus.</p>
                            </div>
                        </div>

                        <input id="fotos" name="fotos[]" type="file" multiple
                               accept="image/jpeg,image/png,image/webp"
                               class="block w-full cursor-pointer rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-blue-700 hover:file:bg-blue-100">

                        <p class="paud-help">
                            Format JPG, PNG, atau WEBP. Maksimal 2 MB per foto
                            dan {{ $maksFoto }} foto per PAUD.
                            Foto baru disimpan saat tombol simpan ditekan.
                        </p>
                    </div>

                    @error('fotos')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @foreach ($errors->get('fotos.*') as $pesanFoto)
                        @foreach ($pesanFoto as $pesan)
                            <p class="mt-2 text-sm text-red-600">{{ $pesan }}</p>
                        @endforeach
                    @endforeach

                    <div id="pratinjau-foto" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4"></div>
                </div>
            </div>
        </section>

        {{-- TOMBOL SIMPAN --}}
        <div class="paud-footer">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs leading-relaxed text-slate-500">
                    <i class="fa-solid fa-shield-halved mr-1 text-sky-600"></i>
                    Periksa kembali data sebelum menyimpan.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ $urlKembali }}"
                       class="paud-action paud-action-light flex-1 sm:flex-none">
                        Batal
                    </a>

                    <button id="tombol-simpan" type="submit"
                            class="paud-action paud-action-primary flex-1 sm:flex-none">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>{{ $sedangUbah ? 'Simpan Perubahan' : 'Simpan Data PAUD' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- FORM AKSI FOTO --}}
    @if ($sedangUbah)
        @foreach ($paud->fotos as $foto)
            <form id="utama-foto-{{ $foto->id }}"
                  method="POST"
                  action="{{ route('admin.paud.foto.utama', [$paud, $foto]) }}"
                  class="hidden">
                @csrf
                @method('PATCH')
            </form>

            <form id="hapus-foto-{{ $foto->id }}"
                  method="POST"
                  action="{{ route('admin.paud.foto.destroy', [$paud, $foto]) }}"
                  onsubmit="return confirm('Hapus foto ini? Tindakan ini tidak bisa dibatalkan.')"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-paud');
    const tombolSimpan = document.getElementById('tombol-simpan');
    const inputFoto = document.getElementById('fotos');
    const pratinjau = document.getElementById('pratinjau-foto');
    const latitude = document.getElementById('latitude');
    const longitude = document.getElementById('longitude');
    const hapusKoordinat = document.getElementById('hapus-koordinat');

    // Pratinjau foto sebelum diunggah.
    if (inputFoto && pratinjau) {
        inputFoto.addEventListener('change', function () {
            pratinjau.replaceChildren();

            Array.from(inputFoto.files || []).forEach(function (file) {
                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    return;
                }

                const kartu = document.createElement('div');
                kartu.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm';

                const gambar = document.createElement('img');
                gambar.alt = file.name;
                gambar.className = 'aspect-square w-full object-cover';

                const nama = document.createElement('p');
                nama.className = 'truncate p-2 text-xs text-slate-600';
                nama.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function (event) {
                    gambar.src = event.target.result;
                };
                reader.readAsDataURL(file);

                kartu.append(gambar, nama);
                pratinjau.appendChild(kartu);
            });
        });
    }

    // Mengosongkan koordinat dan memberi tahu skrip peta.
    if (hapusKoordinat && latitude && longitude) {
        hapusKoordinat.addEventListener('click', function () {
            latitude.value = '';
            longitude.value = '';

            latitude.dispatchEvent(new Event('input', { bubbles: true }));
            longitude.dispatchEvent(new Event('input', { bubbles: true }));
            latitude.dispatchEvent(new Event('change', { bubbles: true }));
            longitude.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    // Mencegah pengiriman form berkali-kali.
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
