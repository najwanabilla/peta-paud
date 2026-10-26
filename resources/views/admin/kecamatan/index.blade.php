@extends('layouts.admin')

@section('judul', 'Kecamatan')

@section('isi')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <p class="text-slate-600">Kelola nama, warna peta, dan status tiap kecamatan.</p>
        <a href="{{ route('admin.kecamatan.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-4 py-3 font-semibold text-white hover:bg-sky-700">
            <i class="fa-solid fa-plus"></i> Tambah Kecamatan
        </a>
    </div>

    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full min-w-[44rem] text-left text-sm">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="px-4 py-3">Kecamatan</th>
                    <th class="px-4 py-3">Warna</th>
                    <th class="px-4 py-3">Jumlah PAUD</th>
                    <th class="px-4 py-3">Batas wilayah</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($daftar as $k)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $k->nama }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full ring-1 ring-slate-300" style="background-color: {{ $k->warna }}"></span>
                                {{ $k->warna }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $k->pauds_count }}</td>
                        <td class="px-4 py-3">
                            @if ($k->geojson)
                                <span class="text-emerald-700"><i class="fa-solid fa-circle-check"></i> Sudah ada</span>
                            @else
                                <span class="text-slate-500">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($k->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-medium text-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kecamatan.edit', $k) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-sky-100 px-3 py-2 font-medium text-sky-800 hover:bg-sky-200">
                                    <i class="fa-solid fa-pen"></i> Ubah
                                </a>

                                @if ($k->pauds_count > 0)
                                    <span class="inline-flex cursor-not-allowed items-center gap-1 rounded-lg bg-slate-100 px-3 py-2 text-slate-400"
                                          title="Tidak bisa dihapus karena masih memiliki PAUD">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('admin.kecamatan.destroy', $k) }}"
                                          onsubmit="return confirm(@js('Hapus kecamatan ' . $k->nama . '? Tindakan ini tidak bisa dibatalkan.'))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-red-100 px-3 py-2 font-medium text-red-700 hover:bg-red-200">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Belum ada data kecamatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection