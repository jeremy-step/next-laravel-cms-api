<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $phone = fake('es_ES')->optional()->phoneNumber();

        $firstName = fake()->firstName($gender);
        $secondName = fake()->optional()->firstName($gender);
        $lastName = fake()->lastName($gender);

        $displayNameOptions = [];

        if ($firstName && $secondName && $lastName) {
            $displayNameOptions[] = '<first_name> <middle_name> <surname>';

            $displayNameOptions[] = '<surname>, <first_name> <middle_name>';
        }

        if ($firstName && $lastName) {
            $displayNameOptions[] = '<first_name> <surname>';

            $displayNameOptions[] = '<surname>, <first_name>';
        }

        if ($secondName && $lastName) {
            $displayNameOptions[] = '<middle_name> <surname>';

            $displayNameOptions[] = '<surname>, <middle_name>';
        }

        if ($firstName && $secondName) {
            $displayNameOptions[] = '<first_name> <middle_name>';
        }

        $displayNameOptions[] = '<username>';

        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => null,
            'name_first' => $firstName,
            'name_second' => $secondName,
            'name_last' => $lastName,
            'name_display' => fake()->randomElement($displayNameOptions),
            'phone' => $phone ? trim(str_replace('+34', '', $phone)) : null,
            'phone_prefix' => $phone ? '34' : null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
