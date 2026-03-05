<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function create(Request $request): View|RedirectResponse
    {
        // Only allow one restaurant per user
        if ($request->user()->restaurants()->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Du kannst nur ein Restaurant erstellen.');
        }

        return view('restaurants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Only allow one restaurant per user
        if ($request->user()->restaurants()->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Du kannst nur ein Restaurant erstellen.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        $restaurant = $request->user()->restaurants()->create($validated);

        // Auto-assign free plan
        $freePlan = Plan::where('slug', 'free')->first();
        if ($freePlan) {
            $restaurant->subscription()->create([
                'plan_id' => $freePlan->id,
                'status' => 'active',
                'starts_at' => now(),
            ]);
        }

        return redirect()->route('web.restaurants.show', $restaurant)
            ->with('success', 'Restaurant erfolgreich erstellt.');
    }

    public function show(Request $request, Restaurant $restaurant): View
    {
        $this->authorize('view', $restaurant);

        $restaurant->load([
            'menus' => fn ($q) => $q->orderBy('sort_order'),
            'menus.categories' => fn ($q) => $q->orderBy('sort_order'),
            'menus.categories.dishes' => fn ($q) => $q->orderBy('sort_order'),
            'tables',
            'subscription.plan',
        ]);

        return view('restaurants.show', compact('restaurant'));
    }

    public function edit(Request $request, Restaurant $restaurant): View
    {
        $this->authorize('update', $restaurant);

        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $restaurant->update($validated);

        return redirect()->route('web.restaurants.show', $restaurant)
            ->with('success', 'Restaurant aktualisiert.');
    }

    public function destroy(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $this->authorize('delete', $restaurant);

        if ($restaurant->logo_path) {
            Storage::disk('public')->delete($restaurant->logo_path);
        }

        $restaurant->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Restaurant gelöscht.');
    }
}
