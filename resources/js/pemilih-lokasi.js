import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Perbaikan agar ikon penanda Leaflet tampil saat dibundel oleh Vite
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

const PUSAT_BALIKPAPAN = [-1.2654, 116.8312];

const wadahPeta = document.getElementById('peta-pemilih');
const inputLat = document.getElementById('latitude');
const inputLng = document.getElementById('longitude');
const tombolHapus = document.getElementById('hapus-koordinat');

function angkaValid(nilai, min, max) {
    const angka = parseFloat(String(nilai).replace(',', '.'));
    return Number.isFinite(angka) && angka >= min && angka <= max ? angka : null;
}

if (wadahPeta && inputLat && inputLng) {
    const latAwal = angkaValid(inputLat.value, -90, 90);
    const lngAwal = angkaValid(inputLng.value, -180, 180);
    const adaAwal = latAwal !== null && lngAwal !== null;

    const peta = L.map(wadahPeta).setView(
        adaAwal ? [latAwal, lngAwal] : PUSAT_BALIKPAPAN,
        adaAwal ? 17 : 12
    );

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(peta);

    let penanda = null;

    function isiInput(lat, lng) {
        inputLat.value = lat.toFixed(7);
        inputLng.value = lng.toFixed(7);
    }

    function letakkanPenanda(lat, lng) {
        if (penanda) {
            penanda.setLatLng([lat, lng]);
            return;
        }

        penanda = L.marker([lat, lng], { draggable: true }).addTo(peta);

        penanda.on('dragend', function () {
            const posisi = penanda.getLatLng();
            isiInput(posisi.lat, posisi.lng);
        });
    }

    if (adaAwal) {
        letakkanPenanda(latAwal, lngAwal);
    }

    // Klik peta = tentukan lokasi
    peta.on('click', function (e) {
        letakkanPenanda(e.latlng.lat, e.latlng.lng);
        isiInput(e.latlng.lat, e.latlng.lng);
    });

    // Mengetik angka manual = penanda ikut pindah
    function sinkronDariInput() {
        const lat = angkaValid(inputLat.value, -90, 90);
        const lng = angkaValid(inputLng.value, -180, 180);

        if (lat !== null && lng !== null) {
            letakkanPenanda(lat, lng);
            peta.setView([lat, lng], Math.max(peta.getZoom(), 16));
        }
    }

    inputLat.addEventListener('change', sinkronDariInput);
    inputLng.addEventListener('change', sinkronDariInput);

    // Tombol hapus koordinat
    if (tombolHapus) {
        tombolHapus.addEventListener('click', function () {
            inputLat.value = '';
            inputLng.value = '';

            if (penanda) {
                peta.removeLayer(penanda);
                penanda = null;
            }
        });
    }
}