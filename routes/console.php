<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('ziggy:generate:api', function (): void {
    $url = config('ziggy.url.api');

    Artisan::call(
        "ziggy:generate ../next-laravel-cms-front/src/lib/router/router-api.js --group api --url '{$url}'"
    );

    // Artisan::call(
    //     "ziggy:generate ../next-laravel-cms-front/src/lib/router/router-api-route-list.ts --types-only --group api --url '{$url}'"
    // );

    $this->info('API Files generated!');
});

Artisan::command('ziggy:generate:front', function (): void {
    $url = config('ziggy.url.front');

    Artisan::call(
        "ziggy:generate ../next-laravel-cms-front/src/lib/router/router-front.js --group front --url '{$url}'"
    );

    // Artisan::call(
    //     "ziggy:generate ../next-laravel-cms-front/src/lib/router/router-front-route-list.ts --types-only --group front --url '{$url}'"
    // );

    $this->info('Front Files generated!');
});

Artisan::command('ziggy:generate:all', function (): void {
    Artisan::call('ziggy:generate:api');

    $this->info('API Files generated!');

    Artisan::call('ziggy:generate:front');

    $this->info('Front Files generated!');
});
