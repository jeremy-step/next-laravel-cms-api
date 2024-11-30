<?php

declare(strict_types=1);

namespace App\Helpers;

final class Pages
{
    public static function formatPermalink(string $value): string
    {
        return str($value)
            ->trim()
            ->replaceMatches('/\/{2,}/', '/')
            ->replaceMatches('/^\/|\/$/', '')
            ->replaceMatches('/[^\w\s\/\-_]+/', '')
            ->replaceMatches('/[\s_]+/', '-')
            ->trim()
            ->lower()
            ->replaceMatches('/\/{2,}/', '/')
            ->replaceMatches('/^\/|\/$/', '')
            ->trim()
            ->toString();
    }
}
