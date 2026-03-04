<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 5, 45),
            'allergens' => fake()->randomElements(['gluten', 'dairy', 'nuts', 'eggs', 'soy', 'fish'], rand(0, 3)),
            'dietary_tags' => fake()->randomElements(['vegan', 'vegetarian', 'halal', 'glutenfrei'], rand(0, 2)),
            'is_available' => true,
            'sort_order' => 0,
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn () => ['is_available' => false]);
    }
}
