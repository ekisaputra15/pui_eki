@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label">
            <i class="fa-solid fa-arrow-left mr-1.5 text-xs cursor-pointer" onclick="history.back()"></i>
            Kasir / Detail Meja
        </span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">
            Meja {{ $table->name }}
        </h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Pesanan aktif dengan status pending, diproses, atau dibuat.</p>
    </div>
    <a href="{{ route('cashier.tables') }}" class="btn btn-ghost self-start sm:self-auto" style="font-size: 0.875rem; padding: 8px 14px;">
        <i class="fa-solid fa-table-cells-large mr-1.5 text-xs"></i>Semua Meja
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="card" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Pesanan Aktif</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $activeOrders->count() }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Total Tagihan</p>
        <p class="font-bold text-lg" style="letter-spacing: -0.3px; color: var(--color-ink);">Rp{{ number_format($totalBill, 0, ',', '.') }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: {{ $table->status === 'available' ? 'rgba(24,226,153,0.3)' : 'rgba(239,68,68,0.2)' }};">
        <p class="t-mono text-[10px] mb-1" style="color: {{ $table->status === 'available' ? 'var(--color-brand-deep)' : '#dc2626' }};">Status Meja</p>
        <p class="font-bold text-lg" style="letter-spacing: -0.3px; color: var(--color-ink);">{{ $table->status === 'available' ? 'Bersih' : 'Kotor' }}</p>
    </div>
</div>

<!-- Orders -->
@if ($activeOrders->isEmpty())
<div class="card flex flex-col items-center justify-center py-16 text-center" style="border-style: dashed;">
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background: var(--color-brand-light);">
        <i class="fa-solid fa-circle-check text-2xl" style="color: var(--color-brand-deep);"></i>
    </div>
    <p class="font-semibold mb-1" style="color: var(--color-ink);">Tidak Ada Pesanan Aktif</p>
    <p class="text-sm mb-5" style="color: var(--color-ink-muted);">Meja {{ $table->name }} tidak memiliki pesanan yang sedang berjalan.</p>
    <a href="{{ route('cashier.tables') }}" class="btn btn-ghost" style="font-size: 0.875rem; padding: 8px 18px;">
        <i class="fa-solid fa-arrow-left mr-1.5 text-xs"></i>Kembali
    </a>
</div>

@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @foreach ($activeOrders as $order)
    @php
        $s = $order->status;
        $borderColor = $s === 'pending' ? '#f59e0b' : ($s === 'diproses' ? '#3b82f6' : 'var(--color-brand)');
        $badgeClass = [
            'pending' => 'badge-yellow',
            'diproses' => 'badge-blue',
            'dibuat' => 'badge-green',
        ][$s] ?? 'badge-gray';
        $statusLabel = [
            'pending' => 'Menunggu',
            'diproses' => 'Diproses',
            'dibuat' => 'Dibuat',
        ][$s] ?? $s;
    @endphp
    <div class="card flex flex-col" style="border-radius: 20px; padding: 0; overflow: hidden; border-left: 3px solid {{ $borderColor }};">

        <!-- Card Header -->
        <div class="flex justify-between items-center px-5 pt-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle); background: var(--color-surface);">
            <div>
                <span class="t-mono text-[10px]" style="color: var(--color-ink-faint);">{{ $order->order_id }}</span>
                <div class="flex items-center gap-2 mt-1">
                    <i class="fa-solid fa-table-cells text-xs" style="color: var(--color-ink-muted);"></i>
                    <span class="font-semibold text-sm" style="color: var(--color-ink);">Meja {{ $order->table_id }}</span>
                </div>
            </div>
            <span class="badge {{ $badgeClass }}">
                <i class="fa-solid fa-circle text-[6px] mr-1.5"></i>{{ $statusLabel }}
            </span>
        </div>

        <!-- Items -->
        <div class="flex-1 px-5 py-4">
            <p class="t-mono text-[10px] mb-3" style="color: var(--color-ink-faint);">Item Pesanan</p>
            <div class="flex flex-col gap-2">
                @foreach ($order->items as $it)
                <div class="flex justify-between items-center text-sm">
                    <span style="color: var(--color-ink-secondary);">
                        <span class="font-semibold" style="color: var(--color-brand-deep);">{{ (int)$it->quantity }}x</span>
                        {{ $it->name }}
                    </span>
                    <span class="font-medium" style="color: var(--color-ink);">Rp{{ number_format($it->price * $it->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Total -->
        <div class="flex justify-between items-center px-5 py-3" style="border-top: 1px solid var(--color-border-subtle); background: var(--color-brand-light);">
            <span class="t-mono text-[10px]" style="color: var(--color-brand-deep);">Total</span>
            <span class="font-bold text-sm" style="color: var(--color-brand-deep);">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        @if ($s === 'pending')
        <div class="p-3 bg-white border-t border-border-subtle">
            <form action="{{ route('cashier.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                <button type="submit" class="btn btn-brand w-full" style="padding: 10px; font-size: 0.875rem;">
                    <i class="fa-solid fa-check-circle mr-2"></i>Konfirmasi Pembayaran
                </button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif
@endsection
