<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\View\View;

class GuestMenuController extends Controller
{
    public function show(string $restaurantSlug, string $tableUuid): View
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->with([
                'menus' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'menus.categories' => fn ($q) => $q->orderBy('sort_order'),
                'menus.categories.dishes' => fn ($q) => $q->where('is_available', true)->orderBy('sort_order'),
            ])
            ->first();

        if (! $restaurant) {
            return view('guest.not-found');
        }

        $table = $restaurant->tables()->where('uuid', $tableUuid)->first();

        if (! $table) {
            return view('guest.not-found');
        }

        return view('guest.menu', compact('restaurant', 'table'));
    }
}
