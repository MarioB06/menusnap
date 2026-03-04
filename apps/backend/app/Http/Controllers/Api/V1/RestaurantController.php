<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\StoreRestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Models\Plan;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $restaurants = $request->user()->restaurants()->latest()->get();

        return response()->json([
            'data' => RestaurantResource::collection($restaurants),
        ]);
    }

    public function store(StoreRestaurantRequest $request): JsonResponse
    {
        $restaurant = $request->user()->restaurants()->create($request->validated());

        // Auto-assign Free plan subscription
        $freePlan = Plan::where('slug', 'free')->first();
        if ($freePlan) {
            $restaurant->subscription()->create([
                'plan_id' => $freePlan->id,
                'status' => 'active',
                'starts_at' => now(),
            ]);
        }

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ], 201);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('view', $restaurant);

        $restaurant->load('menus.categories.dishes');

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ]);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('update', $restaurant);

        $restaurant->update($request->validated());

        return response()->json([
            'data' => new RestaurantResource($restaurant),
        ]);
    }

    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('delete', $restaurant);

        $restaurant->delete();

        return response()->json(null, 204);
    }
}
