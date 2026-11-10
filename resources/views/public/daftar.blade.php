@extends('layouts.public')

@section('judul', 'Daftar PAUD | Peta PAUD Balikpapan')

@section('isi')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:py-10">
    <div class="mx-auto max-w-6xl">

        {{-- Navigasi kembali --}}
        <a href="{{ route('beranda') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-blue-700">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Beranda
        </a>

        {{-- Judul halaman --}}
        <div class="mb-6 mt-6">
            <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">Daftar Sekolah PAUD</h1>
            <p class="mt-2 text-sm text-slate-500">
                Cari data sekolah PAUD berdasarkan nama, kecamatan, atau jenis satuan pendidikan.
            </p>
        </div>

        {{-- Filter pencarian --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
            <form action="{{ route('paud.index') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <label for="q" class="mb-2 block text-sm font-medium text-slate-700">
                            Nama atau Alamat
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-magnifying-glass text-sm text-slate-400"></i>
                            </div>
                            <input id="q" type="text" name="q" value="{{ $kataKunci }}"
                                   placeholder="Masukkan nama atau alamat"
                                   class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>

                    <div>
                        <label for="kecamatan" class="mb-2 block text-sm font-medium text-slate-700">
                            Kecamatan
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-map-location-dot text-sm text-slate-400"></i>
                            </div>
                            <select id="kecamatan" name="kecamatan"
                                    class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2.5 pl-9 pr-9 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->slug }}" @selected($kecamatanTerpilih === $kecamatan->slug)>
                                        {{ $kecamatan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="jenis" class="mb-2 block text-sm font-medium text-slate-700">
                            Jenis Sekolah
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-school text-sm text-slate-400"></i>
                            </div>
                            <select id="jenis" name="jenis"
                                    class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2.5 pl-9 pr-9 text-sm text-slate-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <option value="">Semua Jenis</option>
                                @foreach (\App\Models\Paud::JENIS_SATUAN as $kode => $namaJenis)
                                    <option value="{{ $kode }}" @selected($jenisTerpilih === $kode)>
                                        {{ $namaJenis }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-2 border-t border-slate-100 pt-5 sm:flex-row">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        Cari Sekolah
                    </button>
                    <a href="{{ route('paud.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        <i class="fa-solid fa-rotate-right text-xs"></i>
                        Reset Filter
                    </a>
                </div>
            </form>
        </section>

        {{-- Informasi hasil --}}
        <div class="mt-8 flex flex-col gap-2 border-b border-slate-200 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Hasil Pencarian</h2>
                @if ($kecamatanTerpilih)
                    @php $namaKecamatanTerpilih = $kecamatans->firstWhere('slug', $kecamatanTerpilih)?->nama; @endphp
                    @if ($namaKecamatanTerpilih)
                        <p class="mt-1 text-sm text-slate-500">
                            Kecamatan {{ $namaKecamatanTerpilih }}
                        </p>
                    @endif
                @else
                    <p class="mt-1 text-sm text-slate-500">Seluruh kecamatan di Kota Balikpapan</p>
                @endif
            </div>
            <p class="text-sm text-slate-600">
                Menampilkan <span class="font-semibold text-slate-800">{{ $pauds->total() }}</span> sekolah
            </p>
        </div>

        {{-- Daftar sekolah --}}
        @if ($pauds->count() > 0)
            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($pauds as $sekolah)
                    <article class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 transition-colors hover:border-blue-300">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    {{ $sekolah->jenis_satuan ?? 'PAUD' }}
                                </span>
                                @if ($sekolah->status)
                                    <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                        {{ $sekolah->status }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="mt-4 text-base font-semibold leading-snug text-slate-800">
                                <a href="{{ route('paud.show', $sekolah) }}" class="hover:text-blue-700">
                                    {{ $sekolah->nama }}
                                </a>
                            </h3>

                            <div class="mt-3 flex items-start gap-2 text-sm leading-relaxed text-slate-500">
                                <i class="fa-solid fa-location-dot mt-1 text-xs text-slate-400"></i>
                                <p>
                                    {{ $sekolah->alamat ?: 'Alamat belum tersedia' }}
                                    @if ($sekolah->kelurahan), {{ $sekolah->kelurahan }} @endif
                                    @if ($sekolah->kecamatan), Kec. {{ $sekolah->kecamatan->nama }} @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 border-t border-slate-100 pt-4">
                            <a href="{{ route('paud.show', $sekolah) }}"
                               class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">
                                Lihat Profil
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8 flex justify-center">
                {{ $pauds->links() }}
            </div>
        @else
            {{-- Data tidak ditemukan --}}
            <div class="mt-5 rounded-xl border border-slate-200 bg-white px-5 py-12 text-center">
                <i class="fa-solid fa-school-circle-xmark text-3xl text-slate-300"></i>
                <h3 class="mt-4 text-lg font-semibold text-slate-800">Sekolah tidak ditemukan</h3>
                <p class="mt-2 text-sm text-slate-500">
                    Tidak ada data PAUD yang sesuai dengan filter pencarian.
                </p>
                <a href="{{ route('paud.index') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                    Tampilkan Semua Sekolah
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
