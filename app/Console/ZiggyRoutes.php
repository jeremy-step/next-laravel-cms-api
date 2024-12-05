<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Support\Facades\Route;
use Stringable;
use Tighten\Ziggy\Ziggy;

class ZiggyRoutes implements Stringable
{
    public function __construct(
        protected Ziggy $ziggy,
    ) {}

    public function __toString(): string
    {
        $routes = Route::getRoutes()->getRoutesByName();
        $ziggy = collect($this->ziggy->toArray());
        $ziggyRoutes = collect($ziggy['routes'] ?? []);

        $ziggy['url'] = null;
        $ziggy['port'] = null;

        $ziggy['routes'] = $ziggyRoutes->map(function ($route, $name) use ($routes): array {
            $middleware = array_filter(
                $routes[$name]->middleware(),
                fn ($middleware) => str_starts_with($middleware, 'front')
            );

            if (! empty($middleware)) {
                $route['middleware'] = array_values($middleware);
            }

            $route['pattern'] = '/'.ltrim(
                preg_replace_callback(
                    "/{(([^{}]+?)\?|([^{}]+))}/",

                    function ($matches) use (&$route): string {
                        $regex = '';

                        if (isset($route['wheres']) && isset($matches[2])) {
                            $includeRegex = $route['wheres']['include-pattern:'.($matches[3] ?? $matches[2])] ?? false;

                            if ($includeRegex) {
                                $regex = $route['wheres'][$matches[3] ?? $matches[2]] ?? null;
                                $regex = $regex ? "($regex)" : '';

                                unset($route['wheres']['include-pattern:'.($matches[3] ?? $matches[2])]);
                            }
                        }

                        return ":{$matches[1]}$regex";
                    },

                    $route['uri']
                ),
                '/'
            );

            $route['parameters'] ??= [];

            return $route;
        });

        return <<<JAVASCRIPT
        const Ziggy = {$ziggy->toJson()};
        export { Ziggy };

        JAVASCRIPT;
    }
}
