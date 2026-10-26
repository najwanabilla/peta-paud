import '@fortawesome/fontawesome-free/css/all.min.css';

// Pemilih lokasi (Leaflet) hanya dimuat di halaman yang memakainya
if (document.getElementById('peta-pemilih')) {
    import('./pemilih-lokasi.js');
}