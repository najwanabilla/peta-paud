# Peta PAUD Balikpapan

Website untuk menampilkan persebaran dan informasi PAUD (Pendidikan Anak Usia Dini) di Kota Balikpapan, dengan peta interaktif, pencarian, halaman detail, dan panel admin.

Dibuat bertahap (10 tahap). Seluruh teknologi gratis dan open-source. Biaya hanya untuk hosting dan domain saat peluncuran.

## Teknologi

- Laravel 13 + PHP 8.3 atau lebih baru
- MySQL / MariaDB
- Blade, Tailwind CSS, JavaScript (Vite)
- Leaflet + OpenStreetMap (peta dasar)
- Font Awesome Free (ikon)
- Geoapify (routing, kunci API gratis, dipakai lewat server)

## Status pengerjaan

| Tahap | Isi | Status |
|---|---|---|
| 1 | Analisis dan perencanaan | Selesai |
| 2 | Persiapan lingkungan | Selesai |
| 3 | Database dan model | Selesai |
| 4 | Login admin, dashboard, kecamatan | Berjalan (belum teruji penuh) |
| 5 | CRUD PAUD dan foto | Kode ada (belum teruji penuh) |
| 6 | Impor Excel | Importer sudah ada di repo, belum ditinjau |
| 7 | Halaman publik | Beranda dan peta ada; daftar PAUD dan detail PAUD belum |
| 8 | Peta interaktif dan petunjuk arah | Halaman peta ada, belum ditinjau; rute Geoapify belum |
| 9 | Integrasi dan pengujian | Belum dikerjakan |
| 10 | Deployment | Belum dikerjakan |

## Cara menjalankan di komputer sendiri

Prasyarat: PHP 8.3+ (ekstensi `intl`, `zip`, `gd`, `pdo_mysql` aktif), Composer, Node.js, MySQL/MariaDB (misalnya XAMPP), Git.

```bash
git clone <alamat-repo>
cd <nama-folder>
composer install
npm install
copy .env.example .env      # di Mac/Linux: cp .env.example .env
php artisan key:generate
```

1. Buat database kosong bernama `peta_paud` (collation `utf8mb4_unicode_ci`).
2. Buka `.env`, isi `DB_USERNAME` dan `DB_PASSWORD` sesuai MySQL-mu.
3. Isi `ADMIN_EMAIL` dan `ADMIN_PASSWORD` (minimal 12 karakter) untuk akun admin pertama.
4. Isi `GEOAPIFY_API_KEY` dengan kunci milikmu sendiri (buat gratis di geoapify.com).

```bash
php artisan migrate --seed
php artisan storage:link
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

Buka `http://127.0.0.1:8000`. Login admin ada di `/masuk`, panel di `/admin`.

## Aturan proyek

- Jangan memakai layanan berbayar, API berbayar, atau plugin premium.
- Jangan mengarang data PAUD, NPSN, alamat, koordinat, foto, atau batas wilayah. Koordinat boleh kosong.
- Jangan commit file `.env`, kunci API, atau password. Kunci hanya di `.env`.
- Nama folder dan file view memakai huruf kecil semua (hosting Linux membedakan huruf besar dan kecil).
- Data uji diberi awalan "DATA UJI" dan harus dihapus sebelum peluncuran.
- Ganti password admin sementara sebelum website dipublikasikan.
- Permintaan rute ke Geoapify harus lewat server Laravel, bukan langsung dari browser.

## Yang perlu diselesaikan

- Tinjau importer Excel dan "patch koordinat otomatis": koordinat yang janggal tidak boleh ditebak.
- Lengkapi halaman publik: daftar PAUD (pencarian, filter, paginasi) dan detail PAUD.
- Pasang rute Geoapify lewat server di halaman peta.
- Pagination dan pesan validasi bawaan Laravel masih berbahasa Inggris di beberapa tempat.
- Uji menyeluruh sebelum deployment.