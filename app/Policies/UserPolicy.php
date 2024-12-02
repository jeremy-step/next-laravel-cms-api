<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can invite a new user.
     */
    public function invite(User $user): bool
    {
        return (bool) $user->owner;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $user): bool
    {
        if ($currentUser->owner && $currentUser->id !== $user->id) {
            return true;
        }

        return false;
    }
}
