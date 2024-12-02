<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\UserInvite;
use App\Models\UserInvite as ModelsUserInvite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UserActionsTest extends TestCase
{
    public function test_must_be_authenticated_for_actions(): void
    {
        $this->assertGuest();

        $user = $this->createUser();

        $response = $this->json('post', route('api.users.invite'), ['email' => fake()->unique()->email()]);

        $response->assertUnauthorized();

        // $response = $this->json('patch', route('api.users.update', ['user' => $user->id]));

        // $response->assertUnauthorized();

        $response = $this->json('delete', route('api.users.destroy', ['user' => $user->id]));

        $response->assertUnauthorized();
    }

    public function test_user_must_be_owner_to_invite_users(): void
    {
        $response = $this->actingAs($this->createUser())->json('post', route('api.users.invite'), ['email' => fake()->unique()->email()]);

        $response->assertForbidden();

        $response = $this->actingAs($this->getAdmin())->json('post', route('api.users.invite'), ['email' => fake()->unique()->email()]);

        $response->assertOk();
    }

    public function test_user_invite_mail_sent(): void
    {
        Mail::fake();

        $this->actingAs($this->createUser())->json('post', route('api.users.invite'), ['email' => fake()->unique()->email()]);

        Mail::assertNotSent(UserInvite::class);

        $this->actingAs($this->getAdmin())->json('post', route('api.users.invite'), ['email' => fake()->unique()->email()]);

        Mail::assertSent(UserInvite::class);
    }

    public function test_register(): void
    {
        $email = fake()->unique()->email();
        $password = fake()->password();
        $link = URL::temporarySignedRoute('register.store', now()->addMinutes(config('fortify.invite.lifetime')));

        ModelsUserInvite::create(['email' => $email, 'invited_at' => now()]);

        $response = $this->json('post', route('register.store'), [
            'username' => fake()->unique()->userName(),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertForbidden();

        $response = $this->json('post', $link, [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->email(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertForbidden();

        $response = $this->json('post', $link, [
            'username' => fake()->unique()->userName(),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertCreated();
    }

    public function test_user_must_be_owner_to_delete_users(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createUser())->json('delete', route('api.users.destroy', ['user' => $user->id]));

        $response->assertForbidden();

        $response = $this->actingAs($this->getAdmin())->json('delete', route('api.users.destroy', ['user' => $user->id]));

        $response->assertOk();
    }

    public function test_user_cannot_self_delete(): void
    {
        $user = $this->getAdmin();

        $response = $this->actingAs($user)->json('delete', route('api.users.destroy', ['user' => $user->id]));

        $response->assertForbidden();
    }
}
