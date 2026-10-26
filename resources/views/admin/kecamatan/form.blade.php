@extends('layouts.admin')

@section('judul', $kecamatan->exists ? 'Ubah Kecamatan' : 'Tambah Kecamatan')

@section('isi')
    <form method="POST"
          action="{{ $kecamatan->exists ? route('admin.kecamatan.update', $kecamatan) : route('admin.kecamatan.store') }}"
          data-loading
          class="max-w-3xl space-y-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @if ($kecamatan->exists)
            @method('PUT')
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800" role="alert">
                <p class="font-semibold">Data belum bisa disimpan. Periksa isian di bawah ini.</p>
            </div>
        @endif

        <div>
            <label for="nama" class="mb-1 block font-medium">Nama kecamatan <span class="text-red-600">*</span></label>
            <input id="nama" name="nama" type="text" value="{{ old('nama', $kecamatan->nama) }}" required maxlength="100"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">
            @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            @if ($kecamatan->exists)
                {{-- Saat ubah: warna hanya ditampilkan, tidak bisa diganti --}}
                <p class="mb-1 font-medium">Warna di peta</p>
                <div class="flex items-center gap-3">
                    <span class="h-10 w-10 rounded-lg ring-1 ring-slate-300" style="background-color: {{ $kecamatan->warna }}"></span>
                    <span class="text-slate-700">{{ $kecamatan->warna }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">Warna ditetapkan saat kecamatan dibuat dan tidak bisa diubah.</p>
                @error('warna') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            @else
                {{-- Saat tambah: warna dipilih sekali --}}
                <label for="warna" class="mb-1 block font-medium">Warna di peta <span class="text-red-600">*</span></label>
                <input id="warna" name="warna" type="color" value="{{ old('warna', $kecamatan->warna) }}"
                       class="h-12 w-24 cursor-pointer rounded-lg border border-slate-300 bg-white p-1">
                <p class="mt-1 text-sm text-slate-500">
                    Pilih warna yang jelas tetapi tidak menutupi jalan di peta. Setelah disimpan, warna tidak bisa diubah lagi.
                </p>
                @error('warna') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            @endif
        </div>

        <div>
            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" class="h-5 w-5 rounded border-slate-300"
                       {{ old('is_active', $kecamatan->is_active) ? 'checked' : '' }}>
                <span class="font-medium">Aktif (tampil di peta publik)</span>
            </label>
        </div>

        <div>
            <label for="geojson" class="mb-1 block font-medium">Data batas wilayah (GeoJSON)</label>
            <textarea id="geojson" name="geojson" rows="10"
                      class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-xs focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200">{{ old('geojson', $kecamatan->geojson) }}</textarea>
            <p class="mt-1 text-sm text-slate-500">
                Boleh dikosongkan dulu. Isi setelah data batas wilayah didapat dari sumber yang legal.
                Format harus WGS84 (EPSG:4326) dengan urutan [longitude, latitude].
            </p>
            @error('geojson') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-xl bg-sky-600 px-6 py-3 font-semibold text-white hover:bg-sky-700 disabled:opacity-60">
                Simpan
            </button>
            <a href="{{ route('admin.kecamatan.index') }}" class="rounded-xl bg-slate-100 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-200">
                Batal
            </a>
        </div>
    </form>
@endsection