@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex items-start justify-between">
    <div>
        <span class="section-label"><i class="fa-solid fa-broom mr-1.5"></i>Waitress</span>
        <h1 class="font-semibold mt-1" style="font-size: 1.75rem; letter-spacing: -0.5px; color: var(--color-ink);">Clear Up Scanner</h1>
        <p style="color: var(--color-ink-muted); font-size: 0.9375rem; margin-top: 4px;">Scan QR Code meja untuk menandai selesai dibersihkan.</p>
    </div>
    <a href="{{ route('waitress.history') }}" class="btn btn-ghost" style="font-size: 0.875rem; padding: 8px 14px;">
        <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs"></i>Riwayat
    </a>
</div>

<div class="max-w-[480px] mx-auto">

    <!-- Scanner Card -->
    <div class="card mb-4" style="border-radius: 20px; padding: 0; overflow: hidden;">
        <div class="px-5 py-4 flex items-center gap-3" style="border-bottom: 1px solid var(--color-border-subtle); background: var(--color-surface);">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--color-brand-light);">
                <i class="fa-solid fa-qrcode text-sm" style="color: var(--color-brand-deep);"></i>
            </div>
            <div>
                <p class="font-semibold text-sm" style="color: var(--color-ink);">Kamera Scanner</p>
                <p class="t-mono text-[10px]" style="color: var(--color-ink-faint);">Arahkan ke QR Code meja</p>
            </div>
            <div class="ml-auto flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background: var(--color-brand-light); border: 1px solid rgba(24,226,153,0.2);">
                <div class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: var(--color-brand);"></div>
                <span class="t-mono text-[10px]" style="color: var(--color-brand-deep);">Live</span>
            </div>
        </div>
        <div class="bg-[#0d0d0d] relative overflow-hidden" style="aspect-ratio: 1;">
            <div id="reader" style="width: 100%;"></div>
            <!-- Crosshair overlay -->
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                <div style="width: 200px; height: 200px; position: relative;">
                    <!-- Corners -->
                    <div style="position:absolute;top:0;left:0;width:28px;height:28px;border-top:3px solid #18E299;border-left:3px solid #18E299;border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute;top:0;right:0;width:28px;height:28px;border-top:3px solid #18E299;border-right:3px solid #18E299;border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute;bottom:0;left:0;width:28px;height:28px;border-bottom:3px solid #18E299;border-left:3px solid #18E299;border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute;bottom:0;right:0;width:28px;height:28px;border-bottom:3px solid #18E299;border-right:3px solid #18E299;border-radius:0 0 4px 0;"></div>
                </div>
            </div>
        </div>

        <div class="px-5 py-3" style="border-top: 1px solid var(--color-border-subtle);">
            <p id="ready-status" class="text-center t-mono text-[10px]" style="color: var(--color-ink-faint);">Menunggu pemindaian...</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('waitress.history') }}" class="card flex items-center gap-3" style="border-radius: 14px; padding: 14px 16px; text-decoration: none;">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--color-brand-light);">
                <i class="fa-solid fa-clock-rotate-left text-sm" style="color: var(--color-brand-deep);"></i>
            </div>
            <div>
                <p class="font-semibold text-sm" style="color: var(--color-ink);">Riwayat</p>
                <p class="text-xs" style="color: var(--color-ink-muted);">Lihat log</p>
            </div>
        </a>
        <button onclick="if(html5QrcodeScanner) html5QrcodeScanner.resume();"
                class="card flex items-center gap-3" style="border-radius: 14px; padding: 14px 16px; cursor: pointer; background: white; border: 1px solid var(--color-border-subtle); text-align: left;">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--color-brand);">
                <i class="fa-solid fa-rotate-right text-sm" style="color: var(--color-ink);"></i>
            </div>
            <div>
                <p class="font-semibold text-sm" style="color: var(--color-ink);">Scan Lagi</p>
                <p class="text-xs" style="color: var(--color-ink-muted);">Resume kamera</p>
            </div>
        </button>
    </div>
</div>

