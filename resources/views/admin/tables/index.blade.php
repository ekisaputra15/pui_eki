@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-table-cells mr-1.5"></i>Pengelola</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Status Meja</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Overview kondisi semua meja di restoran secara real-time.</p>
    </div>
    <div class="flex gap-2 flex-shrink-0">
        <a href="{{ route('admin.tables.qr.all') }}" target="_blank"
                class="btn btn-ghost" style="font-size: 0.875rem; padding: 8px 14px;">
            <i class="fa-solid fa-print mr-1.5 text-xs"></i>Print Semua
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
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
    <p class="text-sm" style="color: var(--color-ink-muted);">Kasir dapat menambah meja dari menu Manajemen Meja.</p>
</div>
@else
<div id="qr-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
    @foreach ($tables as $table)
    @php
        $isClear = $table->status === 'clear';
    @endphp
    <div class="card flex flex-col items-center text-center" style="padding: 20px 16px; border-radius: 18px;
         {{ $isClear ? 'border-color: rgba(24,226,153,0.3); background: linear-gradient(160deg, #f0fdf8 0%, #ffffff 100%);' : 'border-color: rgba(239,68,68,0.25); background: linear-gradient(160deg, #fff5f5 0%, #ffffff 100%);' }}">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" 
             style="background: {{ $isClear ? 'var(--color-brand-light)' : '#fee2e2' }};">
            <i class="fa-solid fa-table-cells text-sm" style="color: {{ $isClear ? 'var(--color-brand-deep)' : '#dc2626' }};"></i>
        </div>
        <p class="font-bold border-b border-border-subtle w-full pb-1 mb-1" style="font-size: 1.125rem; letter-spacing: -0.3px; color: var(--color-ink);">{{ $table->name }}</p>
        <div class="flex flex-col items-center mb-3">
            <span class="t-mono text-[8px]" style="color: var(--color-ink-faint);">Meja</span>
            <span class="t-mono text-[8px]" style="color: var(--color-ink-faint);">{{ $table->updated_at->format('H:i') }}</span>
        </div>
        @if ($isClear)
            <span class="badge badge-green"><i class="fa-solid fa-circle-check mr-1 text-[8px]"></i>Bersih</span>
        @else
            <span class="badge badge-red"><i class="fa-solid fa-broom mr-1 text-[8px]"></i>Kotor</span>
        @endif
    </div>
    @endforeach
</div>
@endif
@endsection
