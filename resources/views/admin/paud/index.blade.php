@extends('layouts.admin')

@section('judul', 'Data PAUD')

@section('isi')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-slate-600">{{ number_format($daftar->total(), 0, ',', '.') }} data ditemukan.</p>
        <a href="{{ route('admin.paud.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-4 py-3 font-semibold text-white hover:bg-sky-700">
            <i class="fa-solid fa-plus"></i> Tambah PAUD
        </a>
    </div>

    {{-- Pencarian dan filter --}}
    <form method="GET" action="{{ route('admin.paud.index') }}"
          class="mb-4 grid gap-3 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <label for="q" class="mb-1 block text-sm font-medium">Cari</label>
            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Nama, NPSN, alamat, kelurahan"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
        </div>
        <div>
            <label for="kecamatan" class="mb-1 block text-sm font-medium">Kecamatan</label>
            <select id="kecamatan" name="kecamatan"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
                <option value="">Semua kecamatan</option>
                @foreach ($kecamatans as $k)
                    <option value="{{ $k->id }}" @selected(request('kecamatan') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="koordinat" class="mb-1 block text-sm font-medium">Koordinat</label>
            <select id="koordinat" name="koordinat"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
                <option value="">Semua</option>
                <option value="ada" @selected(request('koordinat') === 'ada')>Sudah ada koordinat</option>
                <option value="belum" @selected(request('koordinat') === 'belum')>Belum ada koordinat</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 rounded-xl bg-sky-600 px-4 py-3 font-semibold text-white hover:bg-sky-700">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
            </button>
            @if ($adaFilter)
                <a href="{{ route('admin.paud.index') }}" class="rounded-xl bg-slate-100 px-4 py-3 font-semibold text-slate-700 hover:bg-slate-200">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full min-w-[56rem] text-left text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Nama PAUD</th>
                    <th class="px-4 py-3">NPSN</th>
                    <th class="px-4 py-3">Kecamatan</th>
                    <th class="px-4 py-3">Kelurahan</th>
                    <th class="px-4 py-3">Koordinat</th>
                    <th class="px-4 py-3 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($daftar as $p)
                    <tr>
                        <td class="px-4 py-3">
                            @if ($p->fotoUtama)
                                <img src="{{ $p->fotoUtama->url }}" alt="Foto {{ $p->nama }}" loading="lazy"
                                     class="h-12 w-12 rounded-lg object-cover ring-1 ring-slate-200">
                            @else
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                    <i class="fa-regular fa-image"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('admin.paud.show', $p) }}" class="text-sky-700 hover:underline">{{ $p->nama }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $p->npsn ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full ring-1 ring-slate-300" style="background-color: {{ $p->kecamatan->warna }}"></span>
                                {{ $p->kecamatan->nama }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $p->kelurahan ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($p->punyaKoordinat())
                                <span class="text-emerald-700"><i class="fa-solid fa-circle-check"></i> Ada</span>
                            @else
                                <span class="text-amber-700"><i class="fa-solid fa-circle-minus"></i> Belum</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.paud.show', $p) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-2 font-medium text-slate-700 hover:bg-slate-200">
                                    <i class="fa-solid fa-eye"></i> Lihat
                                </a>
                                <a href="{{ route('admin.paud.edit', $p) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-sky-100 px-3 py-2 font-medium text-sky-800 hover:bg-sky-200">
                                    <i class="fa-solid fa-pen"></i> Ubah
                                </a>
                                <form method="POST" action="{{ route('admin.paud.destroy', $p) }}"
                                      onsubmit="return confirm(@js('Hapus PAUD ' . $p->nama . '? Semua fotonya ikut terhapus dan tidak bisa dikembalikan.'))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg bg-red-100 px-3 py-2 font-medium text-red-700 hover:bg-red-200">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                            @if ($adaFilter)
                                Tidak ada PAUD yang cocok dengan pencarian atau filter ini.
                            @else
                                Data PAUD belum tersedia. Klik "Tambah PAUD" untuk memasukkan data.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $daftar->links() }}
    </div>
@endsection