<!-- Scan Result Modal -->
<div id="scan-result-modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 backdrop-blur-sm">
    <div id="scan-result-card" class="bg-white w-full max-w-sm rounded-2xl shadow-modal p-6" style="border: 1px solid var(--color-border-subtle);">
        <div class="flex items-start gap-4 mb-5">
            <div id="scan-result-icon" class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"></div>
            <div class="flex-1 min-w-0">
                <p id="scan-result-title" class="font-semibold" style="color: var(--color-ink); letter-spacing: -0.1px;"></p>
                <p id="scan-result-body" class="text-sm mt-1" style="color: var(--color-ink-muted);"></p>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" onclick="closeScanModalAndResume()" class="btn btn-brand" style="padding: 8px 20px; font-size: 0.875rem;">
                <i class="fa-solid fa-check mr-1.5 text-xs"></i>OK
            </button>
        </div>
    </div>
</div>

<script>
let html5QrcodeScanner = null;

function onScanSuccess(decodedText) {
    console.log("QR Decoded:", decodedText);
    let tableId = null;
    try {
        const url = new URL(decodedText);
        // Try getting from query param (Native style)
        tableId = url.searchParams.get("table");
        
        // If not found, try getting from path (Laravel style: /scan/A1)
        if (!tableId) {
            const parts = url.pathname.split('/');
            // Check if it's the /scan/ route
            const scanIdx = parts.indexOf('scan');
            if (scanIdx !== -1 && parts[scanIdx + 1]) {
                tableId = parts[scanIdx + 1];
            }
        }
    } catch(e) { 
        // If it's not a valid URL, it might be just the raw table ID
        tableId = decodedText; 
    }
    
    if (tableId) {
        html5QrcodeScanner.pause(true);
        processClearUp(tableId);
    } else {
        showScanModal("error", "QR Tidak Dikenali", "QR Code ini bukan QR meja E-MENUGO yang valid.");
    }
}

function processClearUp(tableId) {
    showScanModal("loading", "Memproses...", `Memperbarui status Meja ${tableId}...`, false);
    
    const formData = new FormData();
    formData.append('table_id', tableId);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("waitress.clear") }}', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showScanModal("success", "Berhasil!", `Meja ${tableId} sekarang berstatus BERSIH.`);
            } else {
                showScanModal("error", "Gagal", data.message || "Proses clear up gagal.");
            }
            setTimeout(() => closeScanModalAndResume(), 2800);
        })
        .catch(() => {
            showScanModal("error", "Koneksi Error", "Terjadi kesalahan sambungan. Coba lagi.");
            setTimeout(() => closeScanModalAndResume(), 2800);
        });
}

function showScanModal(type, title, body, showButton = true) {
    const modal = document.getElementById('scan-result-modal');
    const card  = document.getElementById('scan-result-card');
    const icon  = document.getElementById('scan-result-icon');
    const titleEl = document.getElementById('scan-result-title');
    const bodyEl  = document.getElementById('scan-result-body');
    const btn = modal.querySelector('button');

    modal.classList.remove('hidden'); modal.classList.add('flex');
    titleEl.innerText = title; bodyEl.innerText = body;
    btn.style.display = showButton ? '' : 'none';

    if (type === 'loading') {
        card.style.borderColor = 'var(--color-border-subtle)';
        icon.style.cssText = 'background:#f5f5f5; width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center;';
        icon.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-lg" style="color:var(--color-ink-muted);"></i>';
    } else if (type === 'success') {
        card.style.borderColor = 'rgba(24,226,153,0.4)';
        icon.style.cssText = 'background:var(--color-brand); width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center;';
        icon.innerHTML = '<i class="fa-solid fa-check text-lg" style="color:#0d0d0d;"></i>';
    } else {
        card.style.borderColor = 'rgba(220,38,38,0.3)';
        icon.style.cssText = 'background:#fee2e2; width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center;';
        icon.innerHTML = '<i class="fa-solid fa-xmark text-lg" style="color:#dc2626;"></i>';
    }
}

function closeScanModalAndResume() {
    const modal = document.getElementById('scan-result-modal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
    if (html5QrcodeScanner) html5QrcodeScanner.resume();
}

function onScanFailure(error) {
    // Quiet failure
}

window.addEventListener('load', () => {
    html5QrcodeScanner = new Html5QrcodeScanner("reader",
        { fps: 15, qrbox: {width: 220, height: 220}, aspectRatio: 1.0 },
        false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>
@endsection
