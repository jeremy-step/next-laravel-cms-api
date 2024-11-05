<?php

declare(strict_types=1);

use App\Http\Middleware\ApiJsonResponseMiddleware;
use App\Http\Middleware\FrontPlaceholderMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/status',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->api(prepend: ApiJsonResponseMiddleware::class);

        $middleware->alias([
            'front' => FrontPlaceholderMiddleware::class,
            'front:guest' => FrontPlaceholderMiddleware::class,
            'front:auth' => FrontPlaceholderMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e): bool {
            if (str_starts_with(Route::currentRouteName() ?? '', 'api.')) {
                return true;
            }

            return $request->expectsJson();
        });
    })->create();
