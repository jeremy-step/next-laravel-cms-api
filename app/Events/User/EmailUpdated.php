<?php

declare(strict_types=1);

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class EmailUpdated
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user, public string $oldEmail) {}
}
