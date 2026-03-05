<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrowseController extends Controller
{
    public function index(): View
    {
        return view('browse.index');
    }

    public function lookupByCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'min:1'],
        ]);

        $code = trim($request->input('code'));

        $restaurant = Restaurant::where('is_active', true)
            ->where(function ($query) use ($code) {
                $query->where('slug', $code);
                if (is_numeric($code)) {
                    $query->orWhere('id', (int) $code);
                }
            })
            ->first();

        if (! $restaurant) {
            return back()->withErrors(['code' => 'Restaurant nicht gefunden. Bitte überprüfe den Code.'])->withInput();
        }

        return redirect()->route('browse.restaurant', $restaurant);
    }

    public function showRestaurant(Restaurant $restaurant): View
    {
        if (! $restaurant->is_active) {
            abort(404);
        }

        $restaurant->load([
            'menus' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'menus.categories' => fn ($q) => $q->orderBy('sort_order'),
            'menus.categories.dishes' => fn ($q) => $q->where('is_available', true)->orderBy('sort_order'),
        ]);

        return view('browse.restaurant', compact('restaurant'));
    }
}
