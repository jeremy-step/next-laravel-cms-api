<?php

declare(strict_types=1);

namespace App\Console;

use Tighten\Ziggy\Output\Types;

class ZiggyTypes extends Types
{
    public function __toString(): string
    {
        $ziggy = $this->ziggy->toArray();
        $interfaceSuffix = $ziggy['url'] === config('ziggy.url.api', 'http://localhost:8000') ? 'Api' : 'Front';

        $output = str_replace(
            'interface RouteList {',
            "interface RouteList{$interfaceSuffix} {",
            parent::__toString()
        );

        return $output;
    }
}
