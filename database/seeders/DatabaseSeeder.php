<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Date;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'admin',
            'email' => 'admin@example.dev',
            'password' => 'admin',
            'name_first' => 'John',
            'name_second' => 'Joe',
            'name_last' => 'Doe',
            'name_display' => '<first_name> <surname>',
        ]);

        $user->forceFill([
            'email_verified_at' => Date::now(),
        ])->save();

        $page = $user?->pages()->create([
            'permalink' => 'welcome',
            'title' => 'Welcome',
            'text' => 'My first page...',
        ]);

        $user?->pages()->create([
            'permalink' => 'about',
            'title' => 'About',
            'text' => 'About page...',
        ]);

        $user?->pages()->create([
            'permalink' => 'contact',
            'title' => 'Contact',
            'text' => 'Contact page...',
        ]);

        Setting::create([
            'key' => 'frontpage',
            'value' => $page->id,
        ]);
    }
}
