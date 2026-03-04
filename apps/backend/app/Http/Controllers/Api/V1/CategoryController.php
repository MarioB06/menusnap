<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(Menu $menu): JsonResponse
    {
        $this->authorize('viewAny', [Category::class, $menu]);

        $categories = $menu->categories()->orderBy('sort_order')->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }

    public function store(StoreCategoryRequest $request, Menu $menu): JsonResponse
    {
        $this->authorize('create', [Category::class, $menu]);

        $category = $menu->categories()->create($request->validated());

        return response()->json([
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function show(Menu $menu, Category $category): JsonResponse
    {
        $this->authorize('view', $category);

        $category->load('dishes');

        return response()->json([
            'data' => new CategoryResource($category),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Menu $menu, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return response()->json([
            'data' => new CategoryResource($category),
        ]);
    }

    public function destroy(Menu $menu, Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json(null, 204);
    }

    public function reorder(ReorderRequest $request, Menu $menu): JsonResponse
    {
        $this->authorize('viewAny', [Category::class, $menu]);

        foreach ($request->ordered_ids as $index => $id) {
            $menu->categories()->where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Order updated.']);
    }
}
