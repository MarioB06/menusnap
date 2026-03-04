<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user, Menu $menu): bool
    {
        return $user->id === $menu->restaurant->user_id;
    }

    public function view(User $user, Category $category): bool
    {
        return $user->id === $category->menu->restaurant->user_id;
    }

    public function create(User $user, Menu $menu): bool
    {
        return $user->id === $menu->restaurant->user_id;
    }

    public function update(User $user, Category $category): bool
    {
        return $user->id === $category->menu->restaurant->user_id;
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->id === $category->menu->restaurant->user_id;
    }
}
