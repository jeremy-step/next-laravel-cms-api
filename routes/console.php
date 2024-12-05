<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune')->daily();

Artisan::command('ziggy:generate:api', function (): void {
    Artisan::call(
        'ziggy:generate ../next-laravel-cms-front/src/lib/router/router-api.js --group api'
    );

    $this->info('API Files generated!');
});

Artisan::command('ziggy:generate:front', function (): void {
    Artisan::call(
        'ziggy:generate ../next-laravel-cms-front/src/lib/router/router-front.js --group front'
    );

    $this->info('Front Files generated!');
});

Artisan::command('ziggy:generate:all', function (): void {
    Artisan::call('ziggy:generate:api');

    $this->info('API Files generated!');

    Artisan::call('ziggy:generate:front');

    $this->info('Front Files generated!');
});
