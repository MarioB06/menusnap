<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'name' => fake()->randomElement(['Vorspeisen', 'Hauptgerichte', 'Beilagen', 'Desserts', 'Getränke', 'Salate']),
            'description' => fake()->sentence(),
            'sort_order' => 0,
        ];
    }
}
