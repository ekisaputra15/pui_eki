@extends('layouts.app')

@section('content')
@php
$statusLabels = [
    'pending'    => 'Menunggu Pembayaran',
    'diproses'   => 'Dikonfirmasi Kasir',
    'dibuat'     => 'Sedang Dibuat',
    'selesai'    => 'Siap Disajikan',
    'dibatalkan' => 'Pesanan Dibatalkan'
];

$statusIcons = [
    'pending'    => 'fa-wallet',
    'diproses'   => 'fa-clock',
    'dibuat'     => 'fa-fire-burner',
    'selesai'    => 'fa-plate-wheat',
    'dibatalkan' => 'fa-circle-xmark'
];

$currentStatus = $order->status ?? 'pending';
@endphp

<div class="px-5 py-8 pb-12">
    <!-- Header -->
    <div class="mb-10">
        <span class="section-label">Order Tracking</span>
        <h1 class="t-heading mt-1" style="font-size: 2rem;">Status Pesanan</h1>
        <p class="t-body mt-2">Dapur kami sedang bekerja keras untuk Anda.</p>
    </div>

    <!-- Status Content -->
    <div id="status-main-content">
        <!-- Order Info Card -->
        <div class="card mb-8" style="border-radius: 24px;">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="t-mono text-[10px]" style="color: var(--color-ink-faint);">{{ $order->order_id }}</span>
                    <h3 class="font-bold text-lg mt-0.5" style="color: var(--color-ink);">Meja {{ $order->table_id }}</h3>
                </div>
                <div class="flex flex-col items-end gap-1.5">
                    <span class="badge {{ ($currentStatus === 'selesai' ? 'badge-green' : ($currentStatus === 'dibatalkan' ? 'badge-red' : 'badge-yellow')) }}">
                        <i class="fa-solid {{ $statusIcons[$currentStatus] }} mr-1.5 text-[8px]"></i>
                        {{ $statusLabels[$currentStatus] }}
                    </span>
                    <p class="t-mono text-[9px]" style="color: var(--color-brand-deep);">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="flex flex-col gap-1.5 border-t border-[rgba(0,0,0,0.05)] pt-4">
                @foreach ($order->items as $it)
                <div class="flex justify-between text-xs">
                    <span style="color: var(--color-ink-muted);">
                        <span class="font-semibold text-brand-deep">{{ $it->quantity }}x</span> {{ $it->name }}
                    </span>
                    <span class="font-medium" style="color: var(--color-ink);">Rp{{ number_format($it->price * $it->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="card relative overflow-hidden" style="border-radius: 24px; padding: 28px 24px;">
            @if ($currentStatus === 'dibatalkan')
                <div class="flex flex-col items-center text-center py-6">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-circle-xmark text-2xl text-red-600"></i>
                    </div>
                    <h4 class="font-bold text-lg" style="color: var(--color-ink);">Pesanan Dibatalkan</h4>
                    <p class="text-sm mt-1" style="color: var(--color-ink-muted);">Pesanan Anda tidak dapat diproses (dibatalkan oleh staf atau Anda sendiri).</p>
                    <a href="{{ route('customer.menu') }}" class="btn btn-ghost mt-6 !px-8">Buat Pesanan Baru</a>
                </div>
            @else
                <!-- Vertical Line -->
                <div class="absolute left-[36px] top-[40px] bottom-[40px] w-0.5 bg-[rgba(0,0,0,0.05)]"></div>
                
                <div class="space-y-10">
                    @php
                    $steps = [
                        ['pending', 'Pesanan Masuk', 'Menunggu konfirmasi pembayaran oleh kasir.'],
                        ['diproses', 'Dikonfirmasi', 'Pesanan telah diterima dan diteruskan ke dapur.'],
                        ['dibuat', 'Dimasak', 'Dapur sedang menyiapkan hidangan spesial Anda.'],
                        ['selesai', 'Selesai', 'Pesanan siap disajikan di meja Anda. Selamat menikmati!']
                    ];
                    
                    $statusOrder = ['pending' => 0, 'diproses' => 1, 'dibuat' => 2, 'selesai' => 3];
                    $currentIdx = $statusOrder[$currentStatus] ?? 0;
                    @endphp
                    
                    @foreach ($steps as $idx => $s)
                        @php
                        $isPast = $idx < $currentIdx;
                        $isCurrent = $idx === $currentIdx;
                        
                        $circleColor = ($isPast || $isCurrent) ? 'background: var(--color-brand); box-shadow: 0 0 0 4px rgba(24,226,153,0.15);' : 'background: rgba(0,0,0,0.05);';
                        $isCurrentCompleted = ($isCurrent && $currentStatus === 'selesai');
                        $iconClass = ($isPast || $isCurrentCompleted) ? 'fa-check' : ($isCurrent ? 'fa-spinner animate-spin-slow' : 'fa-circle text-[4px]');
                        $textColor = ($isPast || $isCurrent) ? 'var(--color-ink)' : 'var(--color-ink-faint)';
                        $subTextColor = ($isPast || $isCurrent) ? 'var(--color-ink-muted)' : 'var(--color-ink-faint)';
                        @endphp
                        
                        <div class="relative flex gap-5 pl-1.5">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center z-10 shrink-0 transition-all duration-500" style="{{ $circleColor }}">
                                <i class="fa-solid {{ $iconClass }} text-[10px] {{ ($isPast || $isCurrent) ? 'text-ink' : 'text-ink-faint' }}"></i>
                            </div>
                            <div class="flex-1 -mt-1">
                                <h4 class="font-bold text-sm transition-colors duration-500" style="color: {{ $textColor }}">{{ $s[1] }}</h4>
                                <p class="text-xs mt-1 transition-colors duration-500" style="color: {{ $subTextColor }}">{{ $s[2] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Controls -->
        <div class="mt-8 space-y-3">
            @if ($currentStatus === 'selesai')
                <a href="{{ route('customer.menu') }}" class="btn btn-brand w-full !py-4 !rounded-2xl flex items-center justify-center gap-2 shadow-lg">
                    <i class="fa-solid fa-cart-plus text-xs"></i> Pesan Lagi
                </a>
            @elseif($currentStatus === 'dibatalkan')
                <a href="{{ route('customer.menu') }}" class="btn btn-brand w-full !py-4 !rounded-2xl flex items-center justify-center gap-2 shadow-lg">
                    <i class="fa-solid fa-cart-plus text-xs"></i> Buat Pesanan Baru
                </a>
            @else
                <button onclick="window.location.reload()" class="btn btn-ghost w-full !py-4 !rounded-2xl flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate text-xs animate-spin-slow"></i> Refresh Status
                </button>
                @if ($currentStatus === 'pending')
                    <form method="POST" action="{{ route('customer.order.cancel') }}" onsubmit="return confirm('Yakin ingin membatalkan pesanan?');">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                        <button type="submit" class="w-full py-3.5 text-sm font-semibold rounded-2xl transition-colors hover:bg-red-50" style="color: #ef4444;">
                            <i class="fa-solid fa-xmark mr-1.5"></i>Batalkan Pesanan
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <!-- Riwayat Pesanan Meja Ini -->
        @if(!empty($tableHistory) && count($tableHistory) > 0)
        <div class="mt-10 pb-6">
            <div class="flex items-center gap-3 mb-4">
                <h3 class="font-bold text-sm" style="color: var(--color-ink);">Riwayat Meja {{ $order->table_id }}</h3>
                <div class="flex-1 h-px bg-[rgba(0,0,0,0.05)]"></div>
                <span class="t-mono text-[9px]" style="color: var(--color-ink-faint);">{{ count($tableHistory) }} pesanan</span>
            </div>
            <div class="space-y-3">
                @foreach ($tableHistory as $hist)
                <div class="card" style="border-radius: 16px; padding: 14px 18px; border-left: 3px solid {{ $hist->status === 'selesai' ? 'var(--color-brand)' : '#ef4444' }};">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <span class="t-mono text-[9px]" style="color: var(--color-ink-faint);">{{ $hist->order_id }}</span>
                            <p class="font-semibold text-sm mt-0.5 truncate" style="color: var(--color-ink);">
                                {{ $hist->items->map(fn($i) => $i->quantity.'x '.$i->name)->implode(', ') }}
                            </p>
                            <p class="text-[10px] mt-0.5" style="color: var(--color-ink-faint);">
                                {{ $hist->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-bold text-sm" style="color: var(--color-brand-deep);">Rp{{ number_format($hist->total, 0, ',', '.') }}</p>
                            <span class="badge mt-1 {{ $hist->status === 'selesai' ? 'badge-green' : '' }}" style="{{ $hist->status !== 'selesai' ? 'background:#fef2f2;color:#dc2626;' : '' }}">
                                {{ $hist->status }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin-slow {
    animation: spin-slow 2s linear infinite;
}
</style>
@endsection
