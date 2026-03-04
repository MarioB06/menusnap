<?php

namespace App\Services;

use App\Models\Restaurant;

class PlanLimitService
{
    public function canCreateMenu(Restaurant $restaurant): bool
    {
        $plan = $this->getPlan($restaurant);
        if (! $plan) {
            return false;
        }

        return $restaurant->menus()->count() < $plan->max_menus;
    }

    public function canCreateDish(Restaurant $restaurant): bool
    {
        $plan = $this->getPlan($restaurant);
        if (! $plan) {
            return false;
        }

        $dishCount = 0;
        foreach ($restaurant->menus as $menu) {
            foreach ($menu->categories as $category) {
                $dishCount += $category->dishes()->count();
            }
        }

        return $dishCount < $plan->max_dishes;
    }

    public function canCreateTable(Restaurant $restaurant): bool
    {
        $plan = $this->getPlan($restaurant);
        if (! $plan) {
            return false;
        }

        return $restaurant->tables()->count() < $plan->max_tables;
    }

    private function getPlan(Restaurant $restaurant)
    {
        $subscription = $restaurant->subscription;
        if (! $subscription || $subscription->status !== 'active') {
            return null;
        }

        return $subscription->plan;
    }
}
