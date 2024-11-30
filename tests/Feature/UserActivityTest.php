<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class UserActivityTest extends TestCase
{
    public function test_user_is_not_authenticated(): void
    {
        $response = $this->get(route('api.users.sessions.index'));

        $response->assertUnauthorized();
    }

    public function test_get_activity(): void
    {
        $this->autheticate();

        $response = $this->get(route('api.users.sessions.index'));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'ip_address',
                    'user_agent',
                    'created_at',
                    'last_activity',
                    'user',
                ],
            ],
        ]);
    }
}
