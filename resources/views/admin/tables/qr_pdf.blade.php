<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Meja {{ $table->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; }
        .card { border: 2px solid #333; padding: 40px; margin: 20px auto; width: 400px; border-radius: 20px; }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #666; margin-bottom: 20px; }
        .qr-box { margin: 20px auto; }
        .footer { margin-top: 20px; font-size: 12px; color: #888; border-top: 1px solid #eee; pt: 10px; }
        .url { font-size: 10px; color: #999; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">E-MENUGO</div>
        <div class="subtitle">Scann me to see Menu!</div>
        
        <div class="qr-box">
            <img src="{{ $qrCodeUrl }}" width="250">
        </div>
        
        <div class="title" style="font-size: 30px;">MEJA {{ $table->name }}</div>
        
        <div class="footer">
            Silakan scan untuk memesan menu langsung dari meja Anda.
            <div class="url">{{ $url }}</div>
        </div>
    </div>
</body>
</html>
