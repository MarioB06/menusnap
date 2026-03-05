<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { margin: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            width: 255px;
            height: 360px;
            background: #ffffff;
            color: #1f2937;
        }
        .card {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 12px;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            padding: 24px 20px 20px;
            text-align: center;
        }
        .restaurant-name {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .menu-name {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 400;
        }
        .body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #ffffff;
        }
        .qr-wrapper {
            background: #ffffff;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.12);
            margin-bottom: 16px;
        }
        .qr-wrapper img, .qr-wrapper svg {
            width: 150px;
            height: 150px;
            display: block;
        }
        .scan-text {
            font-size: 12px;
            font-weight: 600;
            color: #4f46e5;
            text-align: center;
            margin-bottom: 4px;
        }
        .scan-sub {
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }
        .footer {
            padding: 10px 20px;
            text-align: center;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
        }
        .footer-text {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="restaurant-name">{{ $restaurant->name }}</div>
            <div class="menu-name">{{ $menu->name }}</div>
        </div>
        <div class="body">
            <div class="qr-wrapper">
                @if(!empty($qrCodeBase64))
                    <img src="{{ $qrCodeBase64 }}" alt="QR Code">
                @else
                    {!! $qrCodeSvg !!}
                @endif
            </div>
            <div class="scan-text">Speisekarte scannen</div>
            <div class="scan-sub">Kamera auf den QR-Code richten</div>
        </div>
        <div class="footer">
            <div class="footer-text">Digitale Speisekarte</div>
        </div>
    </div>
</body>
</html>
