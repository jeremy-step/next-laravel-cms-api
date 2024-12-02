<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'mail.mailers.default.transport' => 'array',
            'mail.mailers.default.from.email' => 'tests@localhost',
        ]);
    }

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = TestSeeder::class;

    protected function authenticate(string $login = 'admin', string $password = 'admin'): ?User
    {
        if (auth()->guard()->hasUser()) {
            $this->json('post', route('logout'));
        }

        $this->assertGuest();

        $response = $this->json('post', route('login.store'), ['login' => $login, 'password' => $password]);

        $response->assertOk();

        $this->assertAuthenticated();

        $user = $this->json('get', route('api.users.authenticated'));

        $user->assertOk();

        $user = $user->json()['data'] ?? [];
        $user = $user['id'] ?? null;

        if ($user) {
            $user = User::find($user);
        }

        return $user;
    }

    protected function getAdmin(): User
    {
        return User::whereUsername('admin')->first();
    }

    protected function createUser(): User
    {
        return User::create([
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->email(),
            'password' => 'password',
        ]);
    }
}
