<?php

declare(strict_types=1);

namespace App\Http\Middleware;

class FrontRoutesMiddleware
{
    /**
     * Abort an incoming request.
     */
    public function handle()
    {
        abort(400);
    }
}
