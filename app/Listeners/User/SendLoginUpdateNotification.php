<?php

declare(strict_types=1);

namespace App\Listeners\User;

use App\Events\User\EmailUpdated;
use App\Events\User\UsernameUpdated;

use function Illuminate\Log\log;

class SendLoginUpdateNotification
{
    /**
     * Handle the event.
     */
    public function handle(EmailUpdated|UsernameUpdated $event): void
    {
        log('Send '.$event::class.' notification');
    }
}
