<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;

class TablePolicy
{
    public function viewAny(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    public function view(User $user, Table $table): bool
    {
        return $user->id === $table->restaurant->user_id;
    }

    public function create(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    public function update(User $user, Table $table): bool
    {
        return $user->id === $table->restaurant->user_id;
    }

    public function delete(User $user, Table $table): bool
    {
        return $user->id === $table->restaurant->user_id;
    }
}
