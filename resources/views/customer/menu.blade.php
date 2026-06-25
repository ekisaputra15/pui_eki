@extends('layouts.app')

@section('content')
@php
$cartCount = 0;
foreach ((session('cart', [])) as $it) {
    $cartCount += $it['quantity'];
}
@endphp

<div class="px-5 py-8 pb-32">
    <!-- Header -->
    <div class="mb-10">
        <span class="section-label">Restoran Menu</span>
        <h1 class="t-heading mt-1" style="font-size: 2rem;">Pilih Menu Favorit</h1>
        <p class="t-body mt-2">Pilih hidangan lezat kami langsung dari meja Anda.</p>
    </div>

    @foreach ($categories as $catName => $items)
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <h2 class="font-bold text-lg" style="color: var(--color-ink);">{{ $catName }}</h2>
            <div class="flex-1 h-px bg-[rgba(0,0,0,0.05)]"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($items as $item)
            @php $qty = session("cart.{$item->id}.quantity", 0); @endphp
            <div class="card overflow-hidden group" style="padding: 0; border-radius: 20px; transition: transform 0.2s ease; {{ $qty > 0 ? 'border-color: var(--color-brand);' : '' }}">
                <!-- Product Image -->
                <div class="relative aspect-[16/10] bg-surface overflow-hidden">
                    @if (!empty($item->image))
                        <img src="{{ $item->image }}" alt="{{ $item->name }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <i class="fa-solid fa-utensils text-4xl text-[rgba(136,136,136,0.3)]"></i>
                            <span class="text-xs font-medium text-ink-faint">No Image Preview</span>
                        </div>
                    @endif
                    
                    @if ($qty > 0)
                    <div class="absolute inset-0 bg-brand/10 backdrop-blur-[1px]" style="background: rgba(24,226,153,0.1); backdrop-filter: blur(1px);"></div>
                    <div class="absolute top-4 right-4 bg-brand px-3 py-1 rounded-full shadow-lg flex items-center gap-1.5" style="background: var(--color-brand);">
                        <i class="fa-solid fa-circle-check text-[10px] text-ink"></i>
                        <span class="text-xs font-bold text-ink">{{ $qty }} Terpilih</span>
                    </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-5">
                    <div class="flex justify-between items-start gap-3 mb-2">
                        <h3 class="font-bold text-lg leading-tight" style="color: var(--color-ink); letter-spacing: -0.2px;">{{ $item->name }}</h3>
                        <p class="font-bold text-lg" style="color: var(--color-brand-deep); flex-shrink: 0;">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    
                    <p class="text-sm line-clamp-2 mb-6" style="color: var(--color-ink-muted); min-height: 2.5rem; line-height: 1.5;">
                        {{ $item->description ?? 'Hidangan istimewa yang diolah dengan bahan pilihan terbaik.' }}
                    </p>

                    <div class="flex items-center gap-3">
                        @if ($qty > 0)
                            <div class="flex-1 flex gap-2 items-center">
                                <form method="POST" action="{{ route('customer.cart.update') }}" class="shrink-0 flex items-center">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all bg-surface border border-border hover:bg-surface-hover" style="background: #fafafa; border: 1px solid rgba(0,0,0,0.08);">
                                        <i class="fa-solid fa-minus text-ink-muted"></i>
                                    </button>
                                </form>
                                
                                <div class="flex-1 h-12 rounded-2xl flex items-center justify-center font-bold text-lg" style="background: #fafafa; border: 1px solid rgba(0,0,0,0.08);">
                                    {{ $qty }}
                                </div>
                                
                                <form method="POST" action="{{ route('customer.cart.update') }}" class="shrink-0 flex items-center">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-md" style="background: var(--color-brand); color: var(--color-ink);">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('customer.cart.update') }}" class="w-full">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-brand w-full !py-3.5 !rounded-2xl flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-cart-plus text-xs"></i>
                                    Tambah ke Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

@if ($cartCount > 0)
<div class="fixed bottom-0 left-0 right-0 p-6 z-40 bg-white/80 backdrop-blur-md border-t border-[rgba(0,0,0,0.05)]">
    <div class="max-w-md mx-auto">
        <a href="{{ route('customer.cart') }}" class="btn btn-primary w-full !py-4 !rounded-2xl flex justify-between items-center px-6 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="bg-brand text-ink w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs" style="background: var(--color-brand); color: var(--color-ink);">{{ $cartCount }}</div>
                <span class="font-semibold">Review Pesanan</span>
            </div>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>
@endif
@endsection
