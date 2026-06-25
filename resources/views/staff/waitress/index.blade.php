@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-broom mr-1.5"></i>Waitress</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Monitor Status Meja</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Pantau status kebersihan meja. Gunakan Aplikasi E-MENUGO Mobile untuk membersihkan meja.</p>
    </div>
    <a href="{{ route('waitress.history') }}" class="btn btn-ghost self-start sm:self-auto" style="font-size: 0.875rem; padding: 8px 14px;">
        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs"></i>Riwayat Scan
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
    <div class="card" style="padding: 16px 20px; border-color: rgba(239,68,68,0.2);">
        <p class="t-mono text-[10px] mb-1" style="color: #dc2626;">Kotor & Perlu Dibereskan</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $tables->where('status', 'dirty')->count() }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Bersih</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $tables->where('status', 'clear')->count() }}</p>
    </div>
    <div class="card hidden md:block" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Meja</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $tables->count() }}</p>
    </div>
</div>

<!-- Table Grid -->
@if ($tables->isEmpty())
<div class="card flex flex-col items-center justify-center py-16 text-center" style="border-style: dashed;">
    <i class="fa-solid fa-table-cells text-3xl mb-4" style="color: var(--color-ink-faint);"></i>
    <p class="font-semibold mb-1" style="color: var(--color-ink);">Belum Ada Meja</p>
    <p class="text-sm mb-4" style="color: var(--color-ink-muted);">Belum ada meja yang terdaftar di sistem.</p>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach ($tables as $table)
    @php
        $isDirty = $table->status === 'dirty';
        $borderStyle = $isDirty ? 'border-color: rgba(239,68,68,0.3);' : 'border-color: rgba(24,226,153,0.25);';
    @endphp
    <div class="card flex flex-col justify-between" style="border-radius: 20px; {{ $borderStyle }} padding: 0; overflow: hidden; min-height: 120px;">
        
        <div class="flex justify-between items-center px-5 pt-4 pb-4">
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-xl" style="color: {{ $isDirty ? '#dc2626' : 'var(--color-brand-deep)' }};"></i>
                    <span class="font-semibold text-lg" style="color: var(--color-ink); letter-spacing: -0.1px;">Meja {{ $table->name }}</span>
                </div>
            </div>
            @if ($isDirty)
                <span class="badge badge-red" style="padding: 6px 12px; font-size: 11px;"><i class="fa-solid fa-broom mr-1.5"></i>Kotor</span>
            @else
                <span class="badge badge-green" style="padding: 6px 12px; font-size: 11px;"><i class="fa-solid fa-circle-check mr-1.5"></i>Bersih</span>
            @endif
        </div>
        
        <div class="px-5 py-3" style="background: var(--color-surface); border-top: 1px solid var(--color-border-subtle);">
            @if ($isDirty)
                <p class="text-xs text-red-600 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Harap bersihkan menggunakan aplikasi mobile.</p>
            @else
                <p class="text-xs text-green-700 font-medium"><i class="fa-solid fa-check mr-1"></i>Siap digunakan pelanggan.</p>
            @endif
        </div>
        
    </div>
    @endforeach
</div>
@endif

@endsection
