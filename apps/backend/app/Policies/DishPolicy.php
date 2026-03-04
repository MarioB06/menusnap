<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Dish;
use App\Models\User;

class DishPolicy
{
    public function viewAny(User $user, Category $category): bool
    {
        return $user->id === $category->menu->restaurant->user_id;
    }

    public function view(User $user, Dish $dish): bool
    {
        return $user->id === $dish->category->menu->restaurant->user_id;
    }

    public function create(User $user, Category $category): bool
    {
        return $user->id === $category->menu->restaurant->user_id;
    }

    public function update(User $user, Dish $dish): bool
    {
        return $user->id === $dish->category->menu->restaurant->user_id;
    }

    public function delete(User $user, Dish $dish): bool
    {
        return $user->id === $dish->category->menu->restaurant->user_id;
    }
}
