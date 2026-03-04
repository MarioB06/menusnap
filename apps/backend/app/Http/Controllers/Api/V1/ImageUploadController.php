<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\UploadImageRequest;
use App\Http\Resources\DishResource;
use App\Http\Resources\RestaurantResource;
use App\Models\Dish;
use App\Models\Restaurant;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;

class ImageUploadController extends Controller
{
    public function __construct(
        private ImageService $imageService,
    ) {}

    public function uploadDishImage(UploadImageRequest $request, Dish $dish): JsonResponse
    {
        $this->authorize('update', $dish);

        // Delete old image
        $this->imageService->delete($dish->image_path);

        $path = $this->imageService->uploadDishImage($request->file('image'), $dish->id);
        $dish->update(['image_path' => $path]);

        return response()->json([
            'data' => new DishResource($dish),
        ]);
    }

    public function deleteDishImage(Dish $dish): JsonResponse
    {
        $this->authorize('update', $dish);

        $this->imageService->delete($dish->image_path);
        $dish->update(['image_path' => null]);

        return response()->json([
            'data' => new DishResource($dish),
        ]);
    }

    public function uploadRestaurantLogo(UploadImageRequest $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('update', $restaurant);

        // Delete old logo
        $this->imageService->delete($restaurant->logo_path);

        $path = $this->imageService->uploadRestaurantLogo($request->file('image'), $restaurant->id);
        $restaurant->update(['logo_path' => $path]);

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ]);
    }

    public function deleteRestaurantLogo(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('update', $restaurant);

        $this->imageService->delete($restaurant->logo_path);
        $restaurant->update(['logo_path' => null]);

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ]);
    }
}
