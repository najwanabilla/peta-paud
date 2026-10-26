@extends('layouts.admin')

@section('judul', 'Dashboard')

@section('isi')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500"><i class="fa-solid fa-school mr-1"></i> Total PAUD</p>
            <p class="mt-1 text-3xl font-bold text-sky-700">{{ number_format($totalPaud, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500"><i class="fa-solid fa-map-location-dot mr-1"></i> Kecamatan aktif</p>
            <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $kecamatanAktif }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm text-slate-500"><i class="fa-solid fa-location-dot mr-1"></i> PAUD belum punya koordinat</p>
            <p class="mt-1 text-3xl font-bold text-amber-600">{{ number_format($tanpaKoordinat, 0, ',', '.') }}</p>
        </div>
    </div>

    @if ($totalPaud === 0)
        <div class="mt-6 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sky-900">
            Data PAUD belum tersedia. Data akan muncul di sini setelah diimpor dari Excel atau ditambahkan manual.
        </div>
    @endif

    <section class="mt-8 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <h2 class="mb-4 text-lg font-semibold">Jumlah PAUD per kecamatan</h2>

        @php $maks = max($perKecamatan->max('pauds_count') ?? 0, 1); @endphp

        <ul class="space-y-4">
            @forelse ($perKecamatan as $k)
                <li>
                    <div class="mb-1 flex items-center justify-between gap-2 text-sm">
                        <span class="flex items-center gap-2 font-medium">
                            <span class="h-3 w-3 rounded-full ring-1 ring-slate-300" style="background-color: {{ $k->warna }}"></span>
                            {{ $k->nama }}
                            @unless ($k->is_active)
                                <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
                            @endunless
                        </span>
                        <span class="text-slate-600">{{ $k->pauds_count }} PAUD</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-3 rounded-full" style="width: {{ ($k->pauds_count / $maks) * 100 }}%; background-color: {{ $k->warna }}"></div>
                    </div>
                </li>
            @empty
                <li class="text-slate-500">Belum ada data kecamatan.</li>
            @endforelse
        </ul>
    </section>
@endsection