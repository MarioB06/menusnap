<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\QrDesignService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QrDesignerController extends Controller
{
    public function __construct(
        private QrDesignService $designService,
    ) {}

    public function show(Menu $menu)
    {
        $this->authorize('view', $menu);

        $restaurant = $menu->restaurant;
        $templates = $this->designService->getTemplates();
        $isPro = $this->designService->isPro($restaurant);
        $qrCodeSvg = $this->designService->generateQrSvg($menu);

        return view('qr-designer', compact(
            'menu',
            'restaurant',
            'templates',
            'isPro',
            'qrCodeSvg',
        ));
    }

    public function download(Request $request, Menu $menu)
    {
        $this->authorize('view', $menu);

        $validated = $request->validate([
            'template' => ['required', 'string', 'in:' . implode(',', array_keys(QrDesignService::TEMPLATES))],
            'custom_text' => ['nullable', 'string', 'max:100'],
            'custom_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $restaurant = $menu->restaurant;

        if (! $this->designService->canUseTemplate($restaurant, $validated['template'])) {
            return back()->with('error', 'Dieses Design ist nur mit dem Pro-Plan verfügbar.');
        }

        $options = [
            'custom_text' => $validated['custom_text'] ?? null,
            'custom_color' => $validated['custom_color'] ?? null,
        ];

        $html = $this->designService->renderTemplate($menu, $validated['template'], $options);

        $pdf = Pdf::loadHTML($html)
            ->setPaper([0, 0, 255.12, 360.0], 'portrait'); // ~9cm x ~12.7cm

        $filename = "tischkarte-{$menu->name}.pdf";

        return $pdf->download($filename);
    }

    public function preview(Request $request, Menu $menu)
    {
        $this->authorize('view', $menu);

        $templateId = $request->query('template', 'minimal');

        if (! array_key_exists($templateId, QrDesignService::TEMPLATES)) {
            abort(404);
        }

        $options = [
            'custom_text' => $request->query('custom_text'),
            'custom_color' => $request->query('custom_color'),
        ];

        $html = $this->designService->renderTemplate($menu, $templateId, $options);

        return response($html);
    }
}
