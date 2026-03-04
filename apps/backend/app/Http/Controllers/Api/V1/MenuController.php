<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('viewAny', [Menu::class, $restaurant]);

        $menus = $restaurant->menus()->orderBy('sort_order')->get();

        return response()->json([
            'data' => MenuResource::collection($menus),
        ]);
    }

    public function store(StoreMenuRequest $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('create', [Menu::class, $restaurant]);

        $menu = $restaurant->menus()->create($request->validated());

        return response()->json([
            'data' => new MenuResource($menu),
        ], 201);
    }

    public function show(Restaurant $restaurant, Menu $menu): JsonResponse
    {
        $this->authorize('view', $menu);

        $menu->load('categories.dishes');

        return response()->json([
            'data' => new MenuResource($menu),
        ]);
    }

    public function update(UpdateMenuRequest $request, Restaurant $restaurant, Menu $menu): JsonResponse
    {
        $this->authorize('update', $menu);

        $menu->update($request->validated());

        return response()->json([
            'data' => new MenuResource($menu),
        ]);
    }

    public function destroy(Restaurant $restaurant, Menu $menu): JsonResponse
    {
        $this->authorize('delete', $menu);

        $menu->delete();

        return response()->json(null, 204);
    }

    public function reorder(ReorderRequest $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('update', $restaurant);

        foreach ($request->ordered_ids as $index => $id) {
            $restaurant->menus()->where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Order updated.']);
    }
}
