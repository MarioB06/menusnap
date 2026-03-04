<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreDishRequest;
use App\Http\Requests\Dish\UpdateDishRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Resources\DishResource;
use App\Models\Category;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;

class DishController extends Controller
{
    public function index(Category $category): JsonResponse
    {
        $this->authorize('viewAny', [Dish::class, $category]);

        $dishes = $category->dishes()->orderBy('sort_order')->get();

        return response()->json([
            'data' => DishResource::collection($dishes),
        ]);
    }

    public function store(StoreDishRequest $request, Category $category): JsonResponse
    {
        $this->authorize('create', [Dish::class, $category]);

        $dish = $category->dishes()->create($request->validated());

        return response()->json([
            'data' => new DishResource($dish),
        ], 201);
    }

    public function show(Category $category, Dish $dish): JsonResponse
    {
        $this->authorize('view', $dish);

        return response()->json([
            'data' => new DishResource($dish),
        ]);
    }

    public function update(UpdateDishRequest $request, Category $category, Dish $dish): JsonResponse
    {
        $this->authorize('update', $dish);

        $dish->update($request->validated());

        return response()->json([
            'data' => new DishResource($dish),
        ]);
    }

    public function destroy(Category $category, Dish $dish): JsonResponse
    {
        $this->authorize('delete', $dish);

        $dish->delete();

        return response()->json(null, 204);
    }

    public function reorder(ReorderRequest $request, Category $category): JsonResponse
    {
        $this->authorize('viewAny', [Dish::class, $category]);

        foreach ($request->ordered_ids as $index => $id) {
            $category->dishes()->where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Order updated.']);
    }
}
