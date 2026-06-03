@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[75vh] px-6 py-12 text-center">

    <!-- Icon -->
    <div class="relative mb-8">
        <div class="w-24 h-24 rounded-[28px] flex items-center justify-center" style="background: var(--color-brand-light);">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-brand-deep);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <!-- Pulsing ring (Animated) -->
        <div class="absolute inset-0 rounded-[28px] animate-ping-slow" style="background: rgba(24,226,153,0.15);"></div>
    </div>

    <!-- Title -->
    <div class="badge badge-green mb-4">Scan Required</div>
    <h1 class="font-semibold mb-3" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink); line-height: 1.2;">
        Scan QR Code<br>di Meja Anda
    </h1>
    <p class="mb-10" style="color: var(--color-ink-muted); max-width: 280px; line-height: 1.65; font-size: 0.9375rem;">
        Gunakan aplikasi kamera ponsel Anda atau aktifkan scanner web untuk memindai QR Code di meja.
    </p>

    <!-- Scanner Container (Hidden by default) -->
    <div id="qr-reader-container" class="hidden w-full max-w-sm mb-10 overflow-hidden rounded-3xl shadow-modal border-2 border-brand relative bg-black">
        <div id="qr-reader" style="width: 100%;"></div>
        <button onclick="stopScanner()" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/40 text-white backdrop-blur-md flex items-center justify-center z-20">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Scanner Action -->
    <div id="scanner-action" class="mb-10">
        <button onclick="startScanner()" class="btn btn-brand !px-10 !py-4 shadow-xl flex items-center gap-3">
            <i class="fa-solid fa-camera"></i>
            <span>Buka Scanner Kamera</span>
        </button>
        <p class="mt-4 text-[11px]" style="color: var(--color-ink-faint);">Scanner membutuhkan izin akses kamera.</p>
    </div>

    <!-- Steps -->
    <div class="w-full max-w-xs space-y-3 text-left">
        @php
        $steps = [
            ['Buka Kamera', 'Buka kamera bawaan HP atau gunakan tombol di atas'],
            ['Scan QR Meja', 'Arahkan ke stiker QR yang tertempel di meja Anda'],
            ['Mulai Pesan', 'Klik link yang muncul & pilih menu favorit!'],
        ];
        @endphp
        @foreach($steps as $idx => $s)
        <div class="flex items-center gap-4 p-4 rounded-2xl" style="background: var(--color-surface); border: 1px solid var(--color-border-subtle);">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 font-semibold text-sm text-ink" style="background: var(--color-brand);">
                {{ $idx + 1 }}
            </div>
            <div>
                <p class="font-medium text-sm" style="color: var(--color-ink); letter-spacing: -0.1px;">{{ $s[0] }}</p>
                <p class="text-xs mt-0.5" style="color: var(--color-ink-muted);">{{ $s[1] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Fallback Manual Input -->
    <form action="#" method="GET" class="mt-10 pt-8 border-t border-[rgba(0,0,0,0.06)] w-full max-w-xs" onsubmit="event.preventDefault(); window.location.href='/scan/' + document.getElementById('table_input').value;">
        <p class="t-mono text-[9px] mb-4 text-center" style="color: var(--color-ink-faint);">Atau Masukkan Manual</p>
        <div class="flex gap-2">
            <input type="text" id="table_input" class="input-base" placeholder="No. Meja..." required>
            <button type="submit" class="btn btn-primary shrink-0 !px-5 !rounded-lg">
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </form>

    <div class="mt-10">
        <a href="{{ route('home') }}" style="font-size: 0.875rem; color: var(--color-ink-faint); text-decoration: none; display: flex; align-items: center; gap: 4px;">
            ← Kembali ke Beranda
        </a>
    </div>
</div>

<script>
let html5QrcodeScanner = null;

function startScanner() {
    document.getElementById('scanner-action').classList.add('hidden');
    document.getElementById('qr-reader-container').classList.remove('hidden');
    
    html5QrcodeScanner = new Html5Qrcode("qr-reader");
    const config = { fps: 20, qrbox: { width: 250, height: 250 } };
    
    html5QrcodeScanner.start(
        { facingMode: "environment" }, 
        config, 
        onScanSuccess
    ).catch(err => {
        alert("Gagal mengakses kamera: " + err);
        stopScanner();
    });
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            document.getElementById('qr-reader-container').classList.add('hidden');
            document.getElementById('scanner-action').classList.remove('hidden');
        }).catch(() => {
            document.getElementById('qr-reader-container').classList.add('hidden');
            document.getElementById('scanner-action').classList.remove('hidden');
        });
    }
}

function onScanSuccess(decodedText) {
    console.log("Scanned:", decodedText);
    try {
        const url = new URL(decodedText);
        // Direct redirect for absolute URLs
        window.location.href = decodedText;
    } catch(e) {
        // Fallback for relative paths or just IDs
        if (decodedText.startsWith('/scan/')) {
            window.location.href = decodedText;
        } else {
            window.location.href = '/scan/' + decodedText;
        }
    }
}
</script>

<style>
@keyframes ping-slow {
  0% { transform: scale(1); opacity: 0.8; }
  70%, 100% { transform: scale(1.6); opacity: 0; }
}
.animate-ping-slow {
  animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
@endsection
