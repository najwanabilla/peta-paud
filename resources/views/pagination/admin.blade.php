@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman"
         class="flex flex-col items-center justify-between gap-3 rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200 sm:flex-row">

        <p class="text-sm text-slate-500">
            Menampilkan
            <span class="font-semibold text-slate-800">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold text-slate-800">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
            data
        </p>

        <div class="flex flex-wrap items-center justify-center gap-1">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="rounded-lg px-3 py-2 text-sm text-slate-300" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left text-xs"></i> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    <i class="fa-solid fa-chevron-left text-xs"></i> Sebelumnya
                </a>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 py-2 text-sm text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $halaman => $url)
                        @if ($halaman == $paginator->currentPage())
                            <span aria-current="page"
                                  class="min-w-9 rounded-lg bg-sky-600 px-3 py-2 text-center text-sm font-semibold text-white">{{ $halaman }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Ke halaman {{ $halaman }}"
                               class="min-w-9 rounded-lg px-3 py-2 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-100">{{ $halaman }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    Berikutnya <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="rounded-lg px-3 py-2 text-sm text-slate-300" aria-disabled="true">
                    Berikutnya <i class="fa-solid fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
    </nav>
@endif