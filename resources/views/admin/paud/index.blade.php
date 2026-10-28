@extends('layouts.admin')

@section('judul', 'Data PAUD')

@section('isi')
    @php
        $adaImpor = \Illuminate\Support\Facades\Route::has('admin.impor.index');

        $jenisSatuan = \App\Models\Paud::JENIS_SATUAN;

        $namaKecamatanDipilih = filled(request('kecamatan'))
            ? optional($kecamatans->firstWhere('id', (int) request('kecamatan')))->nama
            : null;

        $labelKoordinat = [
            'ada' => 'Sudah ada koordinat',
            'belum' => 'Belum ada koordinat',
        ];

        $gayaInput = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100';
    @endphp

    {{-- 1. HEADER DATA PAUD --}}
    <section class="relative mb-6 overflow-hidden rounded-3xl p-6 text-white shadow-sm md:p-8"
             style="background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 60%, #38bdf8 100%);">

        {{-- Dua gelembung besar seperti Dashboard Admin --}}
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -bottom-16 right-24 h-40 w-40 rounded-full bg-white/10"></div>

        <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium text-sky-100">
                    <i class="fa-solid fa-school mr-1"></i>
                    PANEL ADMINISTRATOR
                </p>

                <h1 class="mt-2 text-2xl font-bold md:text-3xl">
                    Data PAUD
                </h1>

                <p class="mt-2 max-w-xl text-sm text-sky-50 md:text-base">
                    Kelola data PAUD di Kota Balikpapan dengan lebih mudah dan terorganisir.
                </p>

                <div class="mt-5 flex flex-wrap gap-2 text-sm">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 font-medium ring-1 ring-white/25">
                        <i class="fa-solid fa-database"></i>
                        {{ number_format($daftar->total(), 0, ',', '.') }} PAUD tercatat
                    </span>
                </div>
            </div>

            {{-- Tombol aksi --}}
            <div class="relative flex shrink-0 flex-wrap gap-3">
                @if ($adaImpor)
                    <a href="{{ route('admin.impor.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-800 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-sky-900">
                        <i class="fa-solid fa-file-excel text-lg"></i>
                        Impor Excel
                    </a>
                @endif

                <a href="{{ route('admin.paud.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-md transition hover:-translate-y-0.5 hover:bg-sky-50">
                    <i class="fa-solid fa-plus text-lg"></i>
                    Tambah PAUD
                </a>
            </div>
        </div>
    </section>

    {{-- 2. PENCARIAN DAN FILTER --}}
    <form method="GET"
          action="{{ route('admin.paud.index') }}"
          id="form-filter"
          class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm shadow-slate-200/50">

        <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-12">

            {{-- Pencarian --}}
            <div class="lg:col-span-5">
                <label for="q"
                       class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Cari PAUD
                </label>

                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input id="q"
                           name="q"
                           type="search"
                           value="{{ request('q') }}"
                           placeholder="Nama, NPSN, alamat, atau kelurahan"
                           class="{{ $gayaInput }} pl-11">
                </div>
            </div>

            {{-- Filter kecamatan --}}
            <div class="lg:col-span-3">
                <label for="kecamatan"
                       class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Kecamatan
                </label>

                <select id="kecamatan"
                        name="kecamatan"
                        data-auto-kirim
                        class="{{ $gayaInput }}">
                    <option value="">Semua kecamatan</option>

                    @foreach ($kecamatans as $k)
                        <option value="{{ $k->id }}"
                            @selected((string) request('kecamatan') === (string) $k->id)>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter koordinat --}}
            <div class="lg:col-span-2">
                <label for="koordinat"
                       class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Koordinat
                </label>

                <select id="koordinat"
                        name="koordinat"
                        data-auto-kirim
                        class="{{ $gayaInput }}">
                    <option value="">Semua</option>
                    <option value="ada" @selected(request('koordinat') === 'ada')>
                        Sudah ada
                    </option>
                    <option value="belum" @selected(request('koordinat') === 'belum')>
                        Belum ada
                    </option>
                </select>
            </div>

            {{-- Tombol cari --}}
            <div class="flex items-end gap-2 lg:col-span-2">
                <button type="submit"
                        class="flex-1 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>
                    Cari
                </button>

                @if ($adaFilter)
                    <a href="{{ route('admin.paud.index') }}"
                       class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                        Reset
                    </a>
                @endif
            </div>
        </div>

        {{-- Filter aktif --}}
        @if ($adaFilter)
            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3 text-xs">
                <span class="font-semibold text-slate-500">Filter aktif:</span>

                @if (filled(request('q')))
                    <a href="{{ request()->fullUrlWithQuery(['q' => null, 'page' => null]) }}"
                       class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 font-medium text-sky-800 ring-1 ring-sky-200 hover:bg-sky-100">
                        Kata: "{{ \Illuminate\Support\Str::limit(request('q'), 25) }}"
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif

                @if ($namaKecamatanDipilih)
                    <a href="{{ request()->fullUrlWithQuery(['kecamatan' => null, 'page' => null]) }}"
                       class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 font-medium text-sky-800 ring-1 ring-sky-200 hover:bg-sky-100">
                        Kecamatan: {{ $namaKecamatanDipilih }}
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif

                @if (isset($labelKoordinat[request('koordinat')]))
                    <a href="{{ request()->fullUrlWithQuery(['koordinat' => null, 'page' => null]) }}"
                       class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1 font-medium text-sky-800 ring-1 ring-sky-200 hover:bg-sky-100">
                        {{ $labelKoordinat[request('koordinat')] }}
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        @endif
    </form>

    {{-- 3. TABEL DESKTOP --}}
    <div class="mt-5 hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm shadow-slate-200/50 md:block">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[56rem] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="w-12 px-4 py-3 text-center">No</th>
                        <th class="px-4 py-3">PAUD</th>
                        <th class="px-4 py-3">NPSN</th>
                        <th class="px-4 py-3">Kecamatan</th>
                        <th class="px-4 py-3">Kelurahan</th>
                        <th class="px-4 py-3">Koordinat</th>
                        <th class="px-4 py-3 text-right">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($daftar as $p)
                        <tr class="transition hover:bg-sky-50/40">
                            <td class="px-4 py-3 text-center text-slate-400">
                                {{ $daftar->firstItem() + $loop->index }}
                            </td>

                            {{-- Nama PAUD --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($p->fotoUtama)
                                        <img src="{{ $p->fotoUtama->url }}"
                                             alt="Foto {{ $p->nama }}"
                                             loading="lazy"
                                             class="h-12 w-12 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                                    @else
                                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                            <i class="fa-regular fa-image"></i>
                                        </span>
                                    @endif

                                    <div class="min-w-0">
                                        <a href="{{ route('admin.paud.show', $p) }}"
                                           class="block truncate font-semibold text-slate-800 transition hover:text-sky-700">
                                            {{ $p->nama }}
                                        </a>

                                        <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                            @if ($p->jenis_satuan)
                                                <span title="{{ $jenisSatuan[$p->jenis_satuan] ?? $p->jenis_satuan }}"
                                                      class="rounded-md bg-violet-100 px-2 py-0.5 text-[11px] font-bold text-violet-700">
                                                    {{ $p->jenis_satuan }}
                                                </span>
                                            @endif

                                            @if ($p->status)
                                                <span class="rounded-md px-2 py-0.5 text-[11px] font-bold {{ $p->status === 'Negeri' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">
                                                    {{ $p->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 font-mono text-xs text-slate-600">
                                {{ $p->npsn ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-2 whitespace-nowrap">
                                    <span class="h-3 w-3 rounded-full ring-1 ring-slate-300"
                                          style="background-color: {{ $p->kecamatan->warna }}"></span>
                                    {{ $p->kecamatan->nama }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-slate-600">
                                {{ $p->kelurahan ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($p->punyaKoordinat())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Ada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        <i class="fa-solid fa-circle-minus"></i>
                                        Belum
                                    </span>
                                @endif
                            </td>

                            {{-- Tindakan --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.paud.show', $p) }}"
                                       title="Lihat detail"
                                       aria-label="Lihat detail {{ $p->nama }}"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 transition hover:bg-slate-200">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.paud.edit', $p) }}"
                                       title="Ubah data"
                                       aria-label="Ubah {{ $p->nama }}"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-700 transition hover:bg-sky-200">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.paud.destroy', $p) }}"
                                          onsubmit="return confirm(@js('Hapus PAUD ' . $p->nama . '? Semua fotonya ikut terhapus dan tidak bisa dikembalikan.'))">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                title="Hapus"
                                                aria-label="Hapus {{ $p->nama }}"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-600 transition hover:bg-red-200">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center">
                                @include('admin.paud._kosong', ['adaFilter' => $adaFilter])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 4. KARTU MOBILE --}}
    <div class="mt-5 space-y-3 md:hidden">
        @forelse ($daftar as $p)
            <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-start gap-3">
                    @if ($p->fotoUtama)
                        <img src="{{ $p->fotoUtama->url }}"
                             alt="Foto {{ $p->nama }}"
                             loading="lazy"
                             class="h-16 w-16 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                    @else
                        <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-xl text-slate-400">
                            <i class="fa-regular fa-image"></i>
                        </span>
                    @endif

                    <div class="min-w-0 flex-1">
                        <a href="{{ route('admin.paud.show', $p) }}"
                           class="block font-semibold leading-snug text-slate-800 transition hover:text-sky-700">
                            {{ $p->nama }}
                        </a>

                        <div class="mt-1 flex flex-wrap items-center gap-1.5">
                            @if ($p->jenis_satuan)
                                <span class="rounded-md bg-violet-100 px-2 py-0.5 text-[11px] font-bold text-violet-700">
                                    {{ $p->jenis_satuan }}
                                </span>
                            @endif

                            @if ($p->status)
                                <span class="rounded-md px-2 py-0.5 text-[11px] font-bold {{ $p->status === 'Negeri' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $p->status }}
                                </span>
                            @endif

                            @if ($p->punyaKoordinat())
                                <span class="rounded-md bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-emerald-800">
                                    Ada koordinat
                                </span>
                            @else
                                <span class="rounded-md bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-800">
                                    Belum ada koordinat
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Detail PAUD --}}
                <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    <div>
                        <dt class="text-slate-400">NPSN</dt>
                        <dd class="font-mono text-slate-700">{{ $p->npsn ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-slate-400">Kelurahan</dt>
                        <dd class="text-slate-700">{{ $p->kelurahan ?? '-' }}</dd>
                    </div>

                    <div class="col-span-2">
                        <dt class="text-slate-400">Kecamatan</dt>
                        <dd class="inline-flex items-center gap-2 text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-full ring-1 ring-slate-300"
                                  style="background-color: {{ $p->kecamatan->warna }}"></span>
                            {{ $p->kecamatan->nama }}
                        </dd>
                    </div>
                </dl>

                {{-- Tombol mobile --}}
                <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.paud.show', $p) }}"
                       class="flex-1 rounded-lg bg-slate-100 py-2 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        <i class="fa-solid fa-eye mr-1"></i>
                        Lihat
                    </a>

                    <a href="{{ route('admin.paud.edit', $p) }}"
                       class="flex-1 rounded-lg bg-sky-100 py-2 text-center text-sm font-medium text-sky-800 transition hover:bg-sky-200">
                        <i class="fa-solid fa-pen mr-1"></i>
                        Ubah
                    </a>

                    <form method="POST"
                          action="{{ route('admin.paud.destroy', $p) }}"
                          class="flex-1"
                          onsubmit="return confirm(@js('Hapus PAUD ' . $p->nama . '? Semua fotonya ikut terhapus dan tidak bisa dikembalikan.'))">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="w-full rounded-lg bg-red-100 py-2 text-sm font-medium text-red-700 transition hover:bg-red-200">
                            <i class="fa-solid fa-trash mr-1"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl bg-white px-4 py-12 text-center shadow-sm ring-1 ring-slate-200">
                @include('admin.paud._kosong', ['adaFilter' => $adaFilter])
            </div>
        @endforelse
    </div>

    {{-- 5. PAGINATION --}}
    <div class="mt-5">
        {{ $daftar->links('pagination.admin') }}
    </div>

    {{-- Filter kecamatan dan koordinat otomatis --}}
    <script>
        document.querySelectorAll('[data-auto-kirim]').forEach(function (pilihan) {
            pilihan.addEventListener('change', function () {
                document.getElementById('form-filter').submit();
            });
        });
    </script>
@endsection
