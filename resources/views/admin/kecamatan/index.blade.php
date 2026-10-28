@extends('layouts.admin')

@section('judul', 'Kecamatan')

@section('isi')
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
        color: white;
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

    .kecamatan-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 9999px;
        padding: 7px 11px;
        background: rgba(255,255,255,.14);
        color: white;
        font-size: 12px;
        font-weight: 600;
    }

    .kecamatan-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: white;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .kecamatan-stat {
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: white;
        padding: 20px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
        transition: .2s ease;
    }

    .kecamatan-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(15,23,42,.07);
    }

    .kecamatan-stat-icon {
        display: flex;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        font-size: 19px;
    }

    .kecamatan-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 12px;
        padding: 11px 16px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .kecamatan-button-primary {
        border: 1px solid #0284c7;
        background: #0284c7;
        color: white;
        box-shadow: 0 4px 10px rgba(2,132,199,.15);
    }

    .kecamatan-button-primary:hover {
        background: #0369a1;
        border-color: #0369a1;
        transform: translateY(-1px);
    }

    .kecamatan-button-edit {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .kecamatan-button-edit:hover {
        background: #dbeafe;
    }

    .kecamatan-button-delete {
        background: #fef2f2;
        color: #b91c1c;
        border: 0;
        cursor: pointer;
    }

    .kecamatan-button-delete:hover {
        background: #fee2e2;
    }

    .kecamatan-button-disabled {
        cursor: not-allowed;
        background: #f1f5f9;
        color: #94a3b8;
    }

    .kecamatan-table th {
        padding: 16px 20px;
        color: #64748b;
        background: #f8fafc;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .kecamatan-table td {
        padding: 17px 20px;
        vertical-align: middle;
    }

    .kecamatan-table tbody tr {
        transition: background .15s ease;
    }

    .kecamatan-table tbody tr:hover {
        background: #f8fbff;
    }

    .kecamatan-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 9999px;
        padding: 6px 11px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    @media (max-width: 640px) {
        .kecamatan-banner {
            padding: 22px;
        }

        .kecamatan-stat {
            padding: 15px;
        }
    }
</style>

<div class="kecamatan-page mx-auto max-w-7xl space-y-6">

    @php
        $totalKecamatan = $daftar->count();
        $totalAktif = $daftar->where('is_active', true)->count();
        $totalNonaktif = $totalKecamatan - $totalAktif;
        $totalGeojson = $daftar->filter(fn ($k) => filled($k->geojson))->count();
    @endphp

    {{-- HEADER --}}
    <section class="kecamatan-banner">
        <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <p class="mb-3 flex items-center gap-2 text-sm font-semibold text-blue-50">
                    <i class="fa-solid fa-map-location-dot"></i>
                    PANEL ADMINISTRATOR
                </p>

                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">
                    Data Kecamatan
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-50">
                    Kelola wilayah administrasi, warna peta, batas wilayah,
                    dan status kecamatan untuk Peta PAUD Balikpapan.
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="kecamatan-chip">
                        <i class="fa-solid fa-map"></i>
                        Wilayah administrasi
                    </span>
                    <span class="kecamatan-chip">
                        <i class="fa-solid fa-palette"></i>
                        Warna peta
                    </span>
                    <span class="kecamatan-chip">
                        <i class="fa-solid fa-school"></i>
                        Data PAUD
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.kecamatan.create') }}"
               class="kecamatan-button shrink-0 self-start border border-white/30 bg-white px-5 py-3 text-sky-700 shadow-sm hover:-translate-y-0.5 hover:bg-blue-50 sm:self-center">
                <i class="fa-solid fa-plus"></i>
                Tambah Kecamatan
            </a>
        </div>
    </section>

    {{-- RINGKASAN --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="kecamatan-stat">
            <div class="kecamatan-stat-icon bg-blue-50 text-blue-600">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-slate-500">Total Kecamatan</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $totalKecamatan }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Terdaftar di sistem</p>
            </div>
        </div>

        <div class="kecamatan-stat">
            <div class="kecamatan-stat-icon bg-emerald-50 text-emerald-600">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-slate-500">Kecamatan Aktif</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $totalAktif }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Aktif di pengaturan</p>
            </div>
        </div>

        <div class="kecamatan-stat">
            <div class="kecamatan-stat-icon bg-amber-50 text-amber-600">
                <i class="fa-solid fa-circle-minus"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-slate-500">Kecamatan Nonaktif</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $totalNonaktif }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Tidak aktif di pengaturan</p>
            </div>
        </div>

        <div class="kecamatan-stat">
            <div class="kecamatan-stat-icon bg-violet-50 text-violet-600">
                <i class="fa-solid fa-draw-polygon"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm text-slate-500">Batas Wilayah</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">
                    {{ $totalGeojson }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Memiliki data GeoJSON</p>
            </div>
        </div>

    </div>

    {{-- TABEL KECAMATAN --}}
    <section class="kecamatan-card">

        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-list-ul text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Daftar Kecamatan</h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Informasi wilayah dan pengelolaan data.
                    </p>
                </div>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700">
                <i class="fa-solid fa-database"></i>
                {{ $totalKecamatan }} data
            </span>
        </div>

        @if (session('sukses'))
            <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 sm:mx-6">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('sukses') }}</span>
            </div>
        @endif

        @if (session('galat'))
            <div class="mx-5 mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 sm:mx-6">
                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                <span>{{ session('galat') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="kecamatan-table w-full min-w-[850px] text-left text-sm">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No.</th>
                        <th>Kecamatan</th>
                        <th>Warna Peta</th>
                        <th class="text-center">Jumlah PAUD</th>
                        <th>Batas Wilayah</th>
                        <th>Status</th>
                        <th class="text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($daftar as $k)
                        <tr>
                            <td class="text-center text-xs font-semibold text-slate-400">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                        <i class="fa-solid fa-map-pin"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">
                                            {{ $k->nama }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Wilayah kecamatan
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="inline-flex items-center gap-2.5 rounded-lg border border-slate-100 bg-slate-50 px-3 py-2">
                                    <span
                                        class="h-5 w-5 shrink-0 rounded-full border border-black/10 shadow-sm"
                                        style="background-color: {{ $k->warna }}"
                                    ></span>
                                    <span class="font-mono text-xs font-semibold text-slate-600">
                                        {{ $k->warna }}
                                    </span>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="inline-flex min-w-10 items-center justify-center rounded-lg bg-blue-50 px-3 py-2 font-extrabold text-blue-700">
                                    {{ $k->pauds_count }}
                                </span>
                            </td>

                            <td>
                                @if ($k->geojson)
                                    <span class="kecamatan-status bg-emerald-50 text-emerald-700">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Sudah ada
                                    </span>
                                @else
                                    <span class="kecamatan-status bg-slate-100 text-slate-500">
                                        <i class="fa-regular fa-circle"></i>
                                        Belum ada
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($k->is_active)
                                    <span class="kecamatan-status bg-emerald-50 text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="kecamatan-status bg-slate-100 text-slate-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.kecamatan.edit', $k) }}"
                                        class="kecamatan-button kecamatan-button-edit px-3 py-2"
                                        title="Ubah kecamatan {{ $k->nama }}"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Ubah
                                    </a>

                                    @if ($k->pauds_count > 0)
                                        <button
                                            type="button"
                                            disabled
                                            class="kecamatan-button kecamatan-button-disabled px-3 py-2"
                                            title="Tidak bisa dihapus karena masih memiliki PAUD"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                            Hapus
                                        </button>
                                    @else
                                        <form
                                            method="POST"
                                            action="{{ route('admin.kecamatan.destroy', $k) }}"
                                            onsubmit="return confirm(@js('Hapus kecamatan ' . $k->nama . '? Tindakan ini tidak bisa dibatalkan.'))"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="kecamatan-button kecamatan-button-delete px-3 py-2"
                                                title="Hapus kecamatan {{ $k->nama }}"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-400">
                                    <i class="fa-solid fa-map-location-dot text-2xl"></i>
                                </div>

                                <h3 class="mt-4 font-bold text-slate-700">
                                    Belum Ada Data Kecamatan
                                </h3>

                                <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500">
                                    Tambahkan kecamatan untuk mulai mengelola wilayah pada Peta PAUD Balikpapan.
                                </p>

                                <a
                                    href="{{ route('admin.kecamatan.create') }}"
                                    class="kecamatan-button kecamatan-button-primary mt-5"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    Tambah Kecamatan
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-2 border-t border-slate-100 bg-slate-50/70 px-5 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p>
                <i class="fa-solid fa-circle-info mr-1 text-blue-500"></i>
                Kecamatan yang masih memiliki data PAUD tidak dapat dihapus.
            </p>

            <p>
                Total: <span class="font-bold text-slate-700">{{ $totalKecamatan }} kecamatan</span>
            </p>
        </div>

    </section>
</div>
@endsection
