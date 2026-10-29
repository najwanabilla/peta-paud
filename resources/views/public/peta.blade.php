@extends('layouts.public')

@section('judul', 'Peta Sebaran - PAUD Balikpapan')

@push('styles')
<style>
    #map { height: calc(100vh - 64px); width: 100%; z-index: 1; transition: filter 0.5s ease; }
    .maplibregl-popup-content { padding: 0 !important; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .maplibregl-popup-close-button { font-size: 18px; padding: 4px 8px; color: #64748b; }

    /* Panel filter: selalu tampil di layar lebar, bisa dibuka-tutup di HP */
    @media (min-width: 768px) { #panel-filter { display: flex !important; } }

    /* === EFEK PETA VINTAGE (JADUL), tanpa gambar dari situs luar === */
    .map-vintage #map { filter: sepia(0.7) hue-rotate(5deg) saturate(0.8) contrast(0.9) brightness(1.05); }
    .map-vintage::after {
        content: ""; position: fixed; top: 64px; left: 0; right: 0; bottom: 0;
        pointer-events: none; z-index: 5;
        background: radial-gradient(ellipse at center, rgba(120, 90, 40, 0) 55%, rgba(120, 90, 40, 0.18) 100%);
    }
</style>
@endpush

@section('isi')
    <!-- Tombol buka filter (khusus HP) -->
    <button id="tombol-filter" type="button" aria-controls="panel-filter" aria-expanded="false"
            class="absolute left-3 top-3 z-20 flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-lg ring-1 ring-slate-200 md:hidden">
        <i class="fa-solid fa-sliders"></i> Filter
    </button>

    <!-- Panel Filter Melayang -->
    <div id="panel-filter"
         class="absolute left-3 top-16 z-10 hidden max-h-[calc(100vh-9rem)] w-[22rem] max-w-[calc(100vw-1.5rem)] flex-col overflow-y-auto rounded-xl border border-slate-200 bg-white/95 p-5 shadow-2xl backdrop-blur-md md:left-4 md:top-4 md:max-h-[calc(100vh-7rem)]">

        <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-lg font-bold text-sky-800">
                <i class="fa-solid fa-map-location-dot mr-2"></i> Pencarian PAUD
            </h2>
            <button id="tutup-panel" type="button" aria-label="Tutup filter" class="text-slate-400 hover:text-slate-700 md:hidden">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="mb-4 space-y-4">
            <div>
                <label for="pencarian" class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari Nama / Alamat</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </div>
                    <input type="text" id="pencarian" autocomplete="off"
                           class="w-full rounded-lg border border-slate-300 bg-white p-2.5 pl-10 text-sm focus:border-sky-500 focus:ring-sky-500"
                           placeholder="Ketik nama sekolah...">
                </div>
            </div>

            <div>
                <label for="filter-kecamatan" class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500">Kecamatan</label>
                <select id="filter-kecamatan"
                        class="w-full rounded-lg border border-slate-300 bg-white p-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="semua">Semua Kecamatan</option>
                    @foreach ($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <span class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Jenis Layanan</span>
                <div class="grid grid-cols-2 gap-2">
                    @foreach (['KB', 'TK', 'TPA', 'SPS'] as $jenis)
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-700 hover:text-sky-700">
                            <input type="checkbox" class="filter-jenis h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500" value="{{ $jenis }}" checked> {{ $jenis }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-slate-100 pt-2">
                <label for="map-style" class="mb-1 mt-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tema Peta</label>
                <select id="map-style"
                        class="w-full rounded-lg border border-slate-300 bg-white p-2.5 text-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="liberty" selected>Liberty (Berwarna)</option>
                    <option value="bright">Bright (Terang)</option>
                    <option value="positron">Positron (Minimalis)</option>
                    <option value="vintage">Vintage (Peta Jadul)</option>
                </select>
            </div>

            <button id="tombol-reset" type="button"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                <i class="fa-solid fa-expand"></i> Lihat seluruh Balikpapan
            </button>
        </div>

        <!-- Daftar PAUD pada kecamatan yang dipilih -->
        <div id="daftar-kecamatan" class="mb-4 hidden rounded-xl border border-sky-200 bg-sky-50/60 p-3">
            <div class="mb-2 flex items-start justify-between gap-2">
                <p id="daftar-kecamatan-judul" class="text-sm font-bold text-sky-900"></p>
                <button id="tutup-daftar" type="button" aria-label="Tutup daftar" class="text-slate-400 hover:text-slate-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <ul id="daftar-kecamatan-isi" class="max-h-60 space-y-1 overflow-y-auto"></ul>
        </div>

        <div class="border-t border-slate-100 pt-4 text-xs">
            <p class="mb-2 font-semibold uppercase tracking-wider text-slate-500">Legenda Warna</p>
            <div class="mb-3 grid grid-cols-2 gap-y-2">
                <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full shadow-sm" style="background-color:#3b82f6"></span> KB</div>
                <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full shadow-sm" style="background-color:#ef4444"></span> TK</div>
                <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full shadow-sm" style="background-color:#10b981"></span> TPA</div>
                <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full shadow-sm" style="background-color:#a855f7"></span> SPS</div>
            </div>
            <p class="mb-2 mt-3 font-semibold uppercase tracking-wider text-slate-500">Warna Gelembung Kecamatan</p>
            <div class="grid grid-cols-2 gap-y-1 text-[11px]">
                @foreach ($kecamatan as $kec)
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $kec->warna }}"></span> {{ $kec->nama }}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 rounded-lg bg-sky-50 p-3 text-center text-sm font-semibold text-sky-800">
            Menampilkan <span id="jumlah-tampil" class="text-lg font-bold">0</span> PAUD
            <p id="catatan-jumlah" class="mt-1 hidden text-xs font-normal text-slate-500"></p>
        </div>
    </div>

    <div class="absolute right-16 top-4 z-10">
        <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-lg transition-colors hover:bg-slate-50">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
    </div>

    <div id="map" role="region" aria-label="Peta sebaran PAUD Kota Balikpapan"></div>

    <!-- Atribusi peta -->
    <p class="absolute bottom-1 left-2 z-10 rounded bg-white/85 px-2 py-0.5 text-[10px] text-slate-600">
        Peta: <a href="https://openfreemap.org" target="_blank" rel="noopener" class="underline">OpenFreeMap</a>
        &middot; <a href="https://www.openmaptiles.org/" target="_blank" rel="noopener" class="underline">&copy; OpenMapTiles</a>
        &middot; Data <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener" class="underline">&copy; OpenStreetMap contributors</a>
    </p>
@endsection

@push('scripts')
<script>
(function () {
    // ===== Data dari server (hanya kolom yang dibutuhkan peta) =====
    const DATA_PAUD = @json($pauds);
    const DATA_KECAMATAN = @json($kecamatan->map(fn ($k) => ['id' => $k->id, 'nama' => $k->nama, 'warna' => $k->warna])->values());
    const TOTAL_PAUD = @json($totalPaud);
    // Tautan detail hanya aktif kalau rute halaman detail sudah ada
    const URL_DETAIL = @json(\Illuminate\Support\Facades\Route::has('paud.show') ? route('paud.show', ['paud' => '__SLUG__']) : null);

    const WARNA_JENIS = { KB: '#3b82f6', TK: '#ef4444', TPA: '#10b981', SPS: '#a855f7' };
    const WARNA_LAINNYA = '#64748b';
    const JENIS_DIKENAL = Object.keys(WARNA_JENIS);
    const PUSAT_AWAL = [116.85, -1.25];
    const ZOOM_AWAL = 11;

    const semuaPaud = DATA_PAUD.map(function (p) {
        return {
            id: Number(p.id),
            nama: String(p.nama || ''),
            alamat: p.alamat ? String(p.alamat) : '',
            jenis: p.jenis ? String(p.jenis) : '',
            slug: p.slug ? String(p.slug) : '',
            kecamatan_id: Number(p.kecamatan_id),
            lat: Number(p.lat),
            lng: Number(p.lng)
        };
    }).filter(function (p) { return Number.isFinite(p.lat) && Number.isFinite(p.lng); });

    const paudPerId = new Map(semuaPaud.map(function (p) { return [p.id, p]; }));

    // Warna gelembung kecamatan diambil dari database (sama dengan panel admin)
    const warnaKecamatan = DATA_KECAMATAN.length > 0
        ? ['match', ['get', 'kec_id']].concat(
            DATA_KECAMATAN.flatMap(function (k) { return [k.id, k.warna]; }),
            ['#0ea5e9']
          )
        : '#0ea5e9';

    let map = null;
    let gayaSaatIni = 'https://tiles.openfreemap.org/styles/liberty';
    let zoomSaatIni = ZOOM_AWAL;
    let pusatSaatIni = PUSAT_AWAL;
    let hasilFilter = semuaPaud.slice();
    let popupAktif = null;
    let kecamatanDaftarId = null;

    const elPencarian = document.getElementById('pencarian');
    const elKecamatan = document.getElementById('filter-kecamatan');
    const elPanel = document.getElementById('panel-filter');
    const elTombolFilter = document.getElementById('tombol-filter');

    function kosong() { return { type: 'FeatureCollection', features: [] }; }

    function adalahHP() { return window.innerWidth < 768; }

    function bukaPanelDiHP() {
        if (adalahHP()) {
            elPanel.classList.remove('hidden');
            elTombolFilter.setAttribute('aria-expanded', 'true');
        }
    }

    // ===== Popup (dibangun dengan DOM, bukan HTML mentah, supaya aman dari XSS) =====
    function buatPopup(p) {
        const wadah = document.createElement('div');
        wadah.className = 'font-sans';

        const isi = document.createElement('div');
        isi.className = 'bg-white p-4';

        const baris = document.createElement('div');
        baris.className = 'mb-1 flex items-start justify-between gap-2';

        const judul = document.createElement('h3');
        judul.className = 'm-0 text-[15px] font-bold leading-tight text-slate-800';
        judul.textContent = p.nama;
        baris.appendChild(judul);

        if (p.jenis) {
            const lencana = document.createElement('span');
            lencana.className = 'shrink-0 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white';
            lencana.style.backgroundColor = WARNA_JENIS[p.jenis] || WARNA_LAINNYA;
            lencana.textContent = p.jenis;
            baris.appendChild(lencana);
        }
        isi.appendChild(baris);

        const alamat = document.createElement('p');
        alamat.className = 'm-0 mt-2 text-xs text-slate-500';
        alamat.textContent = p.alamat !== '' ? p.alamat : 'Alamat belum tersedia';
        isi.appendChild(alamat);
        wadah.appendChild(isi);

        const aksi = document.createElement('div');
        aksi.className = 'flex border-t border-slate-100 bg-slate-50';

        if (URL_DETAIL && p.slug) {
            const detail = document.createElement('a');
            detail.href = URL_DETAIL.replace('__SLUG__', encodeURIComponent(p.slug));
            detail.className = 'flex-1 border-r border-slate-200 py-2.5 text-center text-xs font-bold text-sky-700 hover:bg-sky-100';
            detail.textContent = 'Detail';
            aksi.appendChild(detail);
        }

        const arah = document.createElement('a');
        arah.href = 'https://www.openstreetmap.org/directions?route=' + encodeURIComponent(';' + p.lat + ',' + p.lng);
        arah.target = '_blank';
        arah.rel = 'noopener';
        arah.className = 'flex-1 py-2.5 text-center text-xs font-bold text-emerald-700 hover:bg-emerald-100';
        arah.textContent = 'Petunjuk arah';
        aksi.appendChild(arah);

        wadah.appendChild(aksi);
        return wadah;
    }

    function bukaPopup(p) {
        if (popupAktif) popupAktif.remove();
        popupAktif = new maplibregl.Popup({ offset: 15, maxWidth: '300px' })
            .setLngLat([p.lng, p.lat])
            .setDOMContent(buatPopup(p))
            .addTo(map);
    }

    // ===== Daftar PAUD pada kecamatan yang diklik/dipilih =====
    function tutupDaftar() {
        kecamatanDaftarId = null;
        document.getElementById('daftar-kecamatan').classList.add('hidden');
    }

    function tampilkanDaftarKecamatan(kecId) {
        const kec = DATA_KECAMATAN.find(function (k) { return k.id === kecId; });
        if (!kec) return;

        kecamatanDaftarId = kecId;
        const daftar = hasilFilter.filter(function (p) { return p.kecamatan_id === kecId; });

        document.getElementById('daftar-kecamatan-judul').textContent = kec.nama + ' (' + daftar.length + ' PAUD)';

        const ul = document.getElementById('daftar-kecamatan-isi');
        ul.replaceChildren();

        if (daftar.length === 0) {
            const li = document.createElement('li');
            li.className = 'px-2 py-1 text-xs text-slate-500';
            li.textContent = 'Tidak ada PAUD yang cocok dengan filter saat ini.';
            ul.appendChild(li);
        }

        daftar.slice(0, 100).forEach(function (p) {
            const li = document.createElement('li');
            const tombol = document.createElement('button');
            tombol.type = 'button';
            tombol.className = 'flex w-full items-center gap-2 rounded-lg px-2 py-1.5 text-left text-xs text-slate-700 hover:bg-white';

            const titik = document.createElement('span');
            titik.className = 'h-2.5 w-2.5 shrink-0 rounded-full';
            titik.style.backgroundColor = WARNA_JENIS[p.jenis] || WARNA_LAINNYA;

            const nama = document.createElement('span');
            nama.className = 'min-w-0 flex-1 truncate';
            nama.textContent = p.nama;

            tombol.appendChild(titik);
            tombol.appendChild(nama);
            tombol.addEventListener('click', function () {
                map.flyTo({ center: [p.lng, p.lat], zoom: Math.max(map.getZoom(), 15.5), duration: 1000 });
                bukaPopup(p);
            });

            li.appendChild(tombol);
            ul.appendChild(li);
        });

        if (daftar.length > 100) {
            const li = document.createElement('li');
            li.className = 'px-2 py-1 text-xs text-slate-500';
            li.textContent = 'dan ' + (daftar.length - 100) + ' lainnya. Persempit dengan kolom pencarian.';
            ul.appendChild(li);
        }

        document.getElementById('daftar-kecamatan').classList.remove('hidden');
    }

    function fokusKecamatan(kecId) {
        const daftar = hasilFilter.filter(function (p) { return p.kecamatan_id === kecId; });
        if (daftar.length === 0) return;

        const batas = new maplibregl.LngLatBounds([daftar[0].lng, daftar[0].lat], [daftar[0].lng, daftar[0].lat]);
        daftar.forEach(function (p) { batas.extend([p.lng, p.lat]); });
        map.fitBounds(batas, { padding: 80, maxZoom: 15, duration: 1200 });
    }

    function resetTampilan() {
        if (popupAktif) popupAktif.remove();
        tutupDaftar();
        map.flyTo({ center: PUSAT_AWAL, zoom: ZOOM_AWAL, duration: 1200 });
    }

    // ===== Peta =====
    function initMap() {
        if (map) map.remove();

        map = new maplibregl.Map({
            container: 'map',
            style: gayaSaatIni,
            center: pusatSaatIni,
            zoom: zoomSaatIni
        });

        map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'top-right');

        map.on('moveend', function () {
            zoomSaatIni = map.getZoom();
            pusatSaatIni = map.getCenter().toArray();
        });

        map.on('load', function () {
            siapkanLapisan();
            perbaruiSumber();
            pasangInteraksi();
        });
    }

    function siapkanLapisan() {
        if (map.getSource('source-paud')) return;

        map.addSource('source-paud', { type: 'geojson', data: kosong() });
        map.addSource('source-kec', { type: 'geojson', data: kosong() });

        // Gelembung kecamatan (terlihat sampai zoom 12)
        map.addLayer({
            id: 'kec-circles',
            type: 'circle',
            source: 'source-kec',
            maxzoom: 12,
            paint: {
                'circle-color': warnaKecamatan,
                'circle-radius': ['interpolate', ['linear'], ['get', 'total'], 1, 22, 50, 35, 100, 50],
                'circle-opacity': 0.9,
                'circle-stroke-width': 5,
                'circle-stroke-color': '#ffffff',
                'circle-blur': 0.1,
                'circle-translate': [0, -2]
            }
        });

        map.addLayer({
            id: 'kec-count',
            type: 'symbol',
            source: 'source-kec',
            maxzoom: 12,
            layout: { 'text-field': ['to-string', ['get', 'total']], 'text-font': ['Noto Sans Bold'], 'text-size': 18 },
            paint: { 'text-color': '#ffffff', 'text-halo-color': 'rgba(0,0,0,0.2)', 'text-halo-width': 1 }
        });

        map.addLayer({
            id: 'kec-label',
            type: 'symbol',
            source: 'source-kec',
            maxzoom: 12,
            layout: {
                'text-field': ['get', 'nama'], 'text-font': ['Noto Sans Bold'], 'text-size': 13,
                'text-offset': [0, 3.5], 'text-anchor': 'top'
            },
            paint: { 'text-color': '#1e293b', 'text-halo-color': '#ffffff', 'text-halo-width': 3 }
        });

        // Titik PAUD (terlihat mulai zoom 12)
        map.addLayer({
            id: 'paud-points',
            type: 'circle',
            source: 'source-paud',
            minzoom: 12,
            paint: {
                'circle-color': ['match', ['get', 'jenis'], 'KB', WARNA_JENIS.KB, 'TK', WARNA_JENIS.TK, 'TPA', WARNA_JENIS.TPA, 'SPS', WARNA_JENIS.SPS, WARNA_LAINNYA],
                'circle-radius': 8,
                'circle-stroke-width': 2.5,
                'circle-stroke-color': '#ffffff',
                'circle-opacity': 0.95
            }
        });
    }

    function pasangInteraksi() {
        map.on('click', 'kec-circles', function (e) {
            const kecId = Number(e.features[0].properties.kec_id);
            tampilkanDaftarKecamatan(kecId);
            fokusKecamatan(kecId);
            bukaPanelDiHP();
        });

        map.on('click', 'paud-points', function (e) {
            const p = paudPerId.get(Number(e.features[0].properties.id));
            if (p) bukaPopup(p);
        });

        ['kec-circles', 'paud-points'].forEach(function (lapisan) {
            map.on('mouseenter', lapisan, function () { map.getCanvas().style.cursor = 'pointer'; });
            map.on('mouseleave', lapisan, function () { map.getCanvas().style.cursor = ''; });
        });
    }

    function perbaruiSumber() {
        if (!map || !map.getSource('source-paud')) return;

        const fiturPaud = hasilFilter.map(function (p) {
            return {
                type: 'Feature',
                geometry: { type: 'Point', coordinates: [p.lng, p.lat] },
                properties: { id: p.id, jenis: p.jenis }
            };
        });

        const kelompok = {};
        hasilFilter.forEach(function (p) {
            if (!kelompok[p.kecamatan_id]) {
                kelompok[p.kecamatan_id] = { lats: [], lngs: [] };
            }
            kelompok[p.kecamatan_id].lats.push(p.lat);
            kelompok[p.kecamatan_id].lngs.push(p.lng);
        });

        const fiturKecamatan = DATA_KECAMATAN.filter(function (k) { return kelompok[k.id]; }).map(function (k) {
            const g = kelompok[k.id];
            const rata = function (daftar) { return daftar.reduce(function (a, b) { return a + b; }, 0) / daftar.length; };
            return {
                type: 'Feature',
                geometry: { type: 'Point', coordinates: [rata(g.lngs), rata(g.lats)] },
                properties: { kec_id: k.id, nama: k.nama, total: g.lats.length }
            };
        });

        map.getSource('source-paud').setData({ type: 'FeatureCollection', features: fiturPaud });
        map.getSource('source-kec').setData({ type: 'FeatureCollection', features: fiturKecamatan });
    }

    function terapkanFilter() {
        const kata = elPencarian.value.trim().toLowerCase();
        const kec = elKecamatan.value;
        const jenisDipilih = Array.from(document.querySelectorAll('.filter-jenis:checked')).map(function (cb) { return cb.value; });

        hasilFilter = semuaPaud.filter(function (p) {
            const cocokKata = kata === '' || p.nama.toLowerCase().includes(kata) || p.alamat.toLowerCase().includes(kata);
            const cocokKec = kec === 'semua' || String(p.kecamatan_id) === kec;
            const dikenal = JENIS_DIKENAL.includes(p.jenis);
            const cocokJenis = jenisDipilih.length === 0
                || jenisDipilih.includes(p.jenis)
                || (!dikenal && jenisDipilih.length === JENIS_DIKENAL.length);
            return cocokKata && cocokKec && cocokJenis;
        });

        document.getElementById('jumlah-tampil').textContent = hasilFilter.length;
        perbaruiSumber();

        if (kecamatanDaftarId !== null) tampilkanDaftarKecamatan(kecamatanDaftarId);
    }

    // Catatan transparansi: PAUD yang tidak punya titik lokasi valid tidak ditampilkan
    (function () {
        const tidakTampil = TOTAL_PAUD - semuaPaud.length;
        const el = document.getElementById('catatan-jumlah');
        if (tidakTampil > 0) {
            el.textContent = tidakTampil + ' PAUD lain belum memiliki titik lokasi yang valid, jadi tidak tampil di peta.';
            el.classList.remove('hidden');
        }
    })();

    // ===== Interaksi panel =====
    elPencarian.addEventListener('input', terapkanFilter);
    document.querySelectorAll('.filter-jenis').forEach(function (cb) { cb.addEventListener('change', terapkanFilter); });

    elKecamatan.addEventListener('change', function () {
        terapkanFilter();
        const nilai = elKecamatan.value;
        if (nilai === 'semua') {
            resetTampilan();
        } else {
            const kecId = Number(nilai);
            tampilkanDaftarKecamatan(kecId);
            fokusKecamatan(kecId);
        }
    });

    document.getElementById('tombol-reset').addEventListener('click', function () {
        elKecamatan.value = 'semua';
        terapkanFilter();
        resetTampilan();
    });

    document.getElementById('tutup-daftar').addEventListener('click', tutupDaftar);

    document.getElementById('map-style').addEventListener('change', function (e) {
        const nilai = e.target.value;
        if (nilai === 'vintage') {
            document.body.classList.add('map-vintage');
            gayaSaatIni = 'https://tiles.openfreemap.org/styles/positron';
        } else {
            document.body.classList.remove('map-vintage');
            gayaSaatIni = 'https://tiles.openfreemap.org/styles/' + nilai;
        }
        initMap();
    });

    elTombolFilter.addEventListener('click', function () {
        const tersembunyi = elPanel.classList.toggle('hidden');
        elTombolFilter.setAttribute('aria-expanded', tersembunyi ? 'false' : 'true');
    });

    document.getElementById('tutup-panel').addEventListener('click', function () {
        elPanel.classList.add('hidden');
        elTombolFilter.setAttribute('aria-expanded', 'false');
    });

    initMap();
    terapkanFilter();
})();
</script>
@endpush