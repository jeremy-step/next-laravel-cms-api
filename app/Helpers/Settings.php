<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Setting;

final class Settings
{
    public static function get(string $key, $defaultValue = null): mixed
    {
        return Setting::find($key)?->value ?? $defaultValue;
    }
}
