@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
    <div>
        <span class="section-label"><i class="fa-solid fa-receipt mr-1.5"></i>Pengelola / Pesanan</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Detail Pesanan</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Informasi lengkap item, qty, harga, dan status pesanan.</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-ghost" style="padding: 8px 16px; font-size: 0.875rem;">
        <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Summary Card -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card" style="border-radius: 20px;">
            <p class="t-mono text-[10px] mb-4" style="color: var(--color-ink-faint);">Ringkasan Order</p>
            
            <div class="space-y-4">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-ink-faint mb-1">Order ID</p>
                    <p class="t-mono text-sm font-bold" style="color: var(--color-ink);">{{ $order->order_id }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-ink-faint mb-1">Meja</p>
                    <p class="font-bold text-lg" style="color: var(--color-ink);">{{ $order->table_id }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-ink-faint mb-1">Waktu Pesan</p>
                    <p class="text-sm font-medium" style="color: var(--color-ink-secondary);">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-ink-faint mb-1">Status</p>
                    @php
                        $badges = [
                            'pending'    => 'badge-yellow',
                            'diproses'   => 'badge-blue',
                            'dibuat'     => 'badge-green',
                            'selesai'    => 'badge-green',
                            'dibatalkan' => 'badge-red',
                        ];
                    @endphp
                    <span class="badge {{ $badges[$order->status] ?? 'badge-gray' }}">{{ strtoupper($order->status) }}</span>
                </div>
            </div>

            @if ($order->notes)
            <div class="mt-6 pt-6 border-t border-border-subtle">
                <p class="text-[11px] font-medium uppercase tracking-wider text-ink-faint mb-2">Catatan Khusus</p>
                <div class="p-3 rounded-xl bg-surface border border-border-subtle text-sm italic" style="color: var(--color-ink-muted);">
                    "{{ $order->notes }}"
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <div class="lg:col-span-2">
        <div class="card overflow-hidden" style="padding: 0; border-radius: 20px;">
            <div class="px-6 py-4 bg-surface border-b border-border-subtle">
                <p class="t-mono text-[10px]" style="color: var(--color-ink-faint);">Daftar Item ({{ $order->items->count() }})</p>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th style="text-align: right;">Qty</th>
                            <th style="text-align: right;">Harga</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $it)
                        <tr>
                            <td class="font-semibold">{{ $it->name }}</td>
                            <td style="text-align: right;">{{ (int)$it->quantity }}</td>
                            <td style="text-align: right;">Rp{{ number_format($it->price, 0, ',', '.') }}</td>
                            <td style="text-align: right;" class="font-bold text-brand-deep">
                                Rp{{ number_format($it->price * $it->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-surface font-bold">
                            <td colspan="3" class="px-6 py-5 text-right" style="color: var(--color-ink-muted);">Grand Total</td>
                            <td class="px-6 py-5 text-right text-lg" style="color: var(--color-ink);">
                                Rp{{ number_format($order->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
