<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(string $url, string $uuid): string
    {
        $path = "qrcodes/{$uuid}.png";

        $qrCode = QrCode::format('png')
            ->size(400)
            ->margin(2)
            ->generate($url);

        Storage::disk('public')->put($path, $qrCode);

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
