<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class SettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return (bool) $user?->owner;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->owner;
    }
}
