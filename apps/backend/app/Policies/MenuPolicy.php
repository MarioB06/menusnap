<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\User;

class MenuPolicy
{
    public function viewAny(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    public function view(User $user, Menu $menu): bool
    {
        return $user->id === $menu->restaurant->user_id;
    }

    public function create(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->id === $menu->restaurant->user_id;
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->id === $menu->restaurant->user_id;
    }
}
