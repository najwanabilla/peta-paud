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

        $gayaInput = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100';
        
        $warnaAvatar = [
            'bg-sky-100 text-sky-700',
            'bg-emerald-100 text-emerald-700',
            'bg-violet-100 text-violet-700',
            'bg-amber-100 text-amber-700',
            'bg-rose-100 text-rose-700',
            'bg-cyan-100 text-cyan-700',
        ];
    @endphp

    {{-- HEADER --}}
    <section class="relative mb-6 overflow-hidden rounded-3xl p-6 text-white shadow-sm md:p-8"
        style="background: linear-gradient(135deg, #0369a1, #0ea5e9, #38bdf8);">

        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -bottom-16 right-24 h-40 w-40 rounded-full bg-white/10"></div>

        <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold text-sky-50">
                    <i class="fa-solid fa-school"></i>
                    PANEL ADMINISTRATOR
                </div>

                <h1 class="mt-3 text-2xl font-bold md:text-3xl">
                    Data PAUD
                </h1>

                <p class="mt-2 max-w-xl text-sm text-sky-50 md:text-base">
                    Kelola dan pantau seluruh data pendidikan anak usia dini di Kota Balikpapan.
                </p>

                <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-sm font-medium ring-1 ring-white/20">
                    <i class="fa-solid fa-database"></i>
                    {{ number_format($daftar->total(), 0, ',', '.') }} PAUD tercatat
                </div>
            </div>

            <div class="relative flex flex-wrap gap-3">
                @if ($adaImpor)
                    <a href="{{ route('admin.impor.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-800 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:bg-sky-900">
                        <i class="fa-solid fa-file-excel"></i>
                        Impor Excel
                    </a>
                @endif

                <a href="{{ route('admin.paud.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-md transition hover:bg-sky-50">
                    <i class="fa-solid fa-plus"></i>
                    Tambah PAUD
                </a>
            </div>
        </div>
    </section>

    {{-- PENCARIAN DAN FILTER --}}
    <form method="GET"
        action="{{ route('admin.paud.index') }}"
        id="form-filter"
        class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">

        <div class="mb-4 flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                <i class="fa-solid fa-filter"></i>
            </span>
            <div>
                <h2 class="font-bold text-slate-800">Cari dan Filter PAUD</h2>
                <p class="text-xs text-slate-400">Temukan data sekolah dengan mudah</p>
            </div>
        </div>

        <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <label for="q" class="mb-1.5 block text-xs font-semibold text-slate-500">
                    Pencarian
                </label>

                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input id="q"
                        name="q"
                        type="search"
                        value="{{ request('q') }}"
                        placeholder="Nama, NPSN, alamat, atau kelurahan"
                        class="{{ $gayaInput }} pl-11">
                </div>
            </div>

            <div class="lg:col-span-3">
                <label for="kecamatan" class="mb-1.5 block text-xs font-semibold text-slate-500">
                    Kecamatan
                </label>

                <select id="kecamatan" name="kecamatan" data-auto-kirim class="{{ $gayaInput }}">
                    <option value="">Semua kecamatan</option>

                    @foreach ($kecamatans as $k)
                        <option value="{{ $k->id }}"
                            @selected((string) request('kecamatan') === (string) $k->id)>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label for="koordinat" class="mb-1.5 block text-xs font-semibold text-slate-500">
                    Koordinat
                </label>

                <select id="koordinat" name="koordinat" data-auto-kirim class="{{ $gayaInput }}">
                    <option value="">Semua</option>
                    <option value="ada" @selected(request('koordinat') === 'ada')>
                        Sudah ada
                    </option>
                    <option value="belum" @selected(request('koordinat') === 'belum')>
                        Belum ada
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2 lg:col-span-2">
                <button type="submit"
                    class="flex-1 rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>
                    Cari
                </button>

                @if ($adaFilter)
                    <a href="{{ route('admin.paud.index') }}"
                        class="rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">
                        Reset
                    </a>
                @endif
            </div>
        </div>

        {{-- FILTER AKTIF --}}
        @if ($adaFilter)
            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4 text-xs">
                <span class="font-semibold text-slate-500">Filter aktif:</span>

                @if (filled(request('q')))
                    <a href="{{ request()->fullUrlWithQuery(['q' => null, 'page' => null]) }}"
                        class="rounded-full bg-sky-50 px-3 py-1.5 font-medium text-sky-700 ring-1 ring-sky-200">
                        Kata: "{{ \Illuminate\Support\Str::limit(request('q'), 25) }}"
                        <i class="fa-solid fa-xmark ml-1"></i>
                    </a>
                @endif

                @if ($namaKecamatanDipilih)
                    <a href="{{ request()->fullUrlWithQuery(['kecamatan' => null, 'page' => null]) }}"
                        class="rounded-full bg-sky-50 px-3 py-1.5 font-medium text-sky-700 ring-1 ring-sky-200">
                        Kecamatan: {{ $namaKecamatanDipilih }}
                        <i class="fa-solid fa-xmark ml-1"></i>
                    </a>
                @endif

                @if (isset($labelKoordinat[request('koordinat')]))
                    <a href="{{ request()->fullUrlWithQuery(['koordinat' => null, 'page' => null]) }}"
                        class="rounded-full bg-sky-50 px-3 py-1.5 font-medium text-sky-700 ring-1 ring-sky-200">
                        {{ $labelKoordinat[request('koordinat')] }}
                        <i class="fa-solid fa-xmark ml-1"></i>
                    </a>
                @endif
            </div>
        @endif
    </form>

    {{-- JUDUL DAFTAR --}}
    <div class="mb-4 mt-6 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Sekolah PAUD</h2>
            <p class="mt-1 text-sm text-slate-500">
                Menampilkan {{ $daftar->count() }} dari {{ $daftar->total() }} data PAUD
            </p>
        </div>

        <span class="rounded-full bg-sky-50 px-3 py-1.5 text-xs font-semibold text-sky-700">
            <i class="fa-solid fa-school mr-1"></i>
            Data Sekolah
        </span>
    </div>

    {{-- KARTU PAUD (SEKARANG BERBENTUK BARIS LURUS) --}}
    <div class="flex flex-col gap-3">
        @forelse ($daftar as $p)
            @php
                $namaBersih = trim(preg_replace('/[^\pL\pN]+/u', ' ', $p->nama));
                $kataNama = preg_split('/\s+/u', $namaBersih, -1, PREG_SPLIT_NO_EMPTY);

                if (count($kataNama) >= 2) {
                    $inisial = mb_substr($kataNama[0], 0, 1) . mb_substr($kataNama[1], 0, 1);
                } else {
                    $inisial = mb_substr($namaBersih, 0, 2);
                }

                $warna = $warnaAvatar[($daftar->firstItem() + $loop->index - 1) % count($warnaAvatar)];
            @endphp

            {{-- SATU BARIS --}}
            <article class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg">
                
                {{-- BAGIAN KIRI (Foto, Nama, Info) --}}
                <div class="flex min-w-0 flex-1 items-center gap-4">
                    {{-- FOTO ATAU INISIAL --}}
                    @if ($p->fotoUtama)
                        <img src="{{ $p->fotoUtama->url }}"
                            alt="Foto {{ $p->nama }}"
                            loading="lazy"
                            class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                    @else
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl {{ $warna }} text-lg font-extrabold">
                            {{ mb_strtoupper($inisial) }}
                        </div>
                    @endif

                    {{-- NAMA DAN JENIS --}}
                    <div class="min-w-0">
                        <a href="{{ route('admin.paud.show', $p) }}"
                            class="line-clamp-1 text-base font-bold text-slate-800 transition group-hover:text-sky-700">
                            {{ $p->nama }}
                        </a>

                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400">
                            <p><i class="fa-solid fa-id-card mr-1"></i> NPSN: {{ $p->npsn ?? '-' }}</p>
                            <p><i class="fa-solid fa-location-dot mr-1"></i> {{ optional($p->kecamatan)->nama ?? '-' }} ({{ $p->kelurahan ?? '-' }})</p>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN TENGAH (Badge/Label) --}}
                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    @if ($p->jenis_satuan)
                        <span class="rounded-lg bg-violet-50 px-2.5 py-1 text-[11px] font-bold text-violet-700">
                            {{ $jenisSatuan[$p->jenis_satuan] ?? $p->jenis_satuan }}
                        </span>
                    @endif

                    @if ($p->status)
                        <span class="rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $p->status === 'Negeri' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $p->status }}
                        </span>
                    @endif

                    @if ($p->punyaKoordinat())
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                            <i class="fa-solid fa-circle-check"></i> Tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                            <i class="fa-solid fa-circle-minus"></i> Belum tersedia
                        </span>
                    @endif
                </div>

                {{-- BAGIAN KANAN (Tombol Aksi) --}}
                <div class="flex shrink-0 items-center gap-2">
                    <a href="{{ route('admin.paud.show', $p) }}"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700 transition hover:bg-slate-200" title="Detail">
                        <i class="fa-solid fa-eye"></i>
                    </a>

                    <a href="{{ route('admin.paud.edit', $p) }}"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-700 transition hover:bg-sky-100" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    <form method="POST"
                        action="{{ route('admin.paud.destroy', $p) }}"
                        onsubmit="return confirm(@js('Hapus PAUD ' . $p->nama . '? Semua fotonya ikut terhapus dan tidak bisa dikembalikan.'))"
                        class="m-0">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" title="Hapus">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white px-4 py-12 text-center shadow-sm">
                @include('admin.paud._kosong', ['adaFilter' => $adaFilter])
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $daftar->links('pagination.admin') }}
    </div>

    {{-- FILTER OTOMATIS --}}
    <script>
        document.querySelectorAll('[data-auto-kirim]').forEach(function (pilihan) {
            pilihan.addEventListener('change', function () {
                document.getElementById('form-filter').submit();
            });
        });
    </script>
@endsection