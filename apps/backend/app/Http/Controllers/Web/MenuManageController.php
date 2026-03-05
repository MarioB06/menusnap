<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Table;
use App\Services\ImageService;
use App\Services\PlanLimitService;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuManageController extends Controller
{
    public function __construct(
        private PlanLimitService $planLimitService,
        private ImageService $imageService,
        private QrCodeService $qrCodeService,
    ) {}

    // ── Menus ──

    public function storeMenu(Request $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('update', $restaurant);

        if (! $this->planLimitService->canCreateMenu($restaurant)) {
            return response()->json(['error' => 'Menü-Limit erreicht. Bitte Plan upgraden.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['sort_order'] = $restaurant->menus()->count();
        $menu = $restaurant->menus()->create($validated);

        return response()->json($menu->load('categories.dishes'), 201);
    }

    public function updateMenu(Request $request, Menu $menu): JsonResponse
    {
        $this->authorize('update', $menu);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $menu->update($validated);

        return response()->json($menu);
    }

    public function deleteMenu(Menu $menu): JsonResponse
    {
        $this->authorize('delete', $menu);
        $menu->delete();

        return response()->json(null, 204);
    }

    // ── Categories ──

    public function storeCategory(Request $request, Menu $menu): JsonResponse
    {
        $this->authorize('update', $menu);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['sort_order'] = $menu->categories()->count();
        $category = $menu->categories()->create($validated);

        return response()->json($category->load('dishes'), 201);
    }

    public function updateCategory(Request $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    public function deleteCategory(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);
        $category->delete();

        return response()->json(null, 204);
    }

    // ── Dishes ──

    public function storeDish(Request $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $restaurant = $category->menu->restaurant;
        if (! $this->planLimitService->canCreateDish($restaurant)) {
            return response()->json(['error' => 'Gericht-Limit erreicht. Bitte Plan upgraden.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['string'],
            'dietary_tags' => ['nullable', 'array'],
            'dietary_tags.*' => ['string'],
            'is_available' => ['sometimes', 'boolean'],
        ]);

        $validated['sort_order'] = $category->dishes()->count();
        $validated['is_available'] = $request->input('is_available', true);
        $dish = $category->dishes()->create($validated);

        return response()->json($dish, 201);
    }

    public function updateDish(Request $request, Dish $dish): JsonResponse
    {
        $this->authorize('update', $dish);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['string'],
            'dietary_tags' => ['nullable', 'array'],
            'dietary_tags.*' => ['string'],
            'is_available' => ['sometimes', 'boolean'],
        ]);

        $dish->update($validated);

        return response()->json($dish);
    }

    public function deleteDish(Dish $dish): JsonResponse
    {
        $this->authorize('delete', $dish);

        if ($dish->image_path) {
            $this->imageService->delete($dish->image_path);
        }

        $dish->delete();

        return response()->json(null, 204);
    }

    public function uploadDishImage(Request $request, Dish $dish): JsonResponse
    {
        $this->authorize('update', $dish);

        $request->validate(['image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);

        if ($dish->image_path) {
            $this->imageService->delete($dish->image_path);
        }

        $path = $this->imageService->uploadDishImage($request->file('image'), $dish->id);
        $dish->update(['image_path' => $path]);

        return response()->json(['image_path' => $path]);
    }

    public function deleteDishImage(Dish $dish): JsonResponse
    {
        $this->authorize('update', $dish);

        if ($dish->image_path) {
            $this->imageService->delete($dish->image_path);
            $dish->update(['image_path' => null]);
        }

        return response()->json(null, 204);
    }

    // ── Menu QR ──

    public function downloadMenuQr(Menu $menu)
    {
        $this->authorize('view', $menu);

        $restaurant = $menu->restaurant;

        if (! $menu->qr_code_path || ! Storage::disk('public')->exists($menu->qr_code_path)) {
            $menuUrl = url("/menu/{$restaurant->slug}/m/{$menu->uuid}");
            $qrPath = $this->qrCodeService->generate($menuUrl, 'menu-' . $menu->uuid);
            $menu->update(['qr_code_path' => $qrPath]);
        }

        return Storage::disk('public')->download($menu->qr_code_path, "qr-menu-{$menu->name}.svg");
    }

    // ── Tables ──

    public function storeTable(Request $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('update', $restaurant);

        if (! $this->planLimitService->canCreateTable($restaurant)) {
            return response()->json(['error' => 'Tisch-Limit erreicht. Bitte Plan upgraden.'], 403);
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
        ]);

        $table = $restaurant->tables()->create($validated);

        $menuUrl = url("/menu/{$restaurant->slug}/{$table->uuid}");
        $qrPath = $this->qrCodeService->generate($menuUrl, $table->uuid);
        $table->update(['qr_code_path' => $qrPath]);

        return response()->json($table, 201);
    }

    public function updateTable(Request $request, Table $table): JsonResponse
    {
        $this->authorize('update', $table);

        $validated = $request->validate([
            'label' => ['sometimes', 'string', 'max:100'],
        ]);

        $table->update($validated);

        return response()->json($table);
    }

    public function deleteTable(Table $table): JsonResponse
    {
        $this->authorize('delete', $table);

        if ($table->qr_code_path && Storage::disk('public')->exists($table->qr_code_path)) {
            Storage::disk('public')->delete($table->qr_code_path);
        }

        $table->delete();

        return response()->json(null, 204);
    }

    public function downloadQr(Table $table)
    {
        $this->authorize('view', $table);

        if (! $table->qr_code_path || ! Storage::disk('public')->exists($table->qr_code_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($table->qr_code_path, "qr-{$table->label}.svg");
    }
}
