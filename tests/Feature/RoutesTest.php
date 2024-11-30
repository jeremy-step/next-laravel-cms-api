<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    public function test_any_non_specific_route_return_bad_request_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(400);

        $response = $this->get('/'.Str::random());

        $response->assertStatus(400);
    }
}
