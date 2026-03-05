<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurants()
            ->withCount(['menus', 'tables'])
            ->first();

        if ($restaurant) {
            return redirect()->route('web.restaurants.show', $restaurant);
        }

        return view('dashboard');
    }
}
