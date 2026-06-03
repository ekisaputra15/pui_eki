@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <span class="section-label"><i class="fa-solid fa-broom mr-1.5"></i>Waitress</span>
    <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Clear Up Scanner</h1>
    <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Pindai QR Code di meja untuk menandai meja telah dibersihkan.</p>
</div>

<div class="max-w-sm mx-auto">
    <!-- Scanner Card -->
    <div class="card overflow-hidden mb-6" style="padding: 0; border-radius: 24px;">
        
        <!-- Card Header: Status Bar -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-[rgba(0,0,0,0.05)]">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: var(--color-brand-light);">
                <i class="fa-solid fa-qrcode text-sm" style="color: var(--color-brand-deep);"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-sm" style="color: var(--color-ink);">Kamera Scanner</p>
                <p class="text-[10px]" style="color: var(--color-ink-muted);">Arahkan ke QR Code meja</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full" style="background: var(--color-brand-light);">
                <div class="w-1.5 h-1.5 rounded-full animate-pulse-green" style="background: var(--color-brand-deep);"></div>
                <span class="t-mono text-[9px]" style="color: var(--color-brand-deep);">LIVE</span>
            </div>
        </div>

        <!-- Camera View -->
        <div class="bg-black relative" style="aspect-ratio: 1 / 1;">
            <!-- QR Scanner Target -->
            <div id="qr-reader" style="width: 100%; height: 100%;"></div>
            
            <!-- Corner Overlay (decorative viewfinder) -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                <div class="relative" style="width: 200px; height: 200px;">
                    <div style="position:absolute;top:0;left:0;width:32px;height:32px;border-top:3px solid var(--color-brand);border-left:3px solid var(--color-brand);border-radius:8px 0 0 0;"></div>
                    <div style="position:absolute;top:0;right:0;width:32px;height:32px;border-top:3px solid var(--color-brand);border-right:3px solid var(--color-brand);border-radius:0 8px 0 0;"></div>
                    <div style="position:absolute;bottom:0;left:0;width:32px;height:32px;border-bottom:3px solid var(--color-brand);border-left:3px solid var(--color-brand);border-radius:0 0 0 8px;"></div>
                    <div style="position:absolute;bottom:0;right:0;width:32px;height:32px;border-bottom:3px solid var(--color-brand);border-right:3px solid var(--color-brand);border-radius:0 0 8px 0;"></div>
                    <!-- Scanning line animation -->
                    <div class="scan-line"></div>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="px-5 py-4 text-center" style="background: var(--color-surface);">
            <p id="scan-status" class="t-mono text-[10px]" style="color: var(--color-ink-faint);">Menunggu pemindaian...</p>
        </div>
    </div>

    <!-- Rescan Button -->
    <button id="rescan-btn" onclick="rescan()" class="btn btn-ghost w-full !rounded-2xl !py-4 gap-3 hidden">
        <i class="fa-solid fa-rotate-right"></i>
        <span>Pindai Meja Lain</span>
    </button>

    <!-- Result Notice (inline, no modal) -->
    <div id="result-notice" class="hidden mt-4 p-5 rounded-2xl" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex items-center gap-4">
            <div id="result-icon" class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"></div>
            <div>
                <p id="result-title" class="font-semibold text-sm" style="color: var(--color-ink);"></p>
                <p id="result-body" class="text-xs mt-0.5" style="color: var(--color-ink-muted);"></p>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('waitress.index') }}" class="text-sm" style="color: var(--color-ink-faint); text-decoration: none;">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>

<script>
let qrScanner = null;

function startScanner() {
    qrScanner = new Html5Qrcode("qr-reader");

    qrScanner.start(
        { facingMode: "environment" },
        { fps: 15, qrbox: { width: 200, height: 200 } },
        onScanSuccess
    ).then(() => {
        setScanStatus("Siap — arahkan ke QR Code meja...");
    }).catch(err => {
        setScanStatus("❌ Gagal akses kamera: " + err);
        document.getElementById('result-notice').classList.remove('hidden');
        document.getElementById('result-icon').innerHTML = '<i class="fa-solid fa-video-slash text-xl text-red-500"></i>';
        document.getElementById('result-icon').style.background = '#fef2f2';
        document.getElementById('result-title').textContent = 'Akses Kamera Ditolak';
        document.getElementById('result-body').textContent = 'Browser memblokir kamera. Pastikan Anda mengakses via HTTPS atau localhost, lalu izinkan kamera.';
        document.getElementById('result-body').style.color = '#ef4444';
    });
}

