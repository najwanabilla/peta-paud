@extends('layouts.public')

@section('judul', 'Peta PAUD Balikpapan')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .font-playful { font-family: 'Nunito', sans-serif; }

    .hero-tegas {
        min-height: calc(100vh - 4rem);
        color: #ffffff;
        background-color: #1e3a8a;
        background-image:
            radial-gradient(rgba(255, 255, 255, 0.10) 1.5px, transparent 1.5px),
            linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 60%, #312e81 100%);
        background-size: 26px 26px, auto;
    }

    .label-tegas { color: #fde047; letter-spacing: 0.2em; }
    .judul-tegas { color: #ffffff; font-weight: 900; line-height: 1.05; letter-spacing: -0.02em; font-size: clamp(2.6rem, 6vw, 4.5rem); }
    .judul-tegas span { color: #fde047; }
    .paragraf-tegas { color: #dbeafe; }

    .tombol-kuning { background: #facc15; color: #0f172a; }
    .tombol-kuning:hover { background: #fde047; }

    .angka-tegas dt { color: #bfdbfe; }
    .angka-tegas dd { color: #ffffff; margin: 0; }

    .siluet { filter: drop-shadow(0 25px 35px rgba(0, 0, 0, 0.35)); }
</style>
@endpush

@section('isi')
@php
    $totalPaud       = \App\Models\Paud::count();
    $jumlahKelurahan = \App\Models\Paud::whereNotNull('kelurahan')->distinct()->count('kelurahan');

    // Hiasan peta: 9 titik dengan jarak yang merata
    $titikHiasan = [
        [280, 140],  
        [410, 180],  
        [160, 270],  
        [310, 260],  
        [500, 260],  
        [220, 390],  
        [350, 420],  
        [470, 350],  
        [290, 330],  
    ];
@endphp

<section class="font-playful hero-tegas relative flex items-center overflow-hidden px-5 py-14 md:py-20">
    <div class="relative mx-auto grid w-full max-w-6xl items-center gap-12 lg:grid-cols-2 lg:gap-16">

        {{-- KIRI: pesan utama + satu tombol --}}
        <div>
            <p class="label-tegas mb-5 text-sm font-extrabold uppercase">Peta PAUD Kota Balikpapan</p>

            <h1 class="judul-tegas">
                Temukan PAUD<br>
                Terdekat di<br>
                <span>Balikpapan.</span>
            </h1>

            <p class="paragraf-tegas mt-6 max-w-lg text-lg font-semibold leading-relaxed">
                Lihat lokasi {{ number_format($totalPaud, 0, ',', '.') }} PAUD di seluruh kecamatan, lengkap dengan profil sekolah dan petunjuk jalan.
            </p>

            <a href="{{ route('peta') }}"
               class="tombol-kuning group mt-10 inline-flex items-center gap-3 rounded-2xl px-9 py-5 text-lg font-black shadow-2xl transition active:scale-95">
                <i class="fa-solid fa-map-location-dot text-xl"></i>
                Buka Peta Sebaran
                <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
            </a>

            <dl class="angka-tegas mt-12 flex flex-wrap gap-x-10 gap-y-4 pt-6" style="border-top: 1px solid rgba(255,255,255,.25)">
                <div>
                    <dt class="text-xs font-extrabold uppercase tracking-widest">PAUD</dt>
                    <dd class="text-3xl font-black">{{ number_format($totalPaud, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-extrabold uppercase tracking-widest">Kecamatan</dt>
                    <dd class="text-3xl font-black">{{ $kecamatans->count() }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-extrabold uppercase tracking-widest">Kelurahan</dt>
                    <dd class="text-3xl font-black">{{ $jumlahKelurahan }}</dd>
                </div>
            </dl>
        </div>

        {{-- KANAN: siluet Balikpapan (hiasan) --}}
        <div class="mx-auto w-full max-w-sm lg:max-w-md" aria-hidden="true">
            <svg viewBox="0 0 612 500" class="siluet h-auto w-full">
                {{-- Definisi Bentuk Pin (Map Marker) --}}
                <defs>
                    <g id="map-pin">
                        {{-- 1. Bentuk tetes air (Pin) --}}
                        <path d="M0,0 C-3,-6 -12,-16 -12,-24 A12,12 0 1,1 12,-24 C12,-16 3,-6 0,0 Z" 
                              fill="#facc15" stroke="#1e3a8a" stroke-width="3" stroke-linejoin="round"/>
                        
                        {{-- 2. Lubang kecil di tengah pin --}}
                        <circle cx="0" cy="-24" r="4" fill="#1e3a8a" />

                        {{-- 3. Titik bulat di ujung bawah (dibuat lebih besar dan ditaruh paling bawah) --}}
                        <circle cx="0" cy="0" r="5" fill="#1e3a8a" />
                    </g>
                </defs>

                {{-- Siluet Pulau --}}
                <path d="M1 71 L1 77 L9 94 L9 98 L0 114 L6 122 L10 140 L22 149 L39 143 L39 153 L43 158 L57 162 L71 179 L81 179 L87 183 L90 183 L91 194 L95 202 L106 214 L111 226 L114 249 L123 266 L127 279 L127 285 L121 295 L119 310 L116 315 L118 322 L124 327 L123 330 L125 334 L118 346 L118 351 L121 356 L131 358 L141 366 L149 366 L152 363 L152 359 L160 345 L158 337 L165 334 L166 328 L171 325 L177 326 L180 322 L187 328 L185 331 L174 337 L173 353 L176 357 L177 363 L195 364 L190 370 L188 368 L180 368 L171 372 L168 376 L168 382 L178 398 L189 403 L198 403 L201 408 L198 416 L184 413 L184 435 L181 439 L184 452 L178 459 L175 468 L164 476 L162 481 L163 484 L170 489 L174 499 L212 492 L239 493 L263 484 L280 489 L288 487 L305 487 L317 483 L320 486 L323 486 L325 484 L336 483 L343 479 L349 479 L372 468 L376 461 L388 460 L431 438 L452 423 L469 406 L485 398 L496 388 L505 383 L509 378 L509 372 L513 364 L517 366 L530 354 L559 337 L567 330 L588 297 L592 280 L600 263 L600 234 L611 212 L601 199 L574 187 L562 186 L551 189 L538 184 L520 181 L516 177 L510 177 L502 170 L490 167 L477 150 L461 142 L450 139 L430 141 L422 138 L419 134 L425 120 L412 114 L406 105 L398 105 L394 103 L387 90 L375 83 L372 73 L367 66 L366 52 L362 47 L351 47 L345 43 L336 42 L323 32 L312 33 L295 39 L278 39 L267 32 L250 29 L245 20 L234 19 L231 13 L228 13 L217 23 L206 25 L198 32 L187 31 L184 34 L182 32 L175 31 L166 37 L160 25 L154 20 L146 17 L138 10 L125 6 L118 1 L107 0 L102 2 L97 9 L94 34 L79 63 L72 65 L68 73 L65 73 L55 67 L41 64 L33 64 L29 69 L26 69 L7 55Z" 
                      fill="#ffffff" stroke="#93c5fd" stroke-width="4" stroke-linejoin="round"/>
                
                {{-- Looping Marker Pin --}}
                @foreach ($titikHiasan as [$x, $y])
                    {{-- Memanggil bentuk pin --}}
                    <use href="#map-pin" transform="translate({{ $x }}, {{ $y }}) scale(0.9)" />
                @endforeach
            </svg>
        </div>

    </div>
</section>
@endsection