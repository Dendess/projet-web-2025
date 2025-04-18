<?php

namespace App\Policies;

use App\Models\User;

class GroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
