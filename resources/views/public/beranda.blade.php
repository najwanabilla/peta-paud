@extends('layouts.public')

@section('judul', 'Beranda - Peta PAUD Balikpapan')

@push('styles')
<style>
    /* Animasi Fade In Up untuk Kartu */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animasi-kartu { animation: fadeInUp 0.8s ease-out forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.3s; }
    .delay-3 { animation-delay: 0.5s; }
</style>
@endpush

@section('isi')
    <!-- Bagian Hero -->
    <div class="bg-sky-700 text-white py-16 px-4 shadow-inner relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="max-w-5xl mx-auto relative z-10 text-center animasi-kartu">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 tracking-tight">Peta Sebaran PAUD Balikpapan</h1>
            <p class="text-lg text-sky-100 mb-8 max-w-2xl mx-auto">
                Jelajahi data Pendidikan Anak Usia Dini (PAUD) di Kota Balikpapan. Temukan lokasi, profil, dan rute menuju sekolah dengan mudah.
            </p>
            <a href="{{ route('peta') }}" class="inline-flex items-center gap-2 bg-white text-sky-700 font-bold px-8 py-3.5 rounded-full shadow-lg hover:bg-slate-100 hover:scale-105 transition-transform text-lg">
                <i class="fa-solid fa-map-location-dot"></i> Buka Peta Interaktif
            </a>
        </div>
    </div>

    <!-- Bagian Statistik (Animasi Angka) -->
    <div class="max-w-5xl mx-auto w-full px-4 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total PAUD -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex items-center gap-5 border border-slate-100 animasi-kartu delay-1">
                <div class="bg-sky-100 text-sky-600 p-4 rounded-xl text-3xl"><i class="fa-solid fa-school"></i></div>
                <div>
                    <p class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Total PAUD</p>
                    <h3 class="text-4xl font-extrabold text-slate-800"><span class="counter" data-target="{{ $totalPaud }}">0</span></h3>
                </div>
            </div>
            <!-- Kecamatan -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex items-center gap-5 border border-slate-100 animasi-kartu delay-2">
                <div class="bg-emerald-100 text-emerald-600 p-4 rounded-xl text-3xl"><i class="fa-solid fa-map-location-dot"></i></div>
                <div>
                    <p class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Kecamatan</p>
                    <h3 class="text-4xl font-extrabold text-slate-800"><span class="counter" data-target="{{ $totalKecamatan }}">0</span></h3>
                </div>
            </div>
            <!-- Kelurahan -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex items-center gap-5 border border-slate-100 animasi-kartu delay-3">
                <div class="bg-amber-100 text-amber-600 p-4 rounded-xl text-3xl"><i class="fa-solid fa-location-dot"></i></div>
                <div>
                    <p class="text-slate-500 text-sm font-semibold tracking-wide uppercase">Kelurahan</p>
                    <h3 class="text-4xl font-extrabold text-slate-800"><span class="counter" data-target="{{ $totalKelurahan }}">0</span></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Grafik -->
    <div class="max-w-5xl mx-auto w-full px-4 py-12 animasi-kartu delay-3">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                <h3 class="font-bold text-lg mb-6 text-slate-800 border-l-4 border-sky-500 pl-3">Sebaran per Kecamatan</h3>
                <div class="relative h-72 w-full">
                    <canvas id="chartKecamatan"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                <h3 class="font-bold text-lg mb-6 text-slate-800 border-l-4 border-emerald-500 pl-3">Komposisi Jenis PAUD</h3>
                <div class="relative h-72 w-full flex justify-center">
                    <canvas id="chartJenis"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Animasi Angka
    const counters = document.querySelectorAll('.counter');
    const speed = 200;
    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;
            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 15);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });

    // Grafik
    const dataKecamatan = @json($grafikKecamatan);
    const dataJenis = @json($grafikJenis);

    new Chart(document.getElementById('chartKecamatan').getContext('2d'), {
        type: 'bar',
        data: {
            labels: dataKecamatan.map(item => item.nama.replace('Balikpapan ', 'Bpp. ')),
            datasets: [{
                label: 'Jumlah PAUD',
                data: dataKecamatan.map(item => item.total),
                backgroundColor: '#0ea5e9',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            animation: { duration: 2000, easing: 'easeOutQuart' },
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
        }
    });

    new Chart(document.getElementById('chartJenis').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: dataJenis.map(item => item.jenis_satuan),
            datasets: [{
                data: dataJenis.map(item => item.total),
                backgroundColor: ['#0ea5e9', '#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 4, borderColor: '#ffffff', hoverOffset: 10
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            animation: { animateScale: true, animateRotate: true, duration: 2000 },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
            cutout: '75%'
        }
    });
</script>
@endpush