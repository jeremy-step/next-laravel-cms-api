<?php

declare(strict_types=1);

use App\Console\ZiggyTypes;

return [
    'groups' => [
        'api' => ['!front.*', '!verification.notice'],
        'front' => ['front.*', 'verification.notice'],
    ],

    'url' => [
        'api' => env('API_URL', 'http://localhost:8000'),
        'front' => env('FRONT_URL', 'http://localhost:3000'),
    ],

    'output' => [
        'types' => ZiggyTypes::class,
    ],
];
