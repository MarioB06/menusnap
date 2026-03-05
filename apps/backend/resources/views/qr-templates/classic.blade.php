<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { margin: 0; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            width: 255px;
            height: 360px;
            background: #fdfbf7;
            color: #2c1810;
        }
        .card {
            width: 100%;
            height: 100%;
            border: 2px solid #8b7355;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 16px;
            position: relative;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 6px;
            right: 6px;
            bottom: 6px;
            border: 1px solid #c4a882;
        }
        .restaurant-name {
            font-size: 17px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
            color: #2c1810;
            letter-spacing: 1px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }
        .menu-name {
            font-size: 11px;
            font-style: italic;
            color: #8b7355;
            margin-bottom: 14px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .ornament {
            font-size: 14px;
            color: #c4a882;
            text-align: center;
            margin-bottom: 14px;
            letter-spacing: 4px;
            position: relative;
            z-index: 1;
        }
        .qr-wrapper {
            background: #ffffff;
            padding: 10px;
            border: 1px solid #d4c5a9;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }
        .qr-wrapper img, .qr-wrapper svg {
            width: 140px;
            height: 140px;
            display: block;
        }
        .scan-text {
            font-size: 11px;
            font-style: italic;
            color: #8b7355;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .ornament-bottom {
            font-size: 14px;
            color: #c4a882;
            text-align: center;
            margin-top: 10px;
            letter-spacing: 4px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="restaurant-name">{{ $restaurant->name }}</div>
        <div class="menu-name">{{ $menu->name }}</div>
        <div class="ornament">&#8226; &#8212; &#8226;</div>
        <div class="qr-wrapper">
            @if(!empty($qrCodeBase64))
                <img src="{{ $qrCodeBase64 }}" alt="QR Code">
            @else
                {!! $qrCodeSvg !!}
            @endif
        </div>
        <div class="scan-text">Speisekarte scannen</div>
        <div class="ornament-bottom">&#8226; &#8212; &#8226;</div>
    </div>
</body>
</html>
