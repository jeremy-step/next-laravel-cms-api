<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\Settings;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            Settings::load();
        } catch (\Exception $e) {
        }
    }
}
