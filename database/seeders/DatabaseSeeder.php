<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
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
            'owner' => true,
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $frontPage = $user?->pages()->create([
            'permalink' => 'welcome',
            'title' => 'Voluptates Numquam Autem Natus Dignissimos Aliquid Id Dolor Placeat Nam',
            'text' => <<<'MD'
            ## Est modi nisi cumque commodi

            Lorem ipsum, dolor sit amet *consectetur adipisicing* elit. Vero **cupiditate at ullam** assumenda, ratione molestiae dolores laboriosam architecto iusto ea officiis nemo sint facere corporis. **Quisquam quidem soluta** aperiam vero in, alias expedita doloremque modi molestias hic magni consectetur ratione *reprehenderit* quos minus delectus, quaerat praesentium suscipit? Ab, cum autem!

            - Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nihil, quidem.
            - Cumque beatae mollitia aliquam eius doloremque architecto.
            - Dolore delectus illo? Dignissimos repellat vitae eius totam.
                - Perferendis officia, est modi nisi cumque commodi.
            - Vel beatae voluptas hic eligendi suscipit in.

            ---

            ![Imagen](https://mdg.imgix.net/assets/images/san-juan-mountains.jpg)

            > Source: [Markdown](https://www.markdownguide.org/basic-syntax/#images-1)

            ---

            [GitHub Repo API](https://github.com/cima-alfa/next-laravel-cms-api)

            [GitHub Repo Front](https://github.com/cima-alfa/next-laravel-cms-front)
            MD,
        ]);

        $frontPage?->metadata()->create(['title' => 'Voluptates Numquam Autem Natus Dignissimos Aliquid Id Dolor Placeat Nam', 'description' => 'Meta description...', 'robots' => 'index,follow']);

        $page = $user?->pages()->create([
            'permalink' => 'about',
            'title' => 'About',
            'text' => 'About page...',
        ]);

        $page?->metadata()->create(['title' => 'About', 'description' => 'Meta description...', 'robots' => 'index,follow']);

        $page = $user?->pages()->create([
            'permalink' => 'contact',
            'title' => 'Contact',
            'text' => 'Contact page...',
        ]);

        $page?->metadata()->create(['title' => 'Contact', 'description' => 'Meta description...', 'robots' => 'noindex,nofollow']);

        Setting::create([
            'key' => 'general.website_name',
            'value' => config('app.name'),
        ]);

        Setting::create([
            'key' => 'general.frontpage',
            'value' => $frontPage->id,
        ]);

        Setting::create([
            'key' => 'mail.mailers.default.transport',
            'value' => 'log',
        ]);

        Setting::create([
            'key' => 'mail.mailers.default.from.email',
            'value' => 'admin@example.dev',
        ]);
    }
}
