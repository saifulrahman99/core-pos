<?php

namespace App\Policies;

use App\Models\User;

class StorePolicy
{
    /**
     * Determine whether the user can view the store.
     */
    public function view(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the store.
     */
    public function update(User $user): bool
    {
        return true;
    }
}
