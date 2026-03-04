<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function uploadDishImage(UploadedFile $file, int $dishId): string
    {
        $extension = $file->getClientOriginalExtension();
        $path = "dishes/{$dishId}.{$extension}";

        Storage::disk('public')->putFileAs('dishes', $file, "{$dishId}.{$extension}");

        return $path;
    }

    public function uploadRestaurantLogo(UploadedFile $file, int $restaurantId): string
    {
        $extension = $file->getClientOriginalExtension();
        $path = "logos/{$restaurantId}/logo.{$extension}";

        Storage::disk('public')->putFileAs("logos/{$restaurantId}", $file, "logo.{$extension}");

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
