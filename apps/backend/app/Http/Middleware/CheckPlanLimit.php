<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Services\PlanLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimit
{
    public function __construct(
        private PlanLimitService $planLimitService,
    ) {}

    public function handle(Request $request, Closure $next, string $type): Response
    {
        $restaurant = $this->resolveRestaurant($request, $type);

        if (! $restaurant) {
            return $next($request);
        }

        $allowed = match ($type) {
            'menu' => $this->planLimitService->canCreateMenu($restaurant),
            'dish' => $this->planLimitService->canCreateDish($restaurant),
            'table' => $this->planLimitService->canCreateTable($restaurant),
            default => true,
        };

        if (! $allowed) {
            return response()->json([
                'message' => 'Plan limit reached. Upgrade to Pro for more.',
            ], 403);
        }

        return $next($request);
    }

    private function resolveRestaurant(Request $request, string $type): ?Restaurant
    {
        return match ($type) {
            'menu', 'table' => $request->route('restaurant'),
            'dish' => $this->getRestaurantFromCategory($request),
            default => null,
        };
    }

    private function getRestaurantFromCategory(Request $request): ?Restaurant
    {
        $category = $request->route('category');
        if ($category instanceof Category) {
            return $category->menu->restaurant;
        }

        return null;
    }
}
