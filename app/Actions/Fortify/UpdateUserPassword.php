<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        $messages = [
            'required' => __('passwords.validation.required'),
            'string' => __('passwords.validation.string'),
            'confirmed' => __('passwords.validation.confirmed'),
            'current_password.current_password' => __('passwords.validation.current_password'),
            'password' => [
                'letters' => __('passwords.validation.password.letters'),
                'mixed' => __('passwords.validation.password.mixed'),
                'numbers' => __('passwords.validation.password.numbers'),
                'symbols' => __('passwords.validation.password.symbols'),
                'uncompromised' => __('passwords.validation.password.uncompromised'),
            ],
        ];

        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], $messages)->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
