<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Menu;
use App\Models\Plan;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoRestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if (! $user) {
            return;
        }

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Bella Vista',
            'slug' => 'bella-vista',
            'description' => 'Authentische italienische Küche im Herzen der Stadt.',
            'address' => 'Bahnhofstrasse 42, 8001 Zürich',
            'phone' => '+41 44 123 45 67',
            'website' => 'https://bellavista.example.ch',
            'is_active' => true,
        ]);

        // Free-Plan Subscription
        $freePlan = Plan::where('slug', 'free')->first();
        if ($freePlan) {
            Subscription::create([
                'restaurant_id' => $restaurant->id,
                'plan_id' => $freePlan->id,
                'status' => 'active',
                'starts_at' => now(),
            ]);
        }

        // Menü: Mittagskarte
        $menu = Menu::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Mittagskarte',
            'description' => 'Unsere tägliche Mittagskarte von 11:30 bis 14:00 Uhr.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        // Kategorie: Vorspeisen
        $vorspeisen = Category::create([
            'menu_id' => $menu->id,
            'name' => 'Vorspeisen',
            'sort_order' => 0,
        ]);

        Dish::create([
            'category_id' => $vorspeisen->id,
            'name' => 'Bruschetta al Pomodoro',
            'description' => 'Geröstetes Brot mit frischen Tomaten, Knoblauch und Basilikum.',
            'price' => 12.50,
            'allergens' => ['gluten'],
            'dietary_tags' => ['vegetarian'],
            'sort_order' => 0,
        ]);

        Dish::create([
            'category_id' => $vorspeisen->id,
            'name' => 'Caprese',
            'description' => 'Büffelmozzarella mit Tomaten und frischem Basilikum.',
            'price' => 16.00,
            'allergens' => ['dairy'],
            'dietary_tags' => ['vegetarian'],
            'sort_order' => 1,
        ]);

        // Kategorie: Hauptgerichte
        $hauptgerichte = Category::create([
            'menu_id' => $menu->id,
            'name' => 'Hauptgerichte',
            'sort_order' => 1,
        ]);

        Dish::create([
            'category_id' => $hauptgerichte->id,
            'name' => 'Spaghetti Carbonara',
            'description' => 'Klassische Carbonara mit Guanciale, Ei und Pecorino.',
            'price' => 24.50,
            'allergens' => ['gluten', 'eggs', 'dairy'],
            'sort_order' => 0,
        ]);

        Dish::create([
            'category_id' => $hauptgerichte->id,
            'name' => 'Pizza Margherita',
            'description' => 'San Marzano Tomaten, Fior di Latte, frisches Basilikum.',
            'price' => 19.00,
            'allergens' => ['gluten', 'dairy'],
            'dietary_tags' => ['vegetarian'],
            'sort_order' => 1,
        ]);

        Dish::create([
            'category_id' => $hauptgerichte->id,
            'name' => 'Risotto ai Funghi Porcini',
            'description' => 'Cremiges Steinpilzrisotto mit Parmesan.',
            'price' => 28.00,
            'allergens' => ['dairy'],
            'dietary_tags' => ['vegetarian', 'glutenfrei'],
            'sort_order' => 2,
        ]);

        // Kategorie: Desserts
        $desserts = Category::create([
            'menu_id' => $menu->id,
            'name' => 'Desserts',
            'sort_order' => 2,
        ]);

        Dish::create([
            'category_id' => $desserts->id,
            'name' => 'Tiramisu',
            'description' => 'Haugemachtes Tiramisu nach Originalrezept.',
            'price' => 14.00,
            'allergens' => ['gluten', 'eggs', 'dairy'],
            'dietary_tags' => ['vegetarian'],
            'sort_order' => 0,
        ]);

        Dish::create([
            'category_id' => $desserts->id,
            'name' => 'Panna Cotta',
            'description' => 'Vanille-Panna-Cotta mit Beerensauce.',
            'price' => 12.00,
            'allergens' => ['dairy'],
            'dietary_tags' => ['vegetarian', 'glutenfrei'],
            'sort_order' => 1,
        ]);

        // Tische
        for ($i = 1; $i <= 5; $i++) {
            Table::create([
                'restaurant_id' => $restaurant->id,
                'label' => "Tisch $i",
            ]);
        }
    }
}
