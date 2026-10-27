@extends('layouts.admin')

@section('judul', 'Dashboard')

@section('isi')
    @php
        // Nilai turunan dihitung di sini, jadi aman dengan controller lama maupun baru
        $denganKoordinat = $denganKoordinat ?? ($totalPaud - $tanpaKoordinat);
        $persenKoordinat = $persenKoordinat ?? ($totalPaud > 0 ? (int) round($denganKoordinat / $totalPaud * 100) : 0);
        $paudTerbaru = $paudTerbaru ?? null;
        $perJenis = $perJenis ?? null;
        $jenisKosong = $jenisKosong ?? 0;

        $jam = now()->hour;
        $sapaan = $jam < 4 ? 'malam' : ($jam < 11 ? 'pagi' : ($jam < 15 ? 'siang' : ($jam < 18 ? 'sore' : 'malam')));
        $namaDepan = \Illuminate\Support\Str::before(auth()->user()->name, ' ');

        $urut = $perKecamatan->sortByDesc('pauds_count')->values();
        $maks = max($urut->max('pauds_count') ?? 0, 1);

        $keliling = 2 * pi() * 52;
        $isiLingkaran = $keliling * $persenKoordinat / 100;

        // Warna tiap jenis satuan
        $warnaJenis = [
            'KB' => ['batang' => '#0ea5e9', 'lencana' => 'bg-sky-100 text-sky-700'],
            'TK' => ['batang' => '#10b981', 'lencana' => 'bg-emerald-100 text-emerald-700'],
            'TPA' => ['batang' => '#f59e0b', 'lencana' => 'bg-amber-100 text-amber-700'],
            'SPS' => ['batang' => '#8b5cf6', 'lencana' => 'bg-violet-100 text-violet-700'],
        ];
        $maksJenis = $perJenis ? max($perJenis->max('jumlah') ?? 0, 1) : 1;
    @endphp

    {{-- 1. BANNER RINGKASAN --}}
    <section class="relative overflow-hidden rounded-3xl p-6 text-white shadow-sm md:p-8"
             style="background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 60%, #38bdf8 100%);">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -bottom-16 right-24 h-40 w-40 rounded-full bg-white/10"></div>

        <div class="relative">
            <p class="text-sm font-medium text-sky-100">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="mt-1 text-2xl font-bold md:text-3xl">Selamat {{ $sapaan }}, {{ $namaDepan }}!</h2>
            <p class="mt-2 max-w-xl text-sm text-sky-50 md:text-base">
                Ringkasan data PAUD di Kota Balikpapan.
            </p>

            <div class="mt-5 flex flex-wrap gap-2 text-sm">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 font-medium ring-1 ring-white/25">
                    <i class="fa-solid fa-school"></i>
                    {{ number_format($totalPaud, 0, ',', '.') }} PAUD tercatat
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 font-medium ring-1 ring-white/25">
                    <i class="fa-solid fa-location-dot"></i>
                    @if ($totalPaud > 0)
                        {{ $persenKoordinat }}% koordinat lengkap
                    @else
                        Belum ada data
                    @endif
                </span>
            </div>
        </div>
    </section>

    {{-- 2. KARTU STATISTIK --}}
    <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-sky-50 opacity-70 transition-transform duration-300 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total PAUD</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($totalPaud, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                    <i class="fa-solid fa-school text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-50 opacity-70 transition-transform duration-300 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Sudah ada koordinat</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($denganKoordinat, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-amber-50 opacity-70 transition-transform duration-300 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Belum ada koordinat</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($tanpaKoordinat, 0, ',', '.') }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <i class="fa-solid fa-location-crosshairs text-xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-violet-50 opacity-70 transition-transform duration-300 group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Kecamatan aktif</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $kecamatanAktif }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <i class="fa-solid fa-map-location-dot text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. INFO JIKA DATA KOSONG --}}
    @if ($totalPaud === 0)
        <div class="mt-6 flex items-start gap-3 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm text-sky-900 shadow-sm">
            <i class="fa-solid fa-circle-info mt-0.5 text-lg text-sky-500"></i>
            <p>Data PAUD belum tersedia. Data akan muncul di sini setelah diimpor dari Excel atau ditambahkan manual lewat menu di samping.</p>
        </div>
    @endif

    {{-- 4. SEBARAN PER KECAMATAN DAN KELENGKAPAN KOORDINAT --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:col-span-2">
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                <h2 class="text-lg font-bold text-slate-800">Sebaran PAUD per kecamatan</h2>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    {{ number_format($totalPaud, 0, ',', '.') }} data
                </span>
            </div>

            <ul class="space-y-5">
                @forelse ($urut as $indeks => $k)
                    @php $persen = $totalPaud > 0 ? round($k->pauds_count / $totalPaud * 100) : 0; @endphp
                    <li>
                        <div class="mb-2 flex items-center justify-between gap-2 text-sm">
                            <span class="flex min-w-0 items-center gap-2.5 font-medium text-slate-700">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-500">{{ $indeks + 1 }}</span>
                                <span class="h-3.5 w-3.5 shrink-0 rounded-full shadow-sm ring-2 ring-white" style="background-color: {{ $k->warna }}"></span>
                                <span class="truncate">{{ $k->nama }}</span>
                                @if ($indeks === 0 && $k->pauds_count > 0)
                                    <span class="rounded-full bg-sky-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-sky-700">Terbanyak</span>
                                @endif
                                @unless ($k->is_active)
                                    <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-rose-600">Nonaktif</span>
                                @endunless
                            </span>
                            <span class="shrink-0 font-bold text-slate-800">
                                {{ $k->pauds_count }}
                                <span class="text-xs font-normal text-slate-500">PAUD · {{ $persen }}%</span>
                            </span>
                        </div>
                        <div class="h-3.5 w-full overflow-hidden rounded-full bg-slate-100 shadow-inner">
                            <div data-lebar="{{ round(($k->pauds_count / $maks) * 100, 1) }}%"
                                 class="h-full rounded-full transition-all duration-1000 ease-out"
                                 style="width: {{ round(($k->pauds_count / $maks) * 100, 1) }}%; background-color: {{ $k->warna }};"></div>
                        </div>
                    </li>
                @empty
                    <li class="flex flex-col items-center justify-center py-8 text-slate-400">
                        <i class="fa-solid fa-folder-open mb-3 text-4xl text-slate-300"></i>
                        <p class="text-sm">Belum ada data kecamatan yang tersedia.</p>
                    </li>
                @endforelse
            </ul>
        </section>

        <section class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="mb-6 border-b border-slate-100 pb-4 text-lg font-bold text-slate-800">Kelengkapan koordinat</h2>

            <div class="flex flex-1 flex-col items-center justify-center">
                <div class="relative h-40 w-40">
                    <svg viewBox="0 0 120 120" class="h-full w-full" role="img"
                         aria-label="{{ $persenKoordinat }} persen PAUD sudah memiliki koordinat">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#e2e8f0" stroke-width="12"></circle>
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#10b981" stroke-width="12" stroke-linecap="round"
                                stroke-dasharray="{{ round($isiLingkaran, 2) }} {{ round($keliling, 2) }}"
                                transform="rotate(-90 60 60)"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        @if ($totalPaud > 0)
                            <span class="text-3xl font-bold text-slate-900">{{ $persenKoordinat }}%</span>
                            <span class="text-xs text-slate-500">lengkap</span>
                        @else
                            <span class="text-2xl font-bold text-slate-300">–</span>
                            <span class="text-xs text-slate-400">belum ada data</span>
                        @endif
                    </div>
                </div>

                <dl class="mt-6 w-full space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center gap-2 text-slate-600"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Sudah ada</dt>
                        <dd class="font-semibold text-slate-800">{{ number_format($denganKoordinat, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="flex items-center gap-2 text-slate-600"><span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span> Belum ada</dt>
                        <dd class="font-semibold text-slate-800">{{ number_format($tanpaKoordinat, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            @if ($tanpaKoordinat > 0)
                <p class="mt-5 rounded-xl bg-amber-50 px-4 py-3 text-xs text-amber-800 ring-1 ring-amber-200">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    {{ number_format($tanpaKoordinat, 0, ',', '.') }} PAUD belum memiliki titik lokasi, jadi belum tampil sebagai penanda di peta.
                </p>
            @endif
        </section>
    </div>

    {{-- 5. JENIS SATUAN DAN PAUD TERBARU --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">

        @if ($perJenis !== null)
            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-800">Komposisi jenis satuan</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">KB · TK · TPA · SPS</span>
                </div>

                <ul class="space-y-5">
                    @foreach ($perJenis as $jenis)
                        @php
                            $gaya = $warnaJenis[$jenis['kode']] ?? ['batang' => '#64748b', 'lencana' => 'bg-slate-100 text-slate-700'];
                            $persenJenis = $totalPaud > 0 ? round($jenis['jumlah'] / $totalPaud * 100) : 0;
                        @endphp
                        <li>
                            <div class="mb-2 flex items-center justify-between gap-2 text-sm">
                                <span class="flex min-w-0 items-center gap-2.5 font-medium text-slate-700">
                                    <span class="rounded-md px-2 py-0.5 text-[11px] font-bold {{ $gaya['lencana'] }}">{{ $jenis['kode'] }}</span>
                                    <span class="truncate">{{ \Illuminate\Support\Str::after($jenis['label'], '(') ? rtrim(\Illuminate\Support\Str::after($jenis['label'], '('), ')') : $jenis['label'] }}</span>
                                </span>
                                <span class="shrink-0 font-bold text-slate-800">
                                    {{ $jenis['jumlah'] }}
                                    <span class="text-xs font-normal text-slate-500">· {{ $persenJenis }}%</span>
                                </span>
                            </div>
                            <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100">
                                <div data-lebar="{{ round(($jenis['jumlah'] / $maksJenis) * 100, 1) }}%"
                                     class="h-full rounded-full transition-all duration-1000 ease-out"
                                     style="width: {{ round(($jenis['jumlah'] / $maksJenis) * 100, 1) }}%; background-color: {{ $gaya['batang'] }};"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if ($jenisKosong > 0)
                    <p class="mt-5 text-xs text-slate-500">
                        {{ number_format($jenisKosong, 0, ',', '.') }} PAUD belum diisi jenis satuannya.
                    </p>
                @endif
            </section>
        @endif

        @if ($paudTerbaru !== null)
            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 {{ $perJenis === null ? 'lg:col-span-2' : '' }}">
                <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-800">PAUD terbaru ditambahkan</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">5 terakhir</span>
                </div>

                <ul class="divide-y divide-slate-100">
                    @forelse ($paudTerbaru as $p)
                        <li class="flex flex-wrap items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <p class="flex items-center gap-2 font-semibold text-slate-800">
                                    @if ($p->jenis_satuan)
                                        <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-bold {{ ($warnaJenis[$p->jenis_satuan]['lencana'] ?? 'bg-slate-100 text-slate-700') }}">{{ $p->jenis_satuan }}</span>
                                    @endif
                                    <span class="truncate">{{ $p->nama }}</span>
                                </p>
                                <p class="mt-0.5 flex flex-wrap items-center gap-x-3 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-full ring-1 ring-slate-300" style="background-color: {{ $p->kecamatan->warna }}"></span>
                                        {{ $p->kecamatan->nama }}
                                    </span>
                                    <span>{{ $p->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </p>
                            </div>

                            @if ($p->punyaKoordinat())
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                    <i class="fa-solid fa-circle-check"></i> Ada koordinat
                                </span>
                            @else
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                    <i class="fa-solid fa-circle-minus"></i> Belum ada koordinat
                                </span>
                            @endif
                        </li>
                    @empty
                        <li class="flex flex-col items-center justify-center py-8 text-slate-400">
                            <i class="fa-solid fa-school-circle-xmark mb-3 text-4xl text-slate-300"></i>
                            <p class="text-sm">Belum ada PAUD yang ditambahkan.</p>
                        </li>
                    @endforelse
                </ul>
            </section>
        @endif
    </div>

    <script>
        // Animasi batang grafik: dari 0 ke lebar sebenarnya
        document.querySelectorAll('[data-lebar]').forEach(function (el) {
            var lebar = el.dataset.lebar;
            el.style.width = '0%';
            requestAnimationFrame(function () {
                requestAnimationFrame(function () { el.style.width = lebar; });
            });
        });
    </script>
@endsection