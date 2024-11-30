<?php

declare(strict_types=1);

namespace Tests;

use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = TestSeeder::class;

    protected function autheticate(): void
    {
        $response = $this->json('post', route('login.store'), ['login' => 'admin', 'password' => 'admin']);

        $response->assertOk();
    }
}
