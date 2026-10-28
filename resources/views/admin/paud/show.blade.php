@extends('layouts.admin')

@section('judul', 'Detail PAUD')

@section('isi')
    @php
        $nilai = fn ($v) => filled($v)
            ? e($v)
            : '<span class="font-normal italic text-slate-400">Belum diisi</span>';

        $jenisSatuan = \App\Models\Paud::JENIS_SATUAN[$paud->jenis_satuan]
            ?? $paud->jenis_satuan;
    @endphp

    <style>
        .detail-banner {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            border-radius: 28px;
            background: linear-gradient(120deg, #0277ad 0%, #0ea5e9 55%, #38bdf8 100%);
            box-shadow: 0 4px 8px rgba(2, 119, 173, .12);
        }

        .detail-banner::before,
        .detail-banner::after {
            content: "";
            position: absolute;
            z-index: -1;
            border-radius: 9999px;
            background: rgba(255,255,255,.12);
            pointer-events: none;
        }

        .detail-banner::before {
            width: 220px;
            height: 220px;
            right: -50px;
            top: -100px;
        }

        .detail-banner::after {
            width: 180px;
            height: 180px;
            right: 110px;
            bottom: -120px;
        }

        .detail-card {
            overflow: hidden;
            border: 1px solid #dbe5ef;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .025);
        }

        .detail-card-head {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-icon {
            display: flex;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #eff6ff;
            color: #0284c7;
            font-size: 18px;
        }

        .detail-action {
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

        .detail-primary {
            background: #0284c7;
            color: #fff;
        }

        .detail-primary:hover {
            background: #0369a1;
        }

        .detail-secondary {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #334155;
        }

        .detail-secondary:hover {
            background: #f8fafc;
        }

        .detail-danger {
            border: 1px solid rgba(255,255,255,.3);
            background: rgba(220,38,38,.9);
            color: #fff;
        }

        .detail-danger:hover {
            background: #dc2626;
        }

        .detail-value {
            overflow-wrap: anywhere;
            font-weight: 600;
            color: #1e293b;
        }

        @media (max-width: 640px) {
            .detail-card-head {
                padding: 17px;
            }
        }
    </style>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- HEADER --}}
        <section class="detail-banner p-6 text-white sm:p-8">
            <div class="relative z-10">
                <a href="{{ route('admin.paud.index') }}"
                   class="detail-action mb-6 border border-white/30 bg-white/10 text-white hover:bg-white/20">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar PAUD
                </a>

                <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div class="min-w-0">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="rounded-full border border-white/20 bg-white/15 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider">
                                <i class="fa-solid fa-school mr-1"></i>
                                Detail Sekolah
                            </span>

                            @if (strtolower($paud->status ?? '') === 'negeri')
                                <span class="rounded-full bg-white/20 px-3 py-1.5 text-xs font-semibold">
                                    Negeri
                                </span>
                            @elseif (strtolower($paud->status ?? '') === 'swasta')
                                <span class="rounded-full bg-white/20 px-3 py-1.5 text-xs font-semibold">
                                    Swasta
                                </span>
                            @endif
                        </div>

                        <h1 class="max-w-3xl break-words text-2xl font-extrabold leading-tight sm:text-3xl lg:text-4xl">
                            {{ $paud->nama }}
                        </h1>

                        <p class="mt-3 flex items-start gap-2 text-sm leading-relaxed text-blue-50 sm:text-base">
                            <i class="fa-solid fa-location-dot mt-1"></i>
                            <span>
                                {{ $paud->alamat ?: 'Alamat belum diisi' }}
                                @if ($paud->kelurahan)
                                    , {{ $paud->kelurahan }}
                                @endif
                            </span>
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span class="rounded-xl border border-white/20 bg-white/15 px-3 py-2">
                                <i class="fa-solid fa-school mr-1.5"></i>
                                {{ $jenisSatuan ?: 'Jenis belum diisi' }}
                            </span>

                            <span class="rounded-xl border border-white/20 bg-white/15 px-3 py-2">
                                <i class="fa-solid fa-map mr-1.5"></i>
                                {{ $paud->kecamatan->nama ?? 'Kecamatan belum diisi' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.paud.edit', $paud) }}"
                           class="detail-action bg-white text-sky-700 shadow-sm hover:-translate-y-0.5 hover:bg-blue-50">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Ubah Data
                        </a>

                        <form method="POST"
                              action="{{ route('admin.paud.destroy', $paud) }}"
                              onsubmit="return confirm(@js('Hapus PAUD ' . $paud->nama . '? Semua fotonya ikut terhapus dan tidak bisa dikembalikan.'))">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="detail-action detail-danger">
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        {{-- RINGKASAN --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="detail-card p-5 transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="detail-icon">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">NPSN</p>
                        <p class="mt-1 break-words font-bold text-slate-800">
                            {{ $paud->npsn ?: 'Belum diisi' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="detail-card p-5 transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="detail-icon">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Jumlah Foto</p>
                        <p class="mt-1 text-xl font-bold text-slate-800">
                            {{ $paud->fotos->count() }}
                            <span class="text-sm font-medium text-slate-400">foto</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="detail-card p-5 transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="detail-icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">Kepala Sekolah</p>
                        <p class="mt-1 break-words font-bold text-slate-800">
                            {{ $paud->kepala_sekolah ?: 'Belum diisi' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="detail-card p-5 transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="detail-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm text-slate-500">Telepon</p>
                        <p class="mt-1 break-words font-bold text-slate-800">
                            {{ $paud->telepon ?: 'Belum diisi' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFORMASI SEKOLAH --}}
        <section class="detail-card">
            <div class="detail-card-head">
                <div class="detail-icon">
                    <i class="fa-solid fa-school"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Informasi Sekolah</h2>
                    <p class="mt-1 text-xs text-slate-500">Data identitas dan administrasi PAUD.</p>
                </div>
            </div>

            <dl class="grid gap-x-10 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="mb-1.5 text-sm text-slate-500">Nama PAUD</dt>
                    <dd class="detail-value">{{ $paud->nama }}</dd>
                </div>

                <div>
                    <dt class="mb-1.5 text-sm text-slate-500">NPSN</dt>
                    <dd class="detail-value">{!! $nilai($paud->npsn) !!}</dd>
                </div>

                <div>
                    <dt class="mb-1.5 text-sm text-slate-500">Jenis Satuan</dt>
                    <dd class="detail-value">{!! $nilai($jenisSatuan) !!}</dd>
                </div>

                <div>
                    <dt class="mb-1.5 text-sm text-slate-500">Status Sekolah</dt>
                    <dd class="detail-value">{!! $nilai($paud->status) !!}</dd>
                </div>

                <div>
                    <dt class="mb-1.5 text-sm text-slate-500">Kepala Sekolah</dt>
                    <dd class="detail-value">{!! $nilai($paud->kepala_sekolah) !!}</dd>
                </div>

                <div>
                    <dt class="mb-1.5 text-sm text-slate-500">Telepon</dt>
                    <dd class="detail-value">{!! $nilai($paud->telepon) !!}</dd>
                </div>
            </dl>
        </section>

        {{-- INFORMASI LOKASI --}}
        <section class="detail-card">
            <div class="detail-card-head">
                <div class="detail-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Informasi Lokasi</h2>
                    <p class="mt-1 text-xs text-slate-500">Alamat lengkap dan koordinat sekolah.</p>
                </div>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-2">
                <div class="space-y-5">
                    <div>
                        <p class="mb-1.5 text-sm text-slate-500">Kecamatan</p>
                        <div class="flex items-center gap-3">
                            <span class="h-4 w-4 shrink-0 rounded-full ring-2 ring-slate-200"
                                  style="background-color: {{ $paud->kecamatan->warna ?? '#cbd5e1' }}"></span>
                            <span class="font-semibold text-slate-800">
                                {{ $paud->kecamatan->nama ?? 'Belum diisi' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="mb-1.5 text-sm text-slate-500">Kelurahan</p>
                        <p class="detail-value">{!! $nilai($paud->kelurahan) !!}</p>
                    </div>

                    <div>
                        <p class="mb-1.5 text-sm text-slate-500">Kode Pos</p>
                        <p class="detail-value">{!! $nilai($paud->kode_pos) !!}</p>
                    </div>

                    <div>
                        <p class="mb-1.5 text-sm text-slate-500">Alamat Lengkap</p>
                        <p class="whitespace-pre-line leading-relaxed text-slate-800">
                            {!! $nilai($paud->alamat) !!}
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white text-sky-600 shadow-sm">
                        <i class="fa-solid fa-map-location-dot text-xl"></i>
                    </div>

                    <h3 class="font-bold text-slate-800">Koordinat Lokasi</h3>

                    @if ($paud->punyaKoordinat())
                        <div class="mt-3 space-y-2">
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                <p class="text-xs text-slate-500">Latitude</p>
                                <p class="mt-1 break-all font-mono text-sm font-semibold text-slate-800">
                                    {{ $paud->latitude }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                <p class="text-xs text-slate-500">Longitude</p>
                                <p class="mt-1 break-all font-mono text-sm font-semibold text-slate-800">
                                    {{ $paud->longitude }}
                                </p>
                            </div>
                        </div>

                        <a href="https://www.openstreetmap.org/?mlat={{ $paud->latitude }}&mlon={{ $paud->longitude }}#map=18/{{ $paud->latitude }}/{{ $paud->longitude }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="detail-action detail-primary mt-4 w-full">
                            <i class="fa-solid fa-map"></i>
                            Buka Peta Lokasi
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                    @else
                        <p class="mt-3 rounded-xl border border-dashed border-blue-200 bg-white p-4 text-sm leading-relaxed text-slate-500">
                            <i class="fa-solid fa-circle-info mr-1 text-sky-600"></i>
                            Koordinat belum tersedia. Silakan ubah data PAUD untuk menambahkan lokasi.
                        </p>
                    @endif
                </div>
            </div>
        </section>

        {{-- DESKRIPSI --}}
        <section class="detail-card">
            <div class="detail-card-head">
                <div class="detail-icon">
                    <i class="fa-solid fa-align-left"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Deskripsi PAUD</h2>
                    <p class="mt-1 text-xs text-slate-500">Informasi tambahan tentang sekolah.</p>
                </div>
            </div>

            <div class="p-6">
                @if (filled($paud->deskripsi))
                    <p class="whitespace-pre-line leading-8 text-slate-600">{{ $paud->deskripsi }}</p>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center">
                        <i class="fa-regular fa-file-lines mb-3 text-3xl text-slate-300"></i>
                        <p class="font-medium text-slate-500">Belum ada deskripsi untuk PAUD ini.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- GALERI FOTO --}}
        <section class="detail-card">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="detail-icon">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Galeri Foto</h2>
                        <p class="mt-1 text-xs text-slate-500">Dokumentasi {{ $paud->nama }}</p>
                    </div>
                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1.5 text-sm font-bold text-blue-700">
                    {{ $paud->fotos->count() }} Foto
                </span>
            </div>

            <div class="p-6">
                @if ($paud->fotos->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-12 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-slate-300 shadow-sm">
                            <i class="fa-regular fa-image text-3xl"></i>
                        </div>

                        <h3 class="font-bold text-slate-700">Belum Ada Foto</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Tambahkan foto melalui halaman ubah data PAUD.
                        </p>

                        <a href="{{ route('admin.paud.edit', $paud) }}"
                           class="detail-action detail-primary mt-5">
                            <i class="fa-solid fa-plus"></i>
                            Kelola Foto
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                        @foreach ($paud->fotos as $foto)
                            <figure class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                <img src="{{ $foto->url }}"
                                     alt="Foto {{ $paud->nama }}"
                                     loading="lazy"
                                     class="aspect-square w-full object-cover transition duration-500 group-hover:scale-105">

                                @if ($foto->is_primary)
                                    <figcaption class="absolute left-2 top-2 inline-flex items-center gap-1.5 rounded-full bg-sky-600 px-3 py-1.5 text-xs font-bold text-white shadow-lg">
                                        <i class="fa-solid fa-star"></i>
                                        Foto Utama
                                    </figcaption>
                                @endif

                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 pt-8">
                                    <p class="truncate text-xs font-medium text-white">
                                        {{ $paud->nama }}
                                    </p>
                                </div>
                            </figure>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- RIWAYAT DATA --}}
        <section class="detail-card p-6">
            <div class="mb-5 flex items-center gap-3">
                <div class="detail-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Riwayat Data</h2>
                    <p class="mt-1 text-xs text-slate-500">Waktu pembuatan dan pembaruan data.</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-blue-50/60 p-4">
                    <p class="text-sm text-slate-500">
                        <i class="fa-solid fa-calendar-plus mr-1.5 text-sky-600"></i>
                        Ditambahkan pada
                    </p>
                    <p class="mt-2 font-semibold text-slate-800">
                        {{ $paud->created_at?->translatedFormat('d F Y') ?? 'Belum tersedia' }}
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $paud->created_at?->translatedFormat('H:i') ?? '--:--' }} WITA
                    </p>
                </div>

                <div class="rounded-xl bg-blue-50/60 p-4">
                    <p class="text-sm text-slate-500">
                        <i class="fa-solid fa-calendar-check mr-1.5 text-sky-600"></i>
                        Terakhir diperbarui
                    </p>
                    <p class="mt-2 font-semibold text-slate-800">
                        {{ $paud->updated_at?->translatedFormat('d F Y') ?? 'Belum tersedia' }}
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $paud->updated_at?->translatedFormat('H:i') ?? '--:--' }} WITA
                    </p>
                </div>
            </div>
        </section>

        {{-- TOMBOL BAWAH --}}
        <div class="flex flex-wrap justify-between gap-3 pb-4">
            <a href="{{ route('admin.paud.index') }}"
               class="detail-action detail-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar
            </a>

            <a href="{{ route('admin.paud.edit', $paud) }}"
               class="detail-action detail-primary">
                <i class="fa-solid fa-pen-to-square"></i>
                Ubah Data PAUD
            </a>
        </div>

    </div>
@endsection
