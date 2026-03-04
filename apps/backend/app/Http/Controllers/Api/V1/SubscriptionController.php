<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function show(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('view', $restaurant);

        $subscription = $restaurant->subscription;

        if (! $subscription) {
            return response()->json(['data' => null]);
        }

        $subscription->load('plan');

        return response()->json([
            'data' => new SubscriptionResource($subscription),
        ]);
    }
}
