@extends('layouts.public')

@section('judul', 'Peta Sebaran - PAUD Balikpapan')

@push('styles')
<style>
    #map { height: calc(100vh - 64px); width: 100%; z-index: 1; transition: filter 0.5s ease; }
    .maplibregl-popup-content { padding: 0 !important; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .maplibregl-popup-close-button { font-size: 18px; padding: 4px 8px; color: #64748b; }

    /* === EFEK PETA VINTAGE (JADUL) === */
    .map-vintage #map { filter: sepia(0.7) hue-rotate(5deg) saturate(0.8) contrast(0.9) brightness(1.05); }
    .map-vintage::after {
        content: ""; position: fixed; top: 60px; left: 0; right: 0; bottom: 0;
        pointer-events: none; background-image: url('https://www.transparenttextures.com/patterns/aged-paper.png');
        mix-blend-mode: multiply; z-index: 5; opacity: 0.6;
    }
</style>
@endpush

@section('isi')
    <!-- Panel Filter Melayang -->
    <div class="absolute top-4 left-4 z-10 w-[22rem] bg-white/95 backdrop-blur-md rounded-xl shadow-2xl border border-slate-200 p-5 max-h-[calc(100vh-7rem)] flex flex-col overflow-y-auto">
        <h2 class="font-bold text-lg mb-4 text-sky-800 border-b border-slate-100 pb-3">
            <i class="fa-solid fa-map-location-dot mr-2"></i> Pencarian PAUD
        </h2>
        
        <div class="space-y-4 mb-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Nama / Alamat</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </div>
                    <input type="text" id="pencarian" class="w-full border border-slate-300 rounded-lg p-2.5 pl-10 text-sm focus:ring-sky-500 focus:border-sky-500 bg-white" placeholder="Ketik nama sekolah...">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kecamatan</label>
                <select id="filter-kecamatan" class="w-full border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-sky-500 focus:border-sky-500 bg-white">
                    <option value="semua">Semua Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Jenis Layanan</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-700 hover:text-sky-700">
                        <input type="checkbox" class="filter-jenis w-4 h-4 text-sky-600 rounded border-slate-300 focus:ring-sky-500" value="KB" checked> KB
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-700 hover:text-sky-700">
                        <input type="checkbox" class="filter-jenis w-4 h-4 text-sky-600 rounded border-slate-300 focus:ring-sky-500" value="TK" checked> TK
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-700 hover:text-sky-700">
                        <input type="checkbox" class="filter-jenis w-4 h-4 text-sky-600 rounded border-slate-300 focus:ring-sky-500" value="TPA" checked> TPA
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-700 hover:text-sky-700">
                        <input type="checkbox" class="filter-jenis w-4 h-4 text-sky-600 rounded border-slate-300 focus:ring-sky-500" value="SPS" checked> SPS
                    </label>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 mt-2">Tema Peta</label>
                <select id="map-style" class="w-full border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-sky-500 focus:border-sky-500 bg-white">
                    <option value="liberty" selected>Liberty (Berwarna)</option>
                    <option value="bright">Bright (Terang)</option>
                    <option value="positron">Positron (Minimalis)</option>
                    <option value="vintage">Vintage (Peta Jadul)</option>
                </select>
            </div>
        </div>

        <div class="mt-auto pt-4 border-t border-slate-100 text-xs">
            <p class="font-semibold text-slate-500 mb-2 uppercase tracking-wider">Legenda Warna</p>
            <div class="grid grid-cols-2 gap-y-2 mb-3">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm"></span> KB</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500 shadow-sm"></span> TK</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm"></span> TPA</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500 shadow-sm"></span> SPS</div>
            </div>
            <p class="font-semibold text-slate-500 mb-2 uppercase tracking-wider mt-3">Warna Gelembung Kecamatan</p>
            <div class="grid grid-cols-2 gap-y-1 text-[10px]">
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Bpp. Selatan</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Bpp. Kota</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Bpp. Utara</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Bpp. Tengah</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Bpp. Timur</div>
                <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Bpp. Barat</div>
            </div>
        </div>
        
        <div class="mt-4 bg-sky-50 text-sky-800 text-sm font-semibold p-3 rounded-lg text-center">
            Menampilkan <span id="jumlah-tampil" class="font-bold text-lg">0</span> PAUD
        </div>
    </div>

    <div class="absolute top-4 right-16 z-10">
        <a href="{{ route('beranda') }}" class="bg-white text-slate-700 font-bold px-4 py-2.5 rounded-lg shadow-lg border border-slate-200 hover:bg-slate-50 transition-colors inline-flex items-center gap-2 text-sm">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
    </div>

    <div id="map"></div>
@endsection

@push('scripts')
<script>
    const dataMentah = @json($pauds);
    const semuaPaud = [];
    
    dataMentah.forEach(p => {
        if (!p.latitude || !p.longitude) return;
        const lng = parseFloat(p.longitude);
        const lat = parseFloat(p.latitude);
        
        if (!isNaN(lng) && !isNaN(lat) && lng !== 0 && lat !== 0) {
            semuaPaud.push({
                id: p.id,
                kecamatan_id: String(p.kecamatan_id),
                kecamatan_nama: p.kecamatan ? p.kecamatan.nama : 'Lainnya',
                nama: p.nama,
                alamat: p.alamat || '-',
                jenis: p.jenis_satuan || 'Lainnya',
                slug: p.slug,
                lng: lng,
                lat: lat
            });
        }
    });

    let map;
    let currentStyleUrl = 'https://tiles.openfreemap.org/styles/liberty';
    let currentZoom = 11;
    let currentCenter = [116.8500, -1.2500];

    const matchColorKec = [
        'match', ['get', 'nama'],
        'Balikpapan Selatan', '#ef4444', // Merah
        'Balikpapan Kota', '#3b82f6',    // Biru
        'Balikpapan Utara', '#10b981',   // Hijau
        'Balikpapan Tengah', '#eab308',  // Kuning
        'Balikpapan Timur', '#a855f7',   // Ungu
        'Balikpapan Barat', '#f97316',   // Oranye
        '#0ea5e9'
    ];

    function initMap() {
        if (map) map.remove();

        map = new maplibregl.Map({
            container: 'map',
            style: currentStyleUrl,
            center: currentCenter, 
            zoom: currentZoom 
        });

        map.addControl(new maplibregl.NavigationControl({ showCompass: false }), 'top-right');

        map.on('moveend', () => {
            currentZoom = map.getZoom();
            currentCenter = map.getCenter().toArray();
        });

        map.on('load', () => {
            initMapLayers();
            updateMapData();
        });

        map.on('click', 'kec-circles', (e) => {
            map.flyTo({ center: e.features[0].geometry.coordinates, zoom: 13.5, duration: 1500 });
        });

        map.on('click', 'paud-points', (e) => {
            const coordinates = e.features[0].geometry.coordinates.slice();
            const props = e.features[0].properties;
            let warnaBadge = 'bg-slate-500';
            if (props.jenis === 'KB') warnaBadge = 'bg-blue-500';
            if (props.jenis === 'TK') warnaBadge = 'bg-red-500';
            if (props.jenis === 'TPA') warnaBadge = 'bg-emerald-500';
            if (props.jenis === 'SPS') warnaBadge = 'bg-purple-500';

            new maplibregl.Popup({ offset: 15, maxWidth: '300px' })
                .setLngLat(coordinates)
                .setHTML(`
                    <div class="font-sans">
                        <div class="p-4 bg-white">
                            <div class="flex justify-between items-start mb-1 gap-2">
                                <h3 class="font-bold text-slate-800 text-[15px] leading-tight m-0">${props.nama}</h3>
                                <span class="${warnaBadge} text-white text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider shrink-0">${props.jenis}</span>
                            </div>
                            <p class="text-xs text-slate-500 m-0 mt-2 line-clamp-2"><i class="fa-solid fa-location-dot mr-1 text-slate-400"></i> ${props.alamat}</p>
                        </div>
                        <div class="flex border-t border-slate-100 bg-slate-50">
                            <a href="/paud/${props.slug}" class="flex-1 text-center text-sky-700 text-xs py-2.5 font-bold hover:bg-sky-100 border-r border-slate-200">Detail</a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${coordinates[1]},${coordinates[0]}" target="_blank" class="flex-1 text-center text-emerald-700 text-xs py-2.5 font-bold hover:bg-emerald-100">Rute</a>
                        </div>
                    </div>
                `)
                .addTo(map);
        });

        map.on('mouseenter', 'kec-circles', () => { map.getCanvas().style.cursor = 'pointer'; });
        map.on('mouseleave', 'kec-circles', () => { map.getCanvas().style.cursor = ''; });
        map.on('mouseenter', 'paud-points', () => { map.getCanvas().style.cursor = 'pointer'; });
        map.on('mouseleave', 'paud-points', () => { map.getCanvas().style.cursor = ''; });
    }

    function initMapLayers() {
        if (map.getSource('source-paud')) return;

        map.addSource('source-paud', { type: 'geojson', data: { type: 'FeatureCollection', features: [] } });
        map.addSource('source-kec', { type: 'geojson', data: { type: 'FeatureCollection', features: [] } });

        // LAYER 1: Gelembung Kecamatan (Estetik, No Garis)
        map.addLayer({
            id: 'kec-circles',
            type: 'circle',
            source: 'source-kec',
            maxzoom: 12, 
            paint: {
                'circle-color': matchColorKec,
                'circle-radius': ['interpolate', ['linear'], ['get', 'total'], 1, 22, 50, 35, 100, 50],
                'circle-opacity': 0.9,
                'circle-stroke-width': 5,
                'circle-stroke-color': '#ffffff',
                'circle-blur': 0.1, // Efek sedikit halus
                'circle-translate': [0, -2] // Sedikit naik agar seperti 3D
            }
        });

        map.addLayer({
            id: 'kec-count',
            type: 'symbol',
            source: 'source-kec',
            maxzoom: 12,
            layout: { 'text-field': '{total}', 'text-font': ['Noto Sans Bold'], 'text-size': 18 },
            paint: { 'text-color': '#ffffff', 'text-halo-color': 'rgba(0,0,0,0.2)', 'text-halo-width': 1 }
        });

        map.addLayer({
            id: 'kec-label',
            type: 'symbol',
            source: 'source-kec',
            maxzoom: 12,
            layout: {
                'text-field': '{nama}', 'text-font': ['Noto Sans Bold'], 'text-size': 13,
                'text-offset': [0, 3.5], 'text-anchor': 'top'
            },
            paint: { 'text-color': '#1e293b', 'text-halo-color': '#ffffff', 'text-halo-width': 3 }
        });

        // LAYER 2: Titik PAUD (Warna-warni)
        map.addLayer({
            id: 'paud-points',
            type: 'circle',
            source: 'source-paud',
            minzoom: 12, 
            paint: {
                'circle-color': ['match', ['get', 'jenis'], 'KB', '#3b82f6', 'TK', '#ef4444', 'TPA', '#10b981', 'SPS', '#a855f7', '#64748b'],
                'circle-radius': 8,
                'circle-stroke-width': 2.5,
                'circle-stroke-color': '#ffffff',
                'circle-opacity': 0.95
            }
        });
    }

    function updateMapData() {
        if (!map || !map.getSource('source-paud')) return;

        const keyword = document.getElementById('pencarian').value.toLowerCase();
        const selectedKec = document.getElementById('filter-kecamatan').value;
        const selectedJenis = Array.from(document.querySelectorAll('.filter-jenis:checked')).map(cb => cb.value);
        
        const filteredPaud = semuaPaud.filter(p => {
            const matchSearch = p.nama.toLowerCase().includes(keyword) || p.alamat.toLowerCase().includes(keyword);
            const matchKec = selectedKec === 'semua' || p.kecamatan_id === selectedKec;
            const matchJenis = selectedJenis.includes(p.jenis) || (selectedJenis.length === 0);
            return matchSearch && matchKec && matchJenis;
        });

        document.getElementById('jumlah-tampil').textContent = filteredPaud.length;

        const paudFeatures = filteredPaud.map(p => ({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [p.lng, p.lat] },
            properties: { nama: p.nama, alamat: p.alamat, jenis: p.jenis, slug: p.slug }
        }));

        const kelompokKec = {};
        filteredPaud.forEach(p => {
            if (!kelompokKec[p.kecamatan_id]) {
                kelompokKec[p.kecamatan_id] = { nama: p.kecamatan_nama, lats: [], lngs: [], total: 0 };
            }
            kelompokKec[p.kecamatan_id].lats.push(p.lat);
            kelompokKec[p.kecamatan_id].lngs.push(p.lng);
            kelompokKec[p.kecamatan_id].total++;
        });

        const centroids = Object.values(kelompokKec).map(k => {
            const avgLat = k.lats.reduce((a, b) => a + b, 0) / k.lats.length;
            const avgLng = k.lngs.reduce((a, b) => a + b, 0) / k.lngs.length;
            return {
                type: 'Feature',
                geometry: { type: 'Point', coordinates: [avgLng, avgLat] },
                properties: { nama: k.nama, total: k.total }
            };
        });

        map.getSource('source-paud').setData({ type: 'FeatureCollection', features: paudFeatures });
        map.getSource('source-kec').setData({ type: 'FeatureCollection', features: centroids });
    }

    initMap();

    document.getElementById('map-style').addEventListener('change', (e) => {
        const val = e.target.value;
        if (val === 'vintage') {
            document.body.classList.add('map-vintage');
            currentStyleUrl = 'https://tiles.openfreemap.org/styles/positron';
        } else {
            document.body.classList.remove('map-vintage');
            currentStyleUrl = `https://tiles.openfreemap.org/styles/${val}`;
        }
        initMap();
    });

    document.getElementById('pencarian').addEventListener('input', updateMapData);
    document.getElementById('filter-kecamatan').addEventListener('change', updateMapData);
    document.querySelectorAll('.filter-jenis').forEach(cb => cb.addEventListener('change', updateMapData));
</script>
@endpush