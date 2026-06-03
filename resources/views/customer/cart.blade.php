@extends('layouts.app')

@section('content')
@php
$cart = session('cart', []);
$grandTotal = 0;
foreach ($cart as $id => $cItem) {
    // Note: In real app, you'd fetch latest prices from DB here
    $grandTotal += ($cItem['price'] ?? 0) * $cItem['quantity'];
}
@endphp

<div class="px-5 py-8 pb-12 overflow-hidden">
    <!-- Header -->
    <div class="mb-10 flex justify-between items-start gap-4">
        <div>
            <span class="section-label">Konfirmasi Order</span>
            <h1 class="t-heading mt-1" style="font-size: 2rem;">Review Pesanan</h1>
            <p class="t-body mt-2">Pastikan semua menu yang Anda pilih sudah benar.</p>
        </div>
        <a href="{{ route('customer.menu') }}" class="btn btn-ghost !p-3 !rounded-2xl shrink-0">
            <i class="fa-solid fa-plus text-xs"></i>
        </a>
    </div>

    @if (empty($cart))
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 rounded-[24px] bg-surface border border-[rgba(0,0,0,0.05)] flex items-center justify-center mb-6">
                <i class="fa-solid fa-cart-shopping text-2xl text-ink-faint" style="color: #888888; opacity: 0.5;"></i>
            </div>
            <p class="font-semibold text-lg mb-2" style="color: var(--color-ink);">Keranjang Kosong</p>
            <p class="text-sm mb-8" style="color: var(--color-ink-muted);">Anda belum memilih menu apapun.</p>
            <a href="{{ route('customer.menu') }}" class="btn btn-brand !px-8 !rounded-2xl">Lihat Menu Sekarang</a>
        </div>
    @else
        <div class="space-y-6">
            <!-- Order List Card -->
            <div class="card" style="padding: 0; border-radius: 24px; overflow: hidden;">
                <div class="px-6 py-4 bg-surface border-b border-[rgba(0,0,0,0.05)]">
                    <span class="t-mono text-[10px] text-ink-faint">Daftar Item</span>
                </div>
                <div class="divide-y divide-[rgba(0,0,0,0.05)]">
                    @foreach ($cart as $id => $cItem)
                    <div class="px-6 py-5 flex justify-between items-start gap-4">
                        <div class="min-w-0">
                            <h4 class="font-bold text-base leading-tight mb-1" style="color: var(--color-ink);">{{ $cItem['name'] }}</h4>
                            <p class="t-mono text-[11px]" style="color: var(--color-ink-muted);">
                                <span class="font-bold text-brand-deep">{{ $cItem['quantity'] }}</span> x Rp{{ number_format($cItem['price'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="font-bold text-base shrink-0" style="color: var(--color-ink);">
                            Rp{{ number_format(($cItem['price'] ?? 0) * $cItem['quantity'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Total Footer -->
                <div class="px-6 py-5" style="background: var(--color-brand-light);">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-brand-deep">Total Pembayaran</span>
                        <span class="font-bold text-xl text-brand-deep">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Submission -->
            <form method="POST" action="{{ route('customer.checkout') }}">
                @csrf
                <div class="card mb-6" style="border-radius: 20px; padding: 20px;">
                    <label class="block mb-2 font-semibold text-sm" style="color: var(--color-ink);">Pesan Khusus (Opsional)</label>
                    <textarea name="notes" rows="3" class="input-base" style="background: var(--color-surface); border-color: rgba(0,0,0,0.05);" 
                              placeholder="Contoh: jangan pedas, tanpa bawang, dll."></textarea>
                    <p class="mt-2 text-[11px]" style="color: var(--color-ink-faint);">Catatan akan diteruskan langsung ke bagian dapur.</p>
                </div>

                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full !py-4.5 !text-base !rounded-2xl shadow-[0_8px_20px_rgba(13,13,13,0.15)] flex items-center justify-center gap-3">
                        <i class="fa-solid fa-paper-plane text-xs text-brand"></i>
                        Kirim Pesanan Ke Dapur
                    </button>
                    <a href="{{ route('customer.menu') }}" class="btn btn-ghost w-full !py-4 !rounded-2xl">
                        Kembali & Tambah Menu
                    </a>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
