<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserDataTest extends TestCase
{
    public function test_must_be_authenticated_to_fetch_users(): void
    {
        $this->assertGuest();

        $response = $this->json('get', route('api.users.index'));

        $response->assertUnauthorized();

        $response = $this->json('get', route('api.users.authenticated'));

        $response->assertUnauthorized();

        $response = $this->json('get', route('api.users.is-authenticated'));

        $response->assertUnauthorized();

        $response = $this->json('get', route('api.users.get', ['user' => User::whereUsername('user')->first()->id]));

        $response->assertUnauthorized();
    }

    public function test_is_authenticated_returns_null(): void
    {
        $this->authenticate();

        $response = $this->json('get', route('api.users.is-authenticated'));

        $response->assertJson(fn (AssertableJson $data) => $data->where('data', null), true);
    }

    public function test_user_data_structure(): void
    {
        $this->authenticate();

        $userTypes = [
            'id' => 'string',
            'username' => 'string',
            'email' => 'string',
            'owner' => 'boolean',
            'name_display' => 'string',
            'name_display_plain' => 'string',
            'name_first' => 'string|null',
            'name_second' => 'string|null',
            'name_last' => 'string|null',
            'phone' => 'string|null',
            'phone_prefix' => 'string|null',
            'phone_number' => 'string|null',
            'phone_number_plain' => 'string|null',
            'meta.locale' => 'string',
            'meta.timestamps.email_verified_at' => 'string|null',
            'meta.timestamps.created_at' => 'string',
            'meta.timestamps.updated_at' => 'string',
        ];

        $missingProps = [
            'password',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'remember_token',
        ];

        $response = $this->json('get', route('api.users.authenticated'));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data',
            fn (AssertableJson $data) => $data->whereAllType($userTypes)
                ->missingAll($missingProps)
        ), true);

        $response = $this->json('get', route('api.users.get', ['user' => User::whereUsername('user')->first()->id]));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data',
            fn (AssertableJson $data) => $data->whereAllType($userTypes)
                ->missingAll($missingProps)
        ), true);

        $response = $this->json('get', route('api.users.index'));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data',
            fn (AssertableJson $data) => $data->each(fn (AssertableJson $user) => $user->whereAllType($userTypes)
                ->missingAll($missingProps)
            )
        )->has('links',
            fn (AssertableJson $links) => $links->whereAllType([
                'first' => 'string|null',
                'last' => 'string|null',
                'prev' => 'string|null',
                'next' => 'string|null',
            ])
        )->has('meta',
            fn (AssertableJson $meta) => $meta->whereAllType([
                'current_page' => 'integer',
                'from' => 'integer|null',
                'to' => 'integer|null',
                'last_page' => 'integer',
                'per_page' => 'integer',
                'total' => 'integer|null',
                'path' => 'string',
            ])->has('links',
                fn (AssertableJson $links) => $links->each(fn (AssertableJson $link) => $link->whereAllType([
                    'url' => 'string|null',
                    'label' => 'string',
                    'active' => 'boolean',
                ]))
            )
        ), true);
    }
}
