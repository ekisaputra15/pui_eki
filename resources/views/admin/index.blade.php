@extends('layouts.admin')

@section('content')
@php
$statusBadge = [
    'pending'    => 'badge-yellow',
    'diproses'   => 'badge-blue',
    'dibuat'     => 'badge-green',
    'selesai'    => 'badge-green',
    'dibatalkan' => 'badge-red',
];
$statusLabel = [
    'pending'    => 'Pending',
    'diproses'   => 'Diproses',
    'dibuat'     => 'Dibuat',
    'selesai'    => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
];
@endphp

<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-list-check mr-1.5"></i>Pengelola</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Semua Pesanan</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Rekap & history seluruh pesanan restoran.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-brand" style="font-size: 0.875rem; padding: 8px 16px;">
            <i class="fa-solid fa-utensils mr-2"></i>Produk
        </a>
        <a href="{{ route('admin.tables.index') }}" class="btn btn-brand" style="font-size: 0.875rem; padding: 8px 16px;">
            <i class="fa-solid fa-table-cells mr-2"></i>Meja
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-brand" style="font-size: 0.875rem; padding: 8px 16px;">
            <i class="fa-solid fa-users mr-2"></i>Staff
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="card" style="padding: 16px 20px;">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-ink-faint);">Total Order</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $totalOrders }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Selesai</p>
        <p class="font-bold text-2xl" style="letter-spacing: -0.5px; color: var(--color-ink);">{{ $selesaiCount }}</p>
    </div>
    <div class="card" style="padding: 16px 20px; border-color: rgba(24,226,153,0.3);">
        <p class="t-mono text-[10px] mb-1" style="color: var(--color-brand-deep);">Total Pendapatan</p>
        <p class="font-bold text-lg" style="letter-spacing: -0.3px; color: var(--color-ink);">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Orders Table -->
<div class="card overflow-hidden" style="padding: 0; border-radius: 16px;">
    @if ($orders->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <i class="fa-solid fa-receipt text-3xl mb-4" style="color: var(--color-ink-faint);"></i>
        <p class="font-semibold mb-1" style="color: var(--color-ink);">Belum Ada Pesanan</p>
        <p class="text-sm" style="color: var(--color-ink-muted);">Pesanan akan muncul setelah pelanggan melakukan order.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="data-table" style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Meja</th>
                    <th>Waktu</th>
                    <th>Total</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td>
                        <span class="t-mono text-xs font-semibold" style="color: var(--color-ink);">{{ $o->order_id }}</span>
                    </td>
                    <td>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-table-cells text-xs" style="color: var(--color-ink-faint);"></i>
                            <span class="font-semibold text-sm" style="color: var(--color-ink);">{{ $o->table_id }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="t-mono text-xs" style="color: var(--color-ink-faint);">{{ $o->created_at->format('Y-m-d H:i') }}</span>
                    </td>
                    <td>
                        <span class="font-semibold text-sm" style="color: var(--color-brand-deep);">Rp{{ number_format($o->total, 0, ',', '.') }}</span>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge {{ $statusBadge[$o->status] ?? 'badge-gray' }}">
                            {{ $statusLabel[$o->status] ?? $o->status }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.orders.detail', $o->order_id) }}" class="btn btn-ghost" style="padding: 5px 12px; font-size: 0.8125rem;">
                            <i class="fa-solid fa-eye mr-1 text-xs"></i>Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