function setScanStatus(msg) {
    document.getElementById('scan-status').textContent = msg;
}

function onScanSuccess(decodedText) {
    let tableId = null;
    try {
        if (decodedText.startsWith('http')) {
            const url = new URL(decodedText);
            const parts = url.pathname.split('/');
            const scanIdx = parts.indexOf('scan');
            tableId = scanIdx !== -1 && parts[scanIdx + 1] ? parts[scanIdx + 1] : url.searchParams.get('table');
        } else {
            tableId = decodedText.trim();
        }
    } catch(e) { tableId = decodedText.trim(); }

    if (tableId) {
        if (qrScanner) qrScanner.pause(true);
        setScanStatus(`QR Terdeteksi: Meja ${tableId}`);
        processClearUp(tableId);
    } else {
        showResult('error', 'Format Tidak Dikenal', 'QR Code tidak dapat dikenali sebagai label meja.');
    }
}

function processClearUp(tableId) {
    showResult('loading', 'Memproses...', `Menandai Meja ${tableId} telah dibersihkan...`);

    fetch('{{ route("waitress.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ table_id: tableId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showResult('success', 'Berhasil!', data.message);
        } else {
            showResult('error', 'Gagal', data.message);
        }
        document.getElementById('rescan-btn').classList.remove('hidden');
    })
    .catch(() => {
        showResult('error', 'Koneksi Gagal', 'Tidak dapat menghubungi server. Coba lagi.');
        document.getElementById('rescan-btn').classList.remove('hidden');
    });
}

function showResult(type, title, body) {
    const notice = document.getElementById('result-notice');
    const icon = document.getElementById('result-icon');
    const titleEl = document.getElementById('result-title');
    const bodyEl = document.getElementById('result-body');

    titleEl.textContent = title;
    bodyEl.textContent = body;
    bodyEl.style.color = '';
    notice.classList.remove('hidden');

    const configs = {
        loading: {
            bg: '#f9fafb',
            color: '#9ca3af',
            html: '<i class="fa-solid fa-spinner fa-spin text-xl"></i>',
            border: 'rgba(0,0,0,0.08)',
        },
        success: {
            bg: 'var(--color-brand-light)',
            color: 'var(--color-brand-deep)',
            html: '<i class="fa-solid fa-check text-xl"></i>',
            border: 'rgba(24,226,153,0.25)',
        },
        error: {
            bg: '#fef2f2',
            color: '#dc2626',
            html: '<i class="fa-solid fa-xmark text-xl"></i>',
            border: 'rgba(239,68,68,0.2)',
        },
    };
    const cfg = configs[type] || configs['error'];
    icon.style.background = cfg.bg;
    icon.style.color = cfg.color;
    icon.innerHTML = cfg.html;
    notice.style.borderColor = cfg.border;
}

function rescan() {
    document.getElementById('result-notice').classList.add('hidden');
    document.getElementById('rescan-btn').classList.add('hidden');
    setScanStatus('Siap — menunggu pemindaian...');

    if (qrScanner) {
        qrScanner.resume();
    } else {
        startScanner();
    }
}

document.addEventListener('DOMContentLoaded', startScanner);
</script>

<style>
#qr-reader { width: 100% !important; height: 100% !important; border: none !important; }
#qr-reader video { object-fit: cover; width: 100% !important; height: 100% !important; }
#qr-reader img { display: none !important; }
#qr-reader__dashboard { display: none !important; }
#qr-reader__status_span { display: none !important; }
#qr-reader__header_message { display: none !important; }

.scan-line {
    position: absolute;
    top: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--color-brand), transparent);
    animation: scan 2s linear infinite;
    border-radius: 2px;
}
@keyframes scan {
    0% { top: 0px; opacity: 1; }
    90% { opacity: 1; }
    100% { top: 198px; opacity: 0; }
}
@keyframes pulse-green {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
.animate-pulse-green { animation: pulse-green 1.5s ease-in-out infinite; }
</style>
@endsection
