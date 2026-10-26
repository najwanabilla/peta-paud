@extends('layouts.admin')

@section('judul', $paud->exists ? 'Ubah PAUD' : 'Tambah PAUD')

@section('isi')
    @php
        $gaya = 'w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200';
    @endphp

    <form method="POST"
          action="{{ $paud->exists ? route('admin.paud.update', $paud) : route('admin.paud.store') }}"
          enctype="multipart/form-data"
          data-loading
          class="max-w-4xl space-y-6">
        @csrf
        @if ($paud->exists)
            @method('PUT')
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800" role="alert">
                <p class="font-semibold">Data belum bisa disimpan. Periksa isian yang bertanda merah di bawah ini.</p>
            </div>
        @endif

        {{-- Identitas --}}
        <section class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold">Identitas</h2>

            <div>
                <label for="nama" class="mb-1 block font-medium">Nama PAUD <span class="text-red-600">*</span></label>
                <input id="nama" name="nama" type="text" value="{{ old('nama', $paud->nama) }}" required maxlength="255" class="{{ $gaya }}">
                @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="npsn" class="mb-1 block font-medium">NPSN</label>
                    <input id="npsn" name="npsn" type="text" inputmode="numeric" maxlength="8" value="{{ old('npsn', $paud->npsn) }}" class="{{ $gaya }}">
                    <p class="mt-1 text-sm text-slate-500">8 angka. Kosongkan kalau belum ada.</p>
                    @error('npsn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="jenis_satuan" class="mb-1 block font-medium">Jenis satuan</label>
                    <select id="jenis_satuan" name="jenis_satuan" class="{{ $gaya }} bg-white">
                        <option value="">- Pilih -</option>
                        @foreach ($jenisSatuan as $kode => $label)
                            <option value="{{ $kode }}" @selected(old('jenis_satuan', $paud->jenis_satuan) === $kode)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_satuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="status" class="mb-1 block font-medium">Status</label>
                    <select id="status" name="status" class="{{ $gaya }} bg-white">
                        <option value="">- Pilih -</option>
                        @foreach ($statusList as $s)
                            <option value="{{ $s }}" @selected(old('status', $paud->status) === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Alamat --}}
        <section class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold">Alamat</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="kecamatan_id" class="mb-1 block font-medium">Kecamatan <span class="text-red-600">*</span></label>
                    <select id="kecamatan_id" name="kecamatan_id" required class="{{ $gaya }} bg-white">
                        <option value="">- Pilih kecamatan -</option>
                        @foreach ($kecamatans as $k)
                            <option value="{{ $k->id }}" @selected(old('kecamatan_id', $paud->kecamatan_id) == $k->id)>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('kecamatan_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="kelurahan" class="mb-1 block font-medium">Kelurahan</label>
                    <input id="kelurahan" name="kelurahan" type="text" maxlength="100" value="{{ old('kelurahan', $paud->kelurahan) }}" class="{{ $gaya }}">
                    @error('kelurahan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="alamat" class="mb-1 block font-medium">Alamat lengkap</label>
                <textarea id="alamat" name="alamat" rows="3" maxlength="1000" class="{{ $gaya }}">{{ old('alamat', $paud->alamat) }}</textarea>
                @error('alamat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:w-1/3">
                <label for="kode_pos" class="mb-1 block font-medium">Kode pos</label>
                <input id="kode_pos" name="kode_pos" type="text" inputmode="numeric" maxlength="5" value="{{ old('kode_pos', $paud->kode_pos) }}" class="{{ $gaya }}">
                @error('kode_pos') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Koordinat --}}
        <section class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold">Lokasi di peta</h2>
            <p class="text-sm text-slate-600">
                Klik peta untuk menentukan lokasi, atau geser penandanya. Bisa juga mengetik latitude dan longitude langsung.
                Kosongkan kalau lokasi belum diketahui. Jangan menebak koordinat.
            </p>

            <div id="peta-pemilih" class="relative z-0 h-80 w-full overflow-hidden rounded-xl ring-1 ring-slate-300"></div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="latitude" class="mb-1 block font-medium">Latitude</label>
                    <input id="latitude" name="latitude" type="text" inputmode="decimal" placeholder="contoh: -1.2654000"
                           value="{{ old('latitude', $paud->latitude) }}" class="{{ $gaya }}">
                    @error('latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="longitude" class="mb-1 block font-medium">Longitude</label>
                    <input id="longitude" name="longitude" type="text" inputmode="decimal" placeholder="contoh: 116.8312000"
                           value="{{ old('longitude', $paud->longitude) }}" class="{{ $gaya }}">
                    @error('longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end">
                    <button type="button" id="hapus-koordinat"
                            class="w-full rounded-xl bg-slate-100 px-4 py-3 font-semibold text-slate-700 hover:bg-slate-200">
                        <i class="fa-solid fa-eraser mr-1"></i> Hapus koordinat
                    </button>
                </div>
            </div>
        </section>

        {{-- Kontak dan deskripsi --}}
        <section class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold">Kontak dan deskripsi</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="kepala_sekolah" class="mb-1 block font-medium">Kepala sekolah</label>
                    <input id="kepala_sekolah" name="kepala_sekolah" type="text" maxlength="150" value="{{ old('kepala_sekolah', $paud->kepala_sekolah) }}" class="{{ $gaya }}">
                    @error('kepala_sekolah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="telepon" class="mb-1 block font-medium">Telepon</label>
                    <input id="telepon" name="telepon" type="text" inputmode="tel" maxlength="30" value="{{ old('telepon', $paud->telepon) }}" class="{{ $gaya }}">
                    @error('telepon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="deskripsi" class="mb-1 block font-medium">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="5000" class="{{ $gaya }}">{{ old('deskripsi', $paud->deskripsi) }}</textarea>
                @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </section>

        {{-- Foto --}}
        <section class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-semibold">Foto</h2>

            @if ($paud->exists && $paud->fotos->isNotEmpty())
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5">
                    @foreach ($paud->fotos as $foto)
                        <figure class="overflow-hidden rounded-xl ring-1 ring-slate-200">
                            <div class="relative">
                                <img src="{{ $foto->url }}" alt="Foto {{ $paud->nama }}" loading="lazy" class="aspect-square w-full object-cover">
                                @if ($foto->is_primary)
                                    <span class="absolute left-2 top-2 rounded-full bg-sky-600 px-2 py-1 text-xs font-semibold text-white">Foto utama</span>
                                @endif
                            </div>
                            <div class="flex flex-col gap-1 p-2">
                                @unless ($foto->is_primary)
                                    <button type="submit" form="utama-foto-{{ $foto->id }}"
                                            class="rounded-lg bg-sky-100 px-2 py-2 text-xs font-medium text-sky-800 hover:bg-sky-200">
                                        Jadikan utama
                                    </button>
                                @endunless
                                <button type="submit" form="hapus-foto-{{ $foto->id }}"
                                        class="rounded-lg bg-red-100 px-2 py-2 text-xs font-medium text-red-700 hover:bg-red-200">
                                    Hapus foto
                                </button>
                            </div>
                        </figure>
                    @endforeach
                </div>
                <p class="text-sm text-amber-700">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Tombol "Jadikan utama" dan "Hapus foto" langsung dijalankan. Simpan dulu perubahan isian lain sebelum menekannya.
                </p>
            @endif

            <div>
                <label for="fotos" class="mb-1 block font-medium">{{ $paud->exists ? 'Tambah foto baru' : 'Unggah foto' }}</label>
                <input id="fotos" name="fotos[]" type="file" multiple accept="image/jpeg,image/png,image/webp"
                       class="block w-full rounded-xl border border-slate-300 bg-white p-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-sky-100 file:px-4 file:py-2 file:font-medium file:text-sky-800">
                <p class="mt-1 text-sm text-slate-500">
                    Format JPG, PNG, atau WEBP. Maksimal 2 MB per foto dan {{ $maksFoto }} foto per PAUD.
                    Foto pertama otomatis menjadi foto utama. Foto yang diunggah baru disimpan saat tombol Simpan ditekan.
                </p>
                @error('fotos') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @foreach ($errors->get('fotos.*') as $pesanFoto)
                    @foreach ($pesanFoto as $pesan)
                        <p class="mt-1 text-sm text-red-600">{{ $pesan }}</p>
                    @endforeach
                @endforeach
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-xl bg-sky-600 px-6 py-3 font-semibold text-white hover:bg-sky-700 disabled:opacity-60">
                Simpan
            </button>
            <a href="{{ $paud->exists ? route('admin.paud.show', $paud) : route('admin.paud.index') }}"
               class="rounded-xl bg-slate-100 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-200">
                Batal
            </a>
        </div>
    </form>

    {{-- Form tersembunyi untuk aksi foto (harus di luar form utama) --}}
    @if ($paud->exists)
        @foreach ($paud->fotos as $foto)
            <form id="utama-foto-{{ $foto->id }}" method="POST" action="{{ route('admin.paud.foto.utama', [$paud, $foto]) }}" class="hidden">
                @csrf
                @method('PATCH')
            </form>
            <form id="hapus-foto-{{ $foto->id }}" method="POST" action="{{ route('admin.paud.foto.destroy', [$paud, $foto]) }}"
                  onsubmit="return confirm('Hapus foto ini? Tindakan ini tidak bisa dibatalkan.')" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
@endsection