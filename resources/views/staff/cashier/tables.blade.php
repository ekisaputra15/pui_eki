@extends('layouts.admin')

@section('content')

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-table-cells-large mr-1.5"></i>Manajemen Meja</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Meja & QR Code</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Kelola meja, generate QR, dan cetak untuk ditempel di meja restoran.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.tables.qr.all') }}" target="_blank"
                class="btn btn-ghost shadow-sm" style="font-size: 0.875rem; padding: 8px 14px;">
            <i class="fa-solid fa-print mr-1.5 text-xs"></i>Print Semua
        </a>
        <button type="button" onclick="document.getElementById('add-table-modal').classList.remove('hidden'); document.getElementById('add-table-modal').classList.add('flex');"
                class="btn btn-brand" style="font-size: 0.875rem; padding: 8px 16px;">
            <i class="fa-solid fa-plus mr-1.5 text-xs"></i>Tambah Meja
        </button>
    </div>
</div>

@if (session('success'))
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-6" style="background: var(--color-brand-light); border: 1px solid rgba(24,226,153,0.3);">
    <i class="fa-solid fa-circle-check" style="color: var(--color-brand-deep);"></i>
    <p class="text-sm font-medium" style="color: var(--color-brand-deep);">{{ session('success') }}</p>
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="card" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Meja</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $tables->count() }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Bersih</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $tables->where('status', 'clear')->count() }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(239,68,68,0.2);">
        <p class="t-mono text-[10px] mb-1" style="color: #dc2626;">Kotor</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $tables->where('status', 'dirty')->count() }}</p>
    </div>
</div>

<!-- QR Grid -->
@if ($tables->isEmpty())
<div class="card flex flex-col items-center justify-center py-16 text-center" style="border-style: dashed;">
    <i class="fa-solid fa-table-cells text-3xl mb-4" style="color: var(--color-ink-faint);"></i>
    <p class="font-semibold mb-1" style="color: var(--color-ink);">Belum Ada Meja</p>
    <p class="text-sm mb-4" style="color: var(--color-ink-muted);">Tambah meja pertama untuk mulai generate QR.</p>
    <button type="button" onclick="document.getElementById('add-table-modal').classList.remove('hidden'); document.getElementById('add-table-modal').classList.add('flex');" class="btn btn-brand" style="padding: 8px 20px; font-size: 0.875rem;">
        <i class="fa-solid fa-plus mr-1.5"></i>Tambah Meja
    </button>
</div>
@else
<div id="qr-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach ($tables as $table)
    @php
        $isDirty = $table->status === 'dirty';
        $borderStyle = $isDirty ? 'border-color: rgba(239,68,68,0.3);' : 'border-color: rgba(24,226,153,0.25);';
    @endphp
    <div class="card flex flex-col" style="border-radius: 20px; {{ $borderStyle }} padding: 0; overflow: hidden;">
        <!-- Card Header -->
        <div class="flex justify-between items-center px-5 pt-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle);">
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-sm" style="color: {{ $isDirty ? '#dc2626' : 'var(--color-brand-deep)' }};"></i>
                    <span class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.1px;">Meja {{ $table->name }}</span>
                </div>
                <span class="t-mono text-[8px] mt-0.5" style="color: var(--color-ink-faint);">Update: {{ $table->updated_at->format('H:i') }}</span>
            </div>
            @if ($isDirty)
                <span class="badge badge-red"><i class="fa-solid fa-broom mr-1 text-[9px]"></i>Kotor</span>
            @else
                <span class="badge badge-green"><i class="fa-solid fa-circle-check mr-1 text-[9px]"></i>Bersih</span>
            @endif
        </div>

        <!-- Preview -->
        <div class="px-5 py-6 flex flex-col items-center bg-surface">
            <div class="w-32 h-32 bg-white p-2 rounded-xl shadow-sm border border-border-subtle flex items-center justify-center">
                @php
                    $host = request()->getHost();
                    if (in_array($host, ['localhost', '127.0.0.1', '0.0.0.0'])) {
                        $host = gethostbyname(gethostname());
                    }
                    $dynamicUrl = request()->getScheme() . '://' . $host . (request()->getPort() ? ':' . request()->getPort() : '') . '/scan/' . $table->name;
                @endphp
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($dynamicUrl) }}" 
                     alt="QR Meja {{ $table->name }}" class="w-full h-full object-contain">
            </div>
            <p class="mt-3 text-[10px] text-ink-faint font-mono truncate w-full text-center">{{ $dynamicUrl }}</p>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 px-5 pb-4 pt-4">
            <a href="{{ route('admin.tables.qr.single', $table->name) }}" target="_blank"
               class="btn btn-ghost flex-1" style="font-size: 0.8125rem; padding: 7px 12px;">
                <i class="fa-solid fa-print mr-1.5 text-xs"></i>Print
            </a>
            <a href="{{ route('cashier.table_detail', $table->name) }}"
               class="btn btn-primary flex-1" style="font-size: 0.8125rem; padding: 7px 12px;">
                <i class="fa-solid fa-eye mr-1.5 text-xs"></i>Detail
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Add Table Modal -->
<div id="add-table-modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-modal p-6" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-brand-light);">
                    <i class="fa-solid fa-plus text-sm" style="color: var(--color-brand-deep);"></i>
                </div>
                <h3 class="font-semibold" style="font-size: 1.0625rem; color: var(--color-ink); letter-spacing: -0.2px;">Tambah Meja Baru</h3>
            </div>
            <button type="button" onclick="document.getElementById('add-table-modal').classList.add('hidden'); document.getElementById('add-table-modal').classList.remove('flex');"
                    class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors"
                    style="color: var(--color-ink-muted); background: var(--color-surface);"
                    onmouseover="this.style.background='var(--color-surface-hover)'"
                    onmouseout="this.style.background='var(--color-surface)'">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('cashier.tables.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1.5" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-ink);">Label Meja</label>
                <input type="text" name="table_label" class="input-base" placeholder="Contoh: A1 atau VIP 1" required>
                <p class="mt-1.5" style="font-size: 0.75rem; color: var(--color-ink-faint);">Hanya huruf, angka, spasi, dash, atau underscore.</p>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('add-table-modal').classList.add('hidden'); document.getElementById('add-table-modal').classList.remove('flex');" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.875rem;">Batal</button>
                <button type="submit" class="btn btn-brand" style="padding: 8px 20px; font-size: 0.875rem;">
                    <i class="fa-solid fa-plus mr-1.5 text-xs"></i>Simpan Meja
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
