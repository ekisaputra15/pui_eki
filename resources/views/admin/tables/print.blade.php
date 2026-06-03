<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QR Code Meja - E-MenuGo</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --red: #1E8C45;
      --red-dark: #166B34;
      --white: #FFFFFF;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: #e8e8e8;
      font-family: 'Montserrat', sans-serif;
      display: flex;
      flex-wrap: wrap;
      gap: 12mm;
      padding: 20mm;
      justify-content: flex-start;
      align-items: flex-start;
    }
    .card {
      width: 90mm;
      height: 90mm;
      background: var(--red);
      border-radius: 8mm;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      padding: 5mm 5mm 4mm 5mm;
      box-shadow: 0 4px 18px rgba(0,0,0,0.18);
      page-break-inside: avoid;
    }
    .card::before {
      content: '';
      position: absolute;
      right: -8mm;
      top: 50%;
      transform: translateY(-50%);
      width: 45mm;
      height: 45mm;
      background: rgba(255,255,255,0.07);
      clip-path: polygon(0 25%, 50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%);
      pointer-events: none;
    }
    .card::after {
      content: '';
      position: absolute;
      right: 2mm;
      top: 50%;
      transform: translateY(-50%);
      width: 32mm;
      height: 32mm;
      background: rgba(255,255,255,0.05);
      clip-path: polygon(0 25%, 50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%);
      pointer-events: none;
    }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3mm; }
    .logo-brand { text-align: left; color: var(--white); }
    .logo-brand .brand-icon { font-size: 5mm; font-weight: 900; line-height: 1; }
    .logo-brand .brand-name { font-size: 4mm; font-weight: 700; letter-spacing: 0.5mm; display: block; }
    .title {
      color: var(--white);
      font-size: 6mm;
      font-weight: 900;
      letter-spacing: 0.3mm;
      display: flex;
      align-items: center;
      gap: 2mm;
      margin-bottom: 3mm;
      text-transform: uppercase;
      line-height: 1;
    }
    .title .highlight {
      background: var(--white);
      color: var(--red);
      padding: 0.8mm 2mm;
      border-radius: 1.5mm;
      font-size: 5.5mm;
    }
    .body { display: flex; flex: 1; gap: 3mm; }
    .qr-section { display: flex; flex-direction: column; align-items: flex-start; gap: 2.5mm; flex-shrink: 0; }
    .qr-wrapper {
      background: var(--white);
      border-radius: 2.5mm;
      padding: 2.5mm;
      width: 32mm;
      height: 32mm;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .qr-wrapper img { width: 100%; height: 100%; object-fit: contain; display: block; }
    .table-info { color: var(--white); }
    .table-label { font-size: 2.8mm; font-weight: 600; opacity: 0.85; letter-spacing: 0.2mm; }
    .table-number { font-size: 13mm; font-weight: 900; line-height: 1; letter-spacing: -0.5mm; }
    .steps-section { flex: 1; display: flex; flex-direction: column; gap: 2mm; justify-content: center; padding-top: 1mm; }
    .step { display: flex; align-items: center; gap: 2mm; }
    .step-num {
      width: 3.5mm;
      height: 3.5mm;
      background: rgba(255,255,255,0.25);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8mm;
      font-weight: 800;
      color: var(--white);
      flex-shrink: 0;
    }
    .step-icon {
      width: 7mm;
      height: 7mm;
      background: rgba(255,255,255,0.15);
      border-radius: 1.5mm;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .step-icon svg { width: 4.5mm; height: 4.5mm; fill: var(--white); }
    .step-text { color: var(--white); }
    .step-title { font-size: 3.8mm; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2mm; line-height: 1.1; }
    .step-sub { font-size: 2mm; font-weight: 500; opacity: 0.8; letter-spacing: 0.1mm; }
    @media print {
      body { background: white; padding: 10mm; gap: 8mm; }
      .card { box-shadow: none; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      .no-print { display: none !important; }
    }
    .controls {
      position: fixed;
      bottom: 16px;
      right: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      z-index: 999;
    }
    .btn {
      background: #1a1a1a;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .btn:hover { background: var(--red); }
  </style>
</head>
<body>
    @foreach ($tables as $table)
    @php
      $tableId = $table->name;
      $host = request()->getHost();
      if (in_array($host, ['localhost', '127.0.0.1', '0.0.0.0'])) {
          $host = gethostbyname(gethostname());
      }
      $scanUrl = request()->getScheme() . '://' . $host . (request()->getPort() ? ':' . request()->getPort() : '') . '/scan/' . $tableId;
      $qrSource = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=0&data=' . urlencode($scanUrl);
    @endphp
    <div class="card" data-table="{{ $tableId }}">
      <div class="header">
        <div class="logo-brand">
          <span class="brand-icon">QR</span>
          <span class="brand-name">E-MenuGo</span>
        </div>
      </div>

      <div class="title">
        PESAN DI SINI <span class="highlight">TANPA ANTRI</span>
      </div>

      <div class="body">
        <div class="qr-section">
          <div class="qr-wrapper">
            <img class="qr-img" src="{{ $qrSource }}" alt="QR Code Meja {{ $tableId }}">
          </div>
          <div class="table-info">
            <div class="table-label">Meja No.</div>
            <div class="table-number">{{ $tableId }}</div>
          </div>
        </div>

        <div class="steps-section">
          <div class="step">
            <div class="step-num">1</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm2 2v3h3V5H5zm9-2h7v7h-7V3zm2 2v3h3V5h-3zM3 14h7v7H3v-7zm2 2v3h3v-3H5zm11 0h2v2h-2v-2zm2-2h2v2h-2v-2zm0 4h2v2h-2v-2zm-4 0h2v2h-2v-2zm0-4h2v2h-2v-2zm2 2h2v2h-2v-2z"/></svg>
            </div>
            <div class="step-text">
              <div class="step-title">SCAN</div>
              <div class="step-sub">QR Code</div>
            </div>
          </div>
          <div class="step">
            <div class="step-num">2</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
            </div>
            <div class="step-text">
              <div class="step-title">Konfirmasi</div>
              <div class="step-sub">Pembayaran ke Kasir</div>
            </div>
          </div>
          <div class="step">
            <div class="step-num">3</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            </div>
            <div class="step-text">
              <div class="step-title">Cek Status</div>
              <div class="step-sub">Pesanan</div>
            </div>
          </div>
          <div class="step">
            <div class="step-num">4</div>
            <div class="step-icon">
              <svg viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 21.99V21h15.03v.99c0 .55-.45 1-1.01 1H2.01c-.56 0-1.01-.45-1.01-1zm15.03-7c0-2.87-7.51-3.66-7.51-3.66s-7.51.79-7.51 3.66v1.5h15.02v-1.5z"/></svg>
            </div>
            <div class="step-text">
              <div class="step-title">Pesanan</div>
              <div class="step-sub">Selesai / Diantar</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach

  <div class="controls no-print">
    <button class="btn" onclick="window.print()">Print</button>
    <button class="btn" onclick="window.location.href='{{ route('admin.tables.index') }}'">Kembali</button>
  </div>
</body>
</html>
