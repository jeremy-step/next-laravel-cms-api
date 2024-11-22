<?php

declare(strict_types=1);

use App\Console\ZiggyRoutes;

return [
    'groups' => [
        'api' => ['!front.*', '!verification.notice'],
        'front' => ['front.*', 'verification.notice'],
    ],

    'url' => [
        'api' => env('API_URL', 'http://localhost:8000'),
        'front' => env('FRONT_URL', 'http://localhost:3000'),
    ],

    'domain' => [
        'api' => env('SERVER_HOST', 'localhost'),
        'front' => env('SERVER_HOST', 'localhost'),
    ],

    'port' => [
        'api' => env('SERVER_PORT', '8000'),
        'front' => env('FRONT_PORT', '3000'),
    ],

    'output' => [
        'file' => ZiggyRoutes::class,
    ],
];
