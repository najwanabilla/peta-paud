
@extends('layouts.public')

@section('judul', 'Peta PAUD Balikpapan')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .font-playful {
        font-family: 'Nunito', sans-serif;
    }

    /* BACKGROUND UTAMA */
    .hero-paud {
        position: relative;
        min-height: calc(100vh - 4rem);
        overflow: hidden;
        background:
            radial-gradient(circle at 72% 35%, rgba(59, 130, 246, .12), transparent 36%),
            linear-gradient(115deg, #ffffff 0%, #eff7ff 48%, #d5ebff 100%);
    }

    /* LINGKARAN DI BELAKANG PETA */
    .hero-paud::before {
        content: "";
        position: absolute;
        width: 480px;
        height: 480px;
        border-radius: 50%;
        background: #60a5fa;
        right: -100px;
        top: 70px;
        opacity: .18;
    }

    /* LAPISAN PUTIH BAGIAN BAWAH */
    .hero-paud::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 65px;
        bottom: 0;
        left: 0;
        background: linear-gradient(
            175deg,
            transparent 35%,
            rgba(255,255,255,.85) 36%,
            #ffffff 90%
        );
        z-index: 1;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 3;
    }

    /* LABEL */
    .label-paud {
        display: inline-block;
        padding: 11px 23px;
        border-radius: 50px;
        background: #dbeafe;
        color: #1455a4;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: .08em;
    }

    /* JUDUL */
    .judul-paud {
        color: #102f78;
        font-size: clamp(2.8rem, 5.5vw, 5.2rem);
        line-height: 1.04;
        letter-spacing: -3px;
        font-weight: 900;
    }

    .judul-paud span {
        color: #36a34b;
    }

    /* DESKRIPSI */
    .deskripsi-paud {
        color: #405b8b;
        font-size: 18px;
        line-height: 1.7;
    }

    /* TOMBOL PETA */
    .btn-peta {
        display: inline-flex;
        align-items: center;
        gap: 13px;
        padding: 17px 30px;
        border-radius: 50px;
        background: linear-gradient(120deg, #10b981, #36c978);
        color: white;
        font-weight: 900;
        font-size: 17px;
        text-decoration: none;
        box-shadow: 0 10px 25px rgba(16, 185, 129, .22);
        transition: all .3s ease;
    }

    .btn-peta:hover {
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 28px rgba(16, 185, 129, .3);
    }

    /* STATISTIK */
    .statistik-paud {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0;
        margin-top: 35px;
        padding: 20px 25px;
        background: rgba(255,255,255,.96);
        border: 1px solid rgba(255,255,255,.9);
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(30, 64, 175, .09);
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 20px;
        border-right: 1px solid #dbeafe;
    }

    .stat-item:first-child {
        padding-left: 0;
    }

    .stat-item:last-child {
        border-right: none;
        padding-right: 0;
    }

    .stat-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 21px;
    }

    .stat-number {
        color: #153675;
        font-size: 27px;
        line-height: 1.1;
        font-weight: 900;
    }

    .stat-label {
        color: #526b99;
        font-size: 12px;
        font-weight: 800;
    }

    /* PETA BALIKPAPAN */
    .peta-wrapper {
        position: relative;
        width: 100%;
        max-width: 570px;
        margin: auto;
    }

    .lingkaran-peta {
        position: absolute;
        width: 100%;
        aspect-ratio: 1;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        background: radial-gradient(
            circle,
            rgba(255,255,255,.35),
            rgba(37,99,235,.08)
        );
    }

    .siluet-paud {
        position: relative;
        z-index: 1;
        width: 100%;
        height: auto;
        filter: drop-shadow(0 18px 20px rgba(15, 69, 143, .17));
    }

    /* ILUSTRASI KOTA SEDERHANA */
    .cityscape {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 155px;
        z-index: 2;
        pointer-events: none;
        opacity: .17;
    }

    .cityscape svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* RESPONSIVE */
    @media (max-width: 1023px) {
        .hero-paud::before {
            width: 400px;
            height: 400px;
            right: -180px;
            top: 45%;
        }

        .peta-wrapper {
            max-width: 440px;
        }

        .cityscape {
            height: 130px;
        }
    }

    @media (max-width: 640px) {
        .hero-paud {
            min-height: auto;
            padding-top: 50px;
            padding-bottom: 100px;
        }

        .hero-paud::before {
            width: 300px;
            height: 300px;
            right: -150px;
            top: 55%;
        }

        .judul-paud {
            font-size: 43px;
            letter-spacing: -2px;
        }

        .deskripsi-paud {
            font-size: 15px;
        }

        .peta-wrapper {
            max-width: 340px;
        }

        .statistik-paud {
            width: 100%;
            justify-content: space-around;
            padding: 16px 10px;
            border-radius: 18px;
        }

        .stat-item {
            gap: 7px;
            padding: 0 9px;
        }

        .stat-item:first-child {
            padding-left: 0;
        }

        .stat-item:last-child {
            padding-right: 0;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .stat-number {
            font-size: 21px;
        }

        .stat-label {
            font-size: 10px;
        }

        .btn-peta {
            padding: 15px 22px;
            font-size: 15px;
        }

        .cityscape {
            height: 100px;
        }
    }
</style>
@endpush


@section('isi')

@php
    $totalPaud = \App\Models\Paud::count();

    $jumlahKelurahan = \App\Models\Paud::whereNotNull('kelurahan')
        ->distinct()
        ->count('kelurahan');

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


<section class="font-playful hero-paud flex items-center px-5 py-14 md:px-10">

    {{-- ILUSTRASI KOTA BAGIAN BAWAH --}}
    <div class="cityscape" aria-hidden="true">

        <svg viewBox="0 0 1440 180" preserveAspectRatio="none">

            {{-- Bukit belakang --}}
            <path
                d="M0 125 Q180 65 350 120 T720 110 T1100 105 T1440 120 V180 H0Z"
                fill="#60a5fa"
            />

            {{-- Gedung kota --}}
            <g fill="#2563eb">

                <path d="M170 180 V85 H280 V180Z"/>
                <path d="M300 180 V55 H390 V180Z"/>
                <path d="M420 180 V105 H510 V180Z"/>

                <path d="M850 180 V70 H950 V180Z"/>
                <path d="M970 180 V100 H1070 V180Z"/>
                <path d="M1100 180 V65 H1200 V180Z"/>

            </g>

            {{-- Jendela --}}
            <g fill="#ffffff">

                <path d="
                    M190 105 H210 V125 H190Z
                    M235 105 H255 V125 H235Z
                    M190 140 H210 V160 H190Z
                    M235 140 H255 V160 H235Z

                    M320 75 H340 V95 H320Z
                    M355 75 H375 V95 H355Z
                    M320 115 H340 V135 H320Z
                    M355 115 H375 V135 H355Z

                    M870 90 H890 V110 H870Z
                    M910 90 H930 V110 H910Z
                    M870 125 H890 V145 H870Z
                    M910 125 H930 V145 H910Z

                    M990 120 H1010 V140 H990Z
                    M1030 120 H1050 V140 H1030Z

                    M1120 85 H1140 V105 H1120Z
                    M1160 85 H1180 V105 H1160Z
                    M1120 125 H1140 V145 H1120Z
                    M1160 125 H1180 V145 H1160Z
                "/>

            </g>

            {{-- Pepohonan sederhana --}}
            <g fill="#2563eb">

                <circle cx="80" cy="130" r="30"/>
                <circle cx="130" cy="120" r="38"/>
                <circle cx="1370" cy="125" r="35"/>

            </g>

        </svg>

    </div>


    {{-- KONTEN UTAMA --}}
    <div class="hero-content mx-auto grid w-full max-w-7xl items-center gap-10 lg:grid-cols-2 lg:gap-12">

        {{-- BAGIAN KIRI --}}
        <div class="max-w-2xl">

            <div class="mb-6">
                <span class="label-paud">
                    PETA PAUD KOTA BALIKPAPAN
                </span>
            </div>

            <h1 class="judul-paud">
                Temukan PAUD<br>
                Terdekat di<br>
                <span>Balikpapan.</span>
            </h1>

            <p class="deskripsi-paud mt-7 max-w-xl font-semibold">
                Lihat lokasi
                <strong class="text-blue-900">
                    {{ number_format($totalPaud, 0, ',', '.') }} PAUD
                </strong>
                di seluruh kecamatan, lengkap dengan profil sekolah dan petunjuk jalan.
            </p>

            {{-- TOMBOL PETA --}}
            <div class="mt-8">

                <a href="{{ route('peta') }}" class="btn-peta group">

                    <i class="fa-solid fa-location-dot text-xl"></i>

                    Buka Peta Sebaran

                    <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-2"></i>

                </a>

            </div>


            {{-- STATISTIK --}}
            <div class="statistik-paud">

                {{-- PAUD --}}
                <div class="stat-item">

                    <div class="stat-icon bg-blue-100 text-blue-600">
                        <i class="fa-solid fa-school"></i>
                    </div>

                    <div>
                        <div class="stat-number">
                            {{ number_format($totalPaud, 0, ',', '.') }}
                        </div>

                        <div class="stat-label">
                            PAUD
                        </div>
                    </div>

                </div>


                {{-- KECAMATAN --}}
                <div class="stat-item">

                    <div class="stat-icon bg-emerald-100 text-emerald-600">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>

                    <div>
                        <div class="stat-number">
                            {{ $kecamatans->count() }}
                        </div>

                        <div class="stat-label">
                            Kecamatan
                        </div>
                    </div>

                </div>


                {{-- KELURAHAN --}}
                <div class="stat-item">

                    <div class="stat-icon bg-sky-100 text-sky-600">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <div>
                        <div class="stat-number">
                            {{ $jumlahKelurahan }}
                        </div>

                        <div class="stat-label">
                            Kelurahan
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- BAGIAN KANAN: PETA BALIKPAPAN --}}
        <div class="peta-wrapper" aria-hidden="true">

            <div class="lingkaran-peta"></div>

            <svg viewBox="0 0 612 500" class="siluet-paud">

                <defs>

                    <g id="map-pin-paud">

                        <path
                            d="M0,0 C-3,-6 -12,-16 -12,-24 A12,12 0 1,1 12,-24 C12,-16 3,-6 0,0 Z"
                            fill="#facc15"
                            stroke="#2563eb"
                            stroke-width="3"
                            stroke-linejoin="round"
                        />

                        <circle cx="0" cy="-24" r="4" fill="#1e3a8a"/>

                        <circle cx="0" cy="0" r="5" fill="#1e3a8a"/>

                    </g>

                </defs>


                {{-- BENTUK PETA --}}
                <path
                    d="M1 71 L1 77 L9 94 L9 98 L0 114 L6 122 L10 140 L22 149 L39 143 L39 153 L43 158 L57 162 L71 179 L81 179 L87 183 L90 183 L91 194 L95 202 L106 214 L111 226 L114 249 L123 266 L127 279 L127 285 L121 295 L119 310 L116 315 L118 322 L124 327 L123 330 L125 334 L118 346 L118 351 L121 356 L131 358 L141 366 L149 366 L152 363 L152 359 L160 345 L158 337 L165 334 L166 328 L171 325 L177 326 L180 322 L187 328 L185 331 L174 337 L173 353 L176 357 L177 363 L195 364 L190 370 L188 368 L180 368 L171 372 L168 376 L168 382 L178 398 L189 403 L198 403 L201 408 L198 416 L184 413 L184 435 L181 439 L184 452 L178 459 L175 468 L164 476 L162 481 L163 484 L170 489 L174 499 L212 492 L239 493 L263 484 L280 489 L288 487 L305 487 L317 483 L320 486 L323 486 L325 484 L336 483 L343 479 L349 479 L372 468 L376 461 L388 460 L431 438 L452 423 L469 406 L485 398 L496 388 L505 383 L509 378 L509 372 L513 364 L517 366 L530 354 L559 337 L567 330 L588 297 L592 280 L600 263 L600 234 L611 212 L601 199 L574 187 L562 186 L551 189 L538 184 L520 181 L516 177 L510 177 L502 170 L490 167 L477 150 L461 142 L450 139 L430 141 L422 138 L419 134 L425 120 L412 114 L406 105 L398 105 L394 103 L387 90 L375 83 L372 73 L367 66 L366 52 L362 47 L351 47 L345 43 L336 42 L323 32 L312 33 L295 39 L278 39 L267 32 L250 29 L245 20 L234 19 L231 13 L228 13 L217 23 L206 25 L198 32 L187 31 L184 34 L182 32 L175 31 L166 37 L160 25 L154 20 L146 17 L138 10 L125 6 L118 1 L107 0 L102 2 L97 9 L94 34 L79 63 L72 65 L68 73 L65 73 L55 67 L41 64 L33 64 L29 69 L26 69 L7 55Z"
                    fill="#ffffff"
                    stroke="#60a5fa"
                    stroke-width="4"
                    stroke-linejoin="round"
                />


                {{-- PIN HIASAN --}}
                @foreach ($titikHiasan as [$x, $y])

                    <use
                        href="#map-pin-paud"
                        transform="translate({{ $x }}, {{ $y }}) scale(0.9)"
                    />

                @endforeach

            </svg>

        </div>

    </div>

</section>

@endsection
