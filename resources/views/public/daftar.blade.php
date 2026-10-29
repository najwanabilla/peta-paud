@extends('layouts.public')

@section('judul', 'Daftar PAUD | Peta PAUD Balikpapan')

@push('styles')
<style>
    .school-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .school-card:hover {
        transform: translateY(-4px);
        border-color: #bfdbfe;
        box-shadow: 0 15px 30px -10px rgba(37, 99, 235, 0.12);
    }
</style>
@endpush

@section('isi')
<div class="min-h-screen bg-slate-50 px-5 py-12">
    <div class="mx-auto max-w-6xl">

        <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:text-blue-900 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <div class="mt-8 mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 md:text-4xl">Daftar Sekolah PAUD</h1>
            <p class="mt-3 text-base text-slate-500">Temukan sekolah berdasarkan nama, jenis, dan kecamatan.</p>
        </div>

        {{-- FORM PENCARIAN --}}
        <form action="{{ route('paud.index') }}" method="GET" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="q" class="mb-2 block text-sm font-bold text-slate-700">Nama atau alamat sekolah</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        </div>
                        <input id="q" type="text" name="q" value="{{ $kataKunci }}" placeholder="Contoh: TK Melati" class="w-full rounded-xl border border-slate-300 py-3.5 pl-11 pr-4 outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>

                <div>
                    <label for="kecamatan" class="mb-2 block text-sm font-bold text-slate-700">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->slug }}" @selected($kecamatanTerpilih === $kecamatan->slug)>
                                {{ $kecamatan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="jenis" class="mb-2 block text-sm font-bold text-slate-700">Jenis sekolah</label>
                    <select id="jenis" name="jenis" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3.5 outline-none transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Semua Jenis</option>
                        @foreach (\App\Models\Paud::JENIS_SATUAN as $kode => $namaJenis)
                            <option value="{{ $kode }}" @selected($jenisTerpilih === $kode)>
                                {{ $namaJenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="rounded-xl bg-blue-700 px-8 py-3.5 font-bold text-white shadow-md transition-all hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-lg">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Cari Sekolah
                </button>
                <a href="{{ route('paud.index') }}" class="rounded-xl border border-slate-300 bg-white px-8 py-3.5 font-bold text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-800">
                    Reset Filter
                </a>
            </div>
        </form>

        {{-- HASIL PENCARIAN --}}
        <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Hasil Pencarian</h2>
                @if ($kecamatanTerpilih)
                    @php $namaKecamatanTerpilih = $kecamatans->firstWhere('slug', $kecamatanTerpilih)?->nama; @endphp
                    @if ($namaKecamatanTerpilih)
                        <p class="mt-1 text-sm font-medium text-slate-500"><i class="fa-solid fa-map-pin mr-1 text-blue-500"></i> Kecamatan {{ $namaKecamatanTerpilih }}</p>
                    @endif
                @else
                    <p class="mt-1 text-sm font-medium text-slate-500"><i class="fa-solid fa-map-location-dot mr-1 text-blue-500"></i> Semua kecamatan di Balikpapan</p>
                @endif
            </div>
            <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-bold text-blue-700 shadow-sm">
                {{ $pauds->total() }} Sekolah Ditemukan
            </span>
        </div>

        {{-- DAFTAR SEKOLAH --}}
        @if ($pauds->count() > 0)
            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($pauds as $sekolah)
                    <article class="school-card flex flex-col justify-between rounded-2xl bg-white p-6">
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-extrabold tracking-wide text-blue-700">
                                    {{ $sekolah->jenis_satuan ?? 'PAUD' }}
                                </span>
                                @if ($sekolah->status)
                                    <span class="rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                        {{ $sekolah->status }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="mt-4 text-xl font-bold leading-snug text-slate-800">
                                <a href="{{ route('paud.show', $sekolah) }}" class="hover:text-blue-700 transition-colors">
                                    {{ $sekolah->nama }}
                                </a>
                            </h3>

                            <div class="mt-3 flex items-start gap-2.5 text-sm leading-relaxed text-slate-500">
                                <i class="fa-solid fa-location-dot mt-1 text-slate-400"></i>
                                <p>
                                    {{ $sekolah->alamat ?: 'Alamat belum tersedia' }}
                                    @if ($sekolah->kelurahan), {{ $sekolah->kelurahan }} @endif
                                    @if ($sekolah->kecamatan), Kec. {{ $sekolah->kecamatan->nama }} @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('paud.show', $sekolah) }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                Lihat Profil Lengkap <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-10">
                {{ $pauds->links() }}
            </div>
        @else
            {{-- JIKA DATA TIDAK DITEMUKAN --}}
            <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-white px-5 py-20 text-center shadow-sm">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-4xl text-slate-300">
                    <i class="fa-solid fa-school"></i>
                </div>
                <h3 class="mt-6 text-xl font-bold text-slate-800">Sekolah tidak ditemukan</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                    Coba ganti kata kunci atau pilih kecamatan dan jenis sekolah lainnya untuk menemukan PAUD yang sesuai.
                </p>
                <a href="{{ route('paud.index') }}" class="mt-6 inline-block rounded-xl bg-blue-700 px-6 py-3 font-bold text-white shadow-md hover:bg-blue-800 transition-colors">
                    Tampilkan Semua Sekolah
                </a>
            </div>
        @endif
    </div>
</div>
@endsection