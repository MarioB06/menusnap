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
        }
        .accent-bar {
            height: 6px;
            background: {{ $options['custom_color'] ?? '#4f46e5' }};
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .restaurant-name {
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
            color: {{ $options['custom_color'] ?? '#4f46e5' }};
            letter-spacing: 0.5px;
        }
        .menu-name {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 6px;
            text-align: center;
        }
        .custom-text {
            font-size: 10px;
            color: #374151;
            text-align: center;
            margin-bottom: 16px;
            font-weight: 500;
        }
        .qr-wrapper {
            padding: 10px;
            border: 2px solid {{ $options['custom_color'] ?? '#4f46e5' }};
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .qr-wrapper img, .qr-wrapper svg {
            width: 150px;
            height: 150px;
            display: block;
        }
        .scan-text {
            font-size: 11px;
            font-weight: 600;
            color: {{ $options['custom_color'] ?? '#4f46e5' }};
            text-align: center;
        }
        .accent-bar-bottom {
            height: 6px;
            background: {{ $options['custom_color'] ?? '#4f46e5' }};
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="accent-bar"></div>
        <div class="content">
            <div class="restaurant-name">{{ $restaurant->name }}</div>
            <div class="menu-name">{{ $menu->name }}</div>
            @if(!empty($options['custom_text']))
                <div class="custom-text">{{ $options['custom_text'] }}</div>
            @else
                <div style="margin-bottom:16px"></div>
            @endif
            <div class="qr-wrapper">
                @if(!empty($qrCodeBase64))
                    <img src="{{ $qrCodeBase64 }}" alt="QR Code">
                @else
                    {!! $qrCodeSvg !!}
                @endif
            </div>
            <div class="scan-text">Speisekarte scannen</div>
        </div>
        <div class="accent-bar-bottom"></div>
    </div>
</body>
</html>
