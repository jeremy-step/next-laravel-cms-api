<?php

declare(strict_types=1);

use Illuminate\Support\Facades\URL;

function frontRoute(string $name, array $parameters = []): string
{
    return URL::format(
        config('ziggy.url.front', 'http://localhost:3000'),
        route($name, $parameters, false)
    );
}

function frontSignedRoute(string $frontName, string $apiName, array $parameters = [], $expiration = null): string
{
    $apiRoute = parse_url(URL::signedRoute(
        $apiName,
        $parameters,
        $expiration
    ));

    parse_str($apiRoute['query'], $query);

    return frontRoute($frontName, $parameters + $query);
}

function frontSignedTemporaryRoute(string $frontName, string $apiName, $expiration, array $parameters = []): string
{
    return frontSignedRoute($frontName, $apiName, $parameters, $expiration);
}
