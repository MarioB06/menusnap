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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: #1f2937;
        }
        .card {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
        }
        .restaurant-name {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
            color: #111827;
        }
        .menu-name {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 20px;
            text-align: center;
        }
        .divider {
            width: 40px;
            height: 1px;
            background: #d1d5db;
            margin-bottom: 20px;
        }
        .qr-wrapper {
            margin-bottom: 20px;
        }
        .qr-wrapper img, .qr-wrapper svg {
            width: 160px;
            height: 160px;
        }
        .scan-text {
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            letter-spacing: 0.3px;
        }
        .divider-bottom {
            width: 40px;
            height: 1px;
            background: #d1d5db;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="restaurant-name">{{ $restaurant->name }}</div>
        <div class="menu-name">{{ $menu->name }}</div>
        <div class="divider"></div>
        <div class="qr-wrapper">
            @if(!empty($qrCodeBase64))
                <img src="{{ $qrCodeBase64 }}" alt="QR Code">
            @else
                {!! $qrCodeSvg !!}
            @endif
        </div>
        <div class="scan-text">Speisekarte scannen</div>
        <div class="divider-bottom"></div>
    </div>
</body>
</html>
