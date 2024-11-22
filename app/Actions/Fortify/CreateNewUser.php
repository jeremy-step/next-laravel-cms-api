<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserInvite;
use App\Rules\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(protected Request $request) {}

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        if (! $this->request->hasValidSignature()) {
            abort(401, 'You must be invited to register.');
        }

        Validator::make($input, [
            'username' => ['required', 'string', 'min:4', 'max:32', new Username, Rule::unique(User::class)],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $email = Str::lower($input['email']);

        $invite = UserInvite::whereEmail($email)->where(UserInvite::CREATED_AT, '>=', Carbon::now()->subMinutes(config('fortify.invite.lifetime')))->first();

        if (! $invite) {
            abort(401, 'You must be invited to register.');
        }

        $user = User::create([
            'username' => Str::lower($input['username']),
            'email' => $email,
            'password' => Hash::make($input['password']),
        ]);

        UserInvite::forceDestroy($email);

        return $user;
    }
}
