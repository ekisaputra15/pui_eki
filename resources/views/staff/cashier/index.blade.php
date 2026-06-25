@extends('layouts.admin')

@section('content')
@php
$pendingOrders = $orders->where('status', 'pending');
$totalToday = $orders->whereNotIn('status', ['dibatalkan'])->count();
$completedToday = $orders->where('status', 'selesai')->count();
@endphp

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-receipt mr-1.5"></i>Monitor Pesanan</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Persetujuan Pembayaran</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Konfirmasi pembayaran untuk pesanan yang masuk dari pelanggan.</p>
    </div>
    <button onclick="location.reload()" title="Refresh" class="flex items-center gap-2 px-3 py-2 rounded-xl transition-colors self-start sm:self-auto"
            style="color: var(--color-ink-muted); border: 1px solid var(--color-border-subtle); background: white; font-size: 0.875rem; cursor: pointer;"
            onmouseover="this.style.color=getComputedStyle(document.documentElement).getPropertyValue('--color-brand-deep')"
            onmouseout="this.style.color=getComputedStyle(document.documentElement).getPropertyValue('--color-ink-muted')">
        <i class="fa-solid fa-rotate-right text-sm"></i>
        <span>Refresh</span>
    </button>
</div>

<!-- Dashboard Content Container -->
<div id="dashboard-main-content">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card" style="padding: 16px 20px;">
            <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Menunggu</p>
            <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $pendingOrders->count() }}</p>
            <p class="text-xs mt-0.5" style="color: var(--color-ink-muted);">Perlu konfirmasi</p>
        </div>
        <div class="card" style="padding: 16px 20px;">
            <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Pesanan</p>
            <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $totalToday }}</p>
            <p class="text-xs mt-0.5" style="color: var(--color-ink-muted);">Semua status</p>
        </div>
        <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
            <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Selesai</p>
            <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $completedToday }}</p>
            <p class="text-xs mt-0.5" style="color: var(--color-ink-muted);">Telah dilayani</p>
        </div>
    </div>

    <!-- Orders Grid -->
    @if ($pendingOrders->isEmpty())
    <div class="card flex flex-col items-center justify-center py-16 text-center" style="border-style: dashed;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background: var(--color-brand-light);">
            <i class="fa-solid fa-circle-check text-2xl" style="color: var(--color-brand-deep);"></i>
        </div>
        <p class="font-semibold mb-1" style="color: var(--color-ink);">Semua Pesanan Terproses</p>
        <p style="font-size: 0.875rem; color: var(--color-ink-muted);">Tidak ada pembayaran yang menunggu konfirmasi.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($pendingOrders as $order)
        <div class="card flex flex-col" style="border-radius: 20px; border-left: 3px solid #f59e0b;">
            <!-- Card Header -->
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="t-mono text-[10px]" style="color: var(--color-ink-faint);">{{ $order->order_id }}</span>
                        <span class="t-mono text-[9px] px-1.5 py-0.5 rounded-md" style="background: rgba(245,158,11,0.1); color: #b45309;">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-1.5">
                        <i class="fa-solid fa-table-cells text-xs" style="color: var(--color-ink-muted);"></i>
                        <span class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.1px;">Meja {{ $order->table_id }}</span>
                    </div>
                </div>
                <span class="badge badge-yellow"><i class="fa-solid fa-clock mr-1 text-[9px]"></i>Pending</span>
            </div>

            <!-- Items -->
            <div class="flex-1 mb-4 rounded-xl overflow-hidden" style="background: var(--color-surface); border: 1px solid var(--color-border-subtle);">
                <div class="px-4 py-2 border-b" style="border-color: var(--color-border-subtle);">
                    <p class="t-mono text-[10px]" style="color: var(--color-ink-faint);">Item Pesanan</p>
                </div>
                <div class="px-4 py-3 flex flex-col gap-2">
                    @foreach($order->items as $it)
                    <div class="flex justify-between items-center text-sm">
                        <span style="color: var(--color-ink-secondary);">
                            <span class="font-semibold" style="color: var(--color-brand-deep);">{{ $it->quantity }}x</span>
                            {{ $it->name }}
                        </span>
                        <span class="font-medium" style="color: var(--color-ink);">Rp{{ number_format($it->price * $it->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="px-4 py-3 border-t flex justify-between items-center" style="border-color: var(--color-border-subtle); background: var(--color-brand-light);">
                    <span class="t-mono text-[10px]" style="color: var(--color-brand-deep);">Total</span>
                    <span class="font-bold" style="color: var(--color-brand-deep);">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Action -->
            <form method="POST" action="{{ route('cashier.confirm') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                <button type="submit" class="btn btn-brand w-full" style="width: 100%;">
                    <i class="fa-solid fa-circle-check mr-2"></i>Konfirmasi Pembayaran
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
