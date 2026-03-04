<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free',
                'price' => 0,
                'features' => ['basic_menu', 'qr_codes'],
                'max_menus' => 1,
                'max_dishes' => 20,
                'max_tables' => 5,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Pro',
                'price' => 9.99,
                'features' => ['unlimited_menus', 'custom_branding', 'analytics', 'priority_support'],
                'max_menus' => 100,
                'max_dishes' => 1000,
                'max_tables' => 200,
            ]
        );
    }
}
