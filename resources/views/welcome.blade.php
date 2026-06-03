@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden min-h-screen flex flex-col">
    <!-- Atmospheric green gradient background -->
    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(24,226,153,0.18) 0%, rgba(24,226,153,0.05) 45%, transparent 70%), radial-gradient(ellipse 40% 40% at 80% 30%, rgba(15,167,110,0.08) 0%, transparent 60%);"></div>

    <!-- Top Nav -->
    <nav class="relative z-10 flex items-center justify-between px-6 md:px-10 pt-5 pb-3">
        <span class="font-semibold text-xl" style="letter-spacing: -0.4px; color: var(--color-ink);">
            <span style="color: var(--color-brand-deep);">E-</span>MENUGO
        </span>
        @if(auth()->check())
            <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 7px 20px; font-size: 0.875rem;">Ke Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 7px 20px; font-size: 0.875rem;">Login Staff</a>
        @endif
    </nav>

    <!-- Hero Content -->
    <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-6 pt-16 pb-24 max-w-5xl mx-auto w-full">
        <div class="badge badge-green mb-6" style="font-size: 0.6875rem; padding: 5px 14px;">
            Sistem Order Digital Restoran
        </div>

        <h1 class="t-display mb-6 max-w-3xl" style="font-size: clamp(2.5rem, 6vw, 4rem);">
            Pesan makanan di meja<br>
            <span style="color: var(--color-brand-deep);">tanpa antri.</span>
        </h1>

        <p class="t-body-lg max-w-xl mx-auto mb-10">
            E-MENUGO menghadirkan pengalaman memesan makanan digital yang cepat, mudah, dan elegan — langsung dari QR Code di meja Anda.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('customer.notable') }}" class="btn btn-brand" style="padding: 11px 28px; font-size: 1rem;">
                Mulai Pesan Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn btn-ghost" style="padding: 11px 28px; font-size: 1rem;">
                Login Dashboard Staff →
            </a>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-[3px]" style="background: linear-gradient(90deg, transparent, var(--color-brand), transparent);"></div>
</div>

<!-- Features Section -->
<div class="py-24 px-6 border-t border-[rgba(0,0,0,0.05)]">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16">
            <span class="section-label">Fitur Unggulan</span>
            <h2 class="t-heading mt-2" style="font-size: clamp(1.75rem, 4vw, 2.5rem);">Satu platform, semua kebutuhan restoran</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="card" style="border-radius: 20px;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: var(--color-brand-light);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-brand-deep);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <h3 class="t-card-title mb-2">Scan QR, Langsung Pesan</h3>
                <p class="t-body" style="font-size: 0.875rem;">Pelanggan cukup scan QR di meja menggunakan kamera ponsel. Tidak perlu aplikasi tambahan.</p>
            </div>

            <div class="card" style="border-radius: 20px; border-color: rgba(24,226,153,0.3); background: linear-gradient(160deg, #f0fdf8 0%, #ffffff 100%);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: var(--color-brand);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-ink);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="t-card-title mb-2">Monitoring Real-time</h3>
                <p class="t-body" style="font-size: 0.875rem;">Kasir dan dapur melihat pesanan secara langsung. Status pesanan terupdate setiap saat.</p>
                <div class="mt-3 badge badge-green" style="font-size: 0.6rem;">Live Update</div>
            </div>

            <div class="card" style="border-radius: 20px;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: var(--color-brand-light);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-brand-deep);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                </div>
                <h3 class="t-card-title mb-2">Manajemen Meja Cerdas</h3>
                <p class="t-body" style="font-size: 0.875rem;">Waitress clear meja dengan scan QR. Riwayat otomatis tercatat. Kasir generate QR untuk tiap meja.</p>
            </div>
        </div>
    </div>
</div>

<!-- Role Showcase Section -->
<div class="py-20 px-6" style="background: var(--color-surface);">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <span class="section-label">Role-Based Access</span>
            <h2 class="t-heading mt-2" style="font-size: clamp(1.75rem, 4vw, 2.5rem);">Setiap staf, akses yang tepat</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $roles = [
                ['label' => 'Kasir', 'desc' => 'Konfirmasi pembayaran & manage meja', 'icon' => '💳'],
                ['label' => 'Dapur', 'desc' => 'Monitor & selesaikan pesanan masuk', 'icon' => '🍳'],
                ['label' => 'Waitress', 'desc' => 'Scan meja & clear up riwayat', 'icon' => '🛎️'],
                ['label' => 'Pengelola', 'desc' => 'Kelola user, produk, dan laporan', 'icon' => '📊'],
            ];
            @endphp
            @foreach($roles as $r)
            <div class="card text-center" style="border-radius: 16px; padding: 20px 16px;">
                <div class="text-3xl mb-3">{{ $r['icon'] }}</div>
                <p class="font-semibold text-sm mb-1" style="color: var(--color-ink); letter-spacing: -0.1px;">{{ $r['label'] }}</p>
                <p class="text-xs" style="color: var(--color-ink-muted); line-height: 1.5;">{{ $r['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Footer Section -->
<div class="py-24 px-6 text-center relative overflow-hidden" style="background: var(--color-ink);">
    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse 60% 80% at 50% 120%, rgba(24,226,153,0.15) 0%, transparent 70%);"></div>
    <div class="relative z-10 max-w-2xl mx-auto">
        <span class="section-label" style="color: var(--color-brand);">Get Started</span>
        <h2 class="t-heading mt-3 mb-4 text-white" style="font-size: clamp(1.75rem, 4vw, 2.5rem);">Mulai digitalisasi restoran Anda</h2>
        <p class="mb-8" style="color: rgba(255,255,255,0.6); font-size: 1.0625rem; line-height: 1.6;">Sistem siap pakai. Cukup masuk sebagai kasir dan generate QR untuk meja Anda.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @if(auth()->check())
                <a href="{{ route('dashboard') }}" class="btn btn-brand" style="padding: 11px 32px; font-size: 1rem;">Ke Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-brand" style="padding: 11px 32px; font-size: 1rem;">Login Dashboard →</a>
            @endif
            <a href="{{ route('customer.notable') }}" class="btn btn-ghost" style="padding: 11px 28px; font-size: 1rem; background: rgba(255,255,255,0.07); color: white; border-color: rgba(255,255,255,0.15);">Lihat Tampilan Pelanggan</a>
        </div>
    </div>
</div>

<!-- Footer bar -->
<div class="py-5 px-6 text-center border-t border-[rgba(0,0,0,0.05)]">
    <p class="t-mono text-[10px]" style="color: var(--color-ink-faint);">E-MENUGO — Digital Restaurant Ordering System Laravel</p>
</div>
@endsection
