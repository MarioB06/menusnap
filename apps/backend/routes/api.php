<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DishController;
use App\Http\Controllers\Api\V1\ImageUploadController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\RestaurantController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\TableController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('plans', [PlanController::class, 'index']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/profile', [AuthController::class, 'profile']);

        // Restaurants
        Route::apiResource('restaurants', RestaurantController::class);

        // Subscription
        Route::get('restaurants/{restaurant}/subscription', [SubscriptionController::class, 'show']);

        // Menus (nested under restaurant) with plan limit on store
        Route::post('restaurants/{restaurant}/menus', [MenuController::class, 'store'])
            ->middleware('plan.limit:menu');
        Route::apiResource('restaurants.menus', MenuController::class)
            ->scoped()
            ->except(['store']);
        Route::post('restaurants/{restaurant}/menus/reorder', [MenuController::class, 'reorder']);

        // Categories (nested under menu)
        Route::apiResource('menus.categories', CategoryController::class)->scoped();
        Route::post('menus/{menu}/categories/reorder', [CategoryController::class, 'reorder']);

        // Dishes (nested under category) with plan limit on store
        Route::post('categories/{category}/dishes', [DishController::class, 'store'])
            ->middleware('plan.limit:dish');
        Route::apiResource('categories.dishes', DishController::class)
            ->scoped()
            ->except(['store']);
        Route::post('categories/{category}/dishes/reorder', [DishController::class, 'reorder']);

        // Tables (nested under restaurant) with plan limit on store
        Route::post('restaurants/{restaurant}/tables', [TableController::class, 'store'])
            ->middleware('plan.limit:table');
        Route::apiResource('restaurants.tables', TableController::class)
            ->scoped()
            ->except(['store']);
        Route::get('restaurants/{restaurant}/tables/{table}/qr', [TableController::class, 'downloadQr']);

        // Image uploads
        Route::post('dishes/{dish}/image', [ImageUploadController::class, 'uploadDishImage']);
        Route::delete('dishes/{dish}/image', [ImageUploadController::class, 'deleteDishImage']);
        Route::post('restaurants/{restaurant}/logo', [ImageUploadController::class, 'uploadRestaurantLogo']);
        Route::delete('restaurants/{restaurant}/logo', [ImageUploadController::class, 'deleteRestaurantLogo']);
    });
});
