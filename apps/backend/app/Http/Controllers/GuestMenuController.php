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

    public function showByMenu(string $restaurantSlug, string $menuUuid): View
    {
        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->where('is_active', true)
            ->first();

        if (! $restaurant) {
            return view('guest.not-found');
        }

        $menu = $restaurant->menus()
            ->where('uuid', $menuUuid)
            ->where('is_active', true)
            ->with([
                'categories' => fn ($q) => $q->orderBy('sort_order'),
                'categories.dishes' => fn ($q) => $q->where('is_available', true)->orderBy('sort_order'),
            ])
            ->first();

        if (! $menu) {
            return view('guest.not-found');
        }

        // Wrap the single menu so the view can reuse the same structure
        $restaurant->setRelation('menus', collect([$menu]));

        return view('guest.menu', ['restaurant' => $restaurant, 'table' => null]);
    }
}
