@extends('layouts.admin')

@section('judul', 'Detail PAUD')

@section('isi')
    @php
        // Nilai kosong ditampilkan jujur sebagai "Belum diisi"
        $nilai = fn ($v) => filled($v) ? e($v) : '<span class="text-slate-400">Belum diisi</span>';
    @endphp

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.paud.index') }}" class="rounded-xl bg-slate-100 px-4 py-3 font-semibold text-slate-700 hover:bg-slate-200">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
        <a href="{{ route('admin.paud.edit', $paud) }}" class="rounded-xl bg-sky-600 px-4 py-3 font-semibold text-white hover:bg-sky-700">
            <i class="fa-solid fa-pen mr-1"></i> Ubah
        </a>
        <form method="POST" action="{{ route('admin.paud.destroy', $paud) }}"
              onsubmit="return confirm(@js('Hapus PAUD ' . $paud->nama . '? Semua fotonya ikut terhapus dan tidak bisa dikembalikan.'))">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-xl bg-red-100 px-4 py-3 font-semibold text-red-700 hover:bg-red-200">
                <i class="fa-solid fa-trash mr-1"></i> Hapus
            </button>
        </form>
    </div>

    <div class="space-y-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-xl font-bold">{{ $paud->nama }}</h2>

            <dl class="grid gap-x-8 gap-y-4 sm:grid-cols-2">
                <div><dt class="text-sm text-slate-500">NPSN</dt><dd class="font-medium">{!! $nilai($paud->npsn) !!}</dd></div>
                <div><dt class="text-sm text-slate-500">Jenis satuan</dt><dd class="font-medium">{!! $nilai(\App\Models\Paud::JENIS_SATUAN[$paud->jenis_satuan] ?? $paud->jenis_satuan) !!}</dd></div>
                <div><dt class="text-sm text-slate-500">Status</dt><dd class="font-medium">{!! $nilai($paud->status) !!}</dd></div>
                <div>
                    <dt class="text-sm text-slate-500">Kecamatan</dt>
                    <dd class="inline-flex items-center gap-2 font-medium">
                        <span class="h-3 w-3 rounded-full ring-1 ring-slate-300" style="background-color: {{ $paud->kecamatan->warna }}"></span>
                        {{ $paud->kecamatan->nama }}
                    </dd>
                </div>
                <div><dt class="text-sm text-slate-500">Kelurahan</dt><dd class="font-medium">{!! $nilai($paud->kelurahan) !!}</dd></div>
                <div><dt class="text-sm text-slate-500">Kode pos</dt><dd class="font-medium">{!! $nilai($paud->kode_pos) !!}</dd></div>
                <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Alamat</dt><dd class="font-medium">{!! $nilai($paud->alamat) !!}</dd></div>
                <div><dt class="text-sm text-slate-500">Kepala sekolah</dt><dd class="font-medium">{!! $nilai($paud->kepala_sekolah) !!}</dd></div>
                <div><dt class="text-sm text-slate-500">Telepon</dt><dd class="font-medium">{!! $nilai($paud->telepon) !!}</dd></div>
                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Koordinat</dt>
                    <dd class="font-medium">
                        @if ($paud->punyaKoordinat())
                            {{ $paud->latitude }}, {{ $paud->longitude }}
                            <a href="https://www.openstreetmap.org/?mlat={{ $paud->latitude }}&mlon={{ $paud->longitude }}#map=18/{{ $paud->latitude }}/{{ $paud->longitude }}"
                               target="_blank" rel="noopener" class="ml-2 text-sm text-sky-700 hover:underline">
                                Buka di OpenStreetMap <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @else
                            <span class="text-slate-400">Belum diisi</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Deskripsi</dt>
                    <dd class="whitespace-pre-line font-medium">{!! $nilai($paud->deskripsi) !!}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-4 text-lg font-semibold">Foto ({{ $paud->fotos->count() }})</h2>

            @if ($paud->fotos->isEmpty())
                <p class="text-slate-500">Belum ada foto untuk PAUD ini.</p>
            @else
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5">
                    @foreach ($paud->fotos as $foto)
                        <figure class="relative overflow-hidden rounded-xl ring-1 ring-slate-200">
                            <img src="{{ $foto->url }}" alt="Foto {{ $paud->nama }}" loading="lazy" class="aspect-square w-full object-cover">
                            @if ($foto->is_primary)
                                <figcaption class="absolute left-2 top-2 rounded-full bg-sky-600 px-2 py-1 text-xs font-semibold text-white">Foto utama</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @endif
        </section>

        <p class="text-sm text-slate-500">
            Ditambahkan {{ $paud->created_at->translatedFormat('d F Y, H:i') }} WITA ·
            Diperbarui {{ $paud->updated_at->translatedFormat('d F Y, H:i') }} WITA
        </p>
    </div>
@endsection