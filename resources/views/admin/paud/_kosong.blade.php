<div class="mx-auto flex max-w-sm flex-col items-center">
    <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl text-slate-300">
        <i class="fa-solid {{ $adaFilter ? 'fa-magnifying-glass' : 'fa-school-circle-xmark' }}"></i>
    </span>

    @if ($adaFilter)
        <p class="font-semibold text-slate-700">Tidak ada PAUD yang cocok</p>
        <p class="mt-1 text-sm text-slate-500">Coba kata kunci lain atau hapus sebagian filter.</p>
        <a href="{{ route('admin.paud.index') }}"
           class="mt-4 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
            Reset pencarian
        </a>
    @else
        <p class="font-semibold text-slate-700">Data PAUD belum tersedia</p>
        <p class="mt-1 text-sm text-slate-500">Tambahkan satu per satu, atau impor dari file Excel.</p>
        <a href="{{ route('admin.paud.create') }}"
           class="mt-4 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
            <i class="fa-solid fa-plus mr-1"></i> Tambah PAUD
        </a>
    @endif
</div>