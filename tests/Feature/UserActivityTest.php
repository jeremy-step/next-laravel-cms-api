<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserActivityTest extends TestCase
{
    public function test_must_be_authenticated_to_fetch_user_activity(): void
    {
        $this->assertGuest();

        $response = $this->json('get', route('api.users.sessions.index'));

        $response->assertUnauthorized();

        $response = $this->json('get', route('api.users.sessions.get', ['user' => User::whereUsername('user')->first()->id]));

        $response->assertUnauthorized();
    }

    public function test_get_activity(): void
    {
        $this->authenticate();

        $response = $this->json('get', route('api.users.sessions.index'));

        $response->assertExactJsonStructure([
            'data' => [
                '*' => [
                    'ip_address',
                    'user_agent',
                    'created_at',
                    'last_activity',
                    'user',
                ],
            ],
        ]);
    }

    public function test_get_user_specific_activity(): void
    {
        $authenticated = $this->authenticate();

        $user = User::whereUsername('user')->first();

        $response = $this->actingAs($user)->json('get', route('api.users.sessions.get', ['user' => $authenticated->id]));

        $response->assertExactJsonStructure([
            'data' => [
                '*' => [
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
