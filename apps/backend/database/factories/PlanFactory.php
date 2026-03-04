<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'features' => [],
            'max_menus' => 1,
            'max_dishes' => 20,
            'max_tables' => 5,
        ];
    }

    public function pro(): static
    {
        return $this->state(fn () => [
            'name' => 'Pro',
            'slug' => 'pro',
            'price' => 9.99,
            'features' => ['unlimited_menus', 'custom_branding', 'analytics'],
            'max_menus' => 100,
            'max_dishes' => 1000,
            'max_tables' => 200,
        ]);
    }
}
