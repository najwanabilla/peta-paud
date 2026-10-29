
@extends('layouts.public')

@section('judul', 'Daftar PAUD | Peta PAUD Balikpapan')

@push('styles')
<style>
    .school-card {
        transition: all .2s ease;
    }

    .school-card:hover {
        transform: translateY(-3px);
        border-color: #93c5fd;
        box-shadow: 0 10px 25px rgba(30, 64, 175, .08);
    }
</style>
@endpush

@section('isi')
<div class="min-h-screen bg-slate-50 px-5 py-10">
    <div class="mx-auto max-w-6xl">

        {{-- KEMBALI --}}
        <a href="{{ route('beranda') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-900">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Beranda
        </a>

        {{-- JUDUL --}}
        <div class="mt-6">
            <h1 class="text-3xl font-extrabold text-slate-800">
                Daftar Sekolah PAUD
            </h1>

            <p class="mt-2 text-slate-500">
                Temukan sekolah berdasarkan nama, jenis, dan kecamatan.
            </p>
        </div>

        {{-- FORM PENCARIAN DAN FILTER --}}
        <form action="{{ route('paud.index') }}" method="GET"
              class="mt-7 rounded-2xl border border-slate-200 bg-white p-5">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                {{-- KATA KUNCI --}}
                <div>
                    <label for="q" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama atau alamat sekolah
                    </label>

                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $kataKunci }}"
                        placeholder="Contoh: TK Melati"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                {{-- KECAMATAN --}}
                <div>
                    <label for="kecamatan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kecamatan
                    </label>

                    <select
                        id="kecamatan"
                        name="kecamatan"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Semua Kecamatan</option>

                        @foreach ($kecamatans as $kecamatan)
                            <option
                                value="{{ $kecamatan->slug }}"
                                @selected($kecamatanTerpilih === $kecamatan->slug)
                            >
                                {{ $kecamatan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- JENIS SEKOLAH --}}
                <div>
                    <label for="jenis" class="mb-2 block text-sm font-semibold text-slate-700">
                        Jenis sekolah
                    </label>

                    <select
                        id="jenis"
                        name="jenis"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Semua Jenis</option>

                        @foreach (\App\Models\Paud::JENIS_SATUAN as $kode => $namaJenis)
                            <option
                                value="{{ $kode }}"
                                @selected($jenisTerpilih === $kode)
                            >
                                {{ $namaJenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- TOMBOL --}}
            <div class="mt-5 flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="rounded-xl bg-blue-700 px-6 py-3 font-semibold text-white hover:bg-blue-800"
                >
                    <i class="fa-solid fa-magnifying-glass mr-2"></i>
                    Cari Sekolah
                </button>

                <a
                    href="{{ route('paud.index') }}"
                    class="rounded-xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Reset Filter
                </a>
            </div>
        </form>

        {{-- JUDUL HASIL --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Hasil Pencarian
                </h2>

                @if ($kecamatanTerpilih)
                    @php
                        $namaKecamatanTerpilih = $kecamatans
                            ->firstWhere('slug', $kecamatanTerpilih)?->nama;
                    @endphp

                    @if ($namaKecamatanTerpilih)
                        <p class="mt-1 text-sm text-slate-500">
                            Kecamatan {{ $namaKecamatanTerpilih }}
                        </p>
                    @endif
                @else
                    <p class="mt-1 text-sm text-slate-500">
                        Semua kecamatan di Balikpapan
                    </p>
                @endif
            </div>

            <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                {{ $pauds->total() }} sekolah ditemukan
            </span>
        </div>

        {{-- DAFTAR SEKOLAH --}}
        @if ($pauds->count() > 0)

            <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">

                @foreach ($pauds as $sekolah)
                    <article class="school-card rounded-2xl border border-slate-200 bg-white p-5">

                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">
                                {{ $sekolah->jenis_satuan ?? 'PAUD' }}
                            </span>

                            @if ($sekolah->status)
                                <span class="text-xs text-slate-500">
                                    {{ $sekolah->status }}
                                </span>
                            @endif
                        </div>

                        <h3 class="mt-4 text-lg font-bold leading-snug text-slate-800">
                            <a href="{{ route('paud.show', $sekolah) }}"
                               class="hover:text-blue-700">
                                {{ $sekolah->nama }}
                            </a>
                        </h3>

                        <div class="mt-3 flex items-start gap-2 text-sm leading-6 text-slate-500">
                            <i class="fa-solid fa-location-dot mt-1 text-emerald-600"></i>

                            <p>
                                {{ $sekolah->alamat ?: 'Alamat belum tersedia' }}

                                @if ($sekolah->kelurahan)
                                    , {{ $sekolah->kelurahan }}
                                @endif

                                @if ($sekolah->kecamatan)
                                    , Kecamatan {{ $sekolah->kecamatan->nama }}
                                @endif
                            </p>
                        </div>

                        <a href="{{ route('paud.show', $sekolah) }}"
                           class="mt-5 inline-flex items-center gap-2 font-semibold text-blue-700 hover:text-blue-900">
                            Lihat detail
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </article>
                @endforeach

            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $pauds->links() }}
            </div>

        @else

            {{-- JIKA DATA TIDAK DITEMUKAN --}}
            <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-2xl text-blue-600">
                    <i class="fa-solid fa-school"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold text-slate-800">
                    Sekolah tidak ditemukan
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Coba ganti kata kunci atau pilih kecamatan dan jenis sekolah lainnya.
                </p>

                <a href="{{ route('paud.index') }}"
                   class="mt-5 inline-block rounded-xl bg-blue-700 px-5 py-3 font-semibold text-white hover:bg-blue-800">
                    Tampilkan Semua Sekolah
                </a>

            </div>

        @endif

    </div>
</div>
@endsection
