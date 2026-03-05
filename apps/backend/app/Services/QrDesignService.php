<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Restaurant;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrDesignService
{
    public const TEMPLATES = [
        'minimal' => [
            'name' => 'Minimal',
            'description' => 'Elegante Karte mit Restaurantname und QR-Code',
            'free' => true,
        ],
        'classic' => [
            'name' => 'Klassisch',
            'description' => 'Dekorativer Rahmen mit elegantem Serif-Font',
            'free' => false,
        ],
        'modern' => [
            'name' => 'Modern',
            'description' => 'Modernes Design mit Farbverlauf',
            'free' => false,
        ],
        'custom' => [
            'name' => 'Eigenes Design',
            'description' => 'Komplett anpassbar mit eigenem Text und Farben',
            'free' => false,
        ],
    ];

    public function getTemplates(): array
    {
        return self::TEMPLATES;
    }

    public function canUseTemplate(Restaurant $restaurant, string $templateId): bool
    {
        $template = self::TEMPLATES[$templateId] ?? null;
        if (! $template) {
            return false;
        }

        if ($template['free']) {
            return true;
        }

        return $this->isPro($restaurant);
    }

    public function isPro(Restaurant $restaurant): bool
    {
        $subscription = $restaurant->subscription;
        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        return $subscription->plan && $subscription->plan->slug !== 'free';
    }

    public function generateQrSvg(Menu $menu): string
    {
        $restaurant = $menu->restaurant;
        $url = url("/menu/{$restaurant->slug}/m/{$menu->uuid}");

        return QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($url);
    }

    public function generateQrBase64(Menu $menu): string
    {
        $svg = $this->generateQrSvg($menu);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function renderTemplate(Menu $menu, string $templateId, array $options = []): string
    {
        $restaurant = $menu->restaurant;
        $qrCodeSvg = $this->generateQrSvg($menu);
        $qrCodeBase64 = $this->generateQrBase64($menu);

        return view("qr-templates.{$templateId}", [
            'restaurant' => $restaurant,
            'menu' => $menu,
            'qrCodeSvg' => $qrCodeSvg,
            'qrCodeBase64' => $qrCodeBase64,
            'options' => $options,
        ])->render();
    }
}
