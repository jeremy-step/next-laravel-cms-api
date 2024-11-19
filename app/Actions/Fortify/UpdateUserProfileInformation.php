<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'update_user' => ['required', 'in:profile,username,email'],
            'username' => ['required_if:update_user,username', 'string', 'min:4', 'max:32', new UsernameValidation, Rule::unique(User::class)->ignore($user->id)],
            'email' => [
                'required_if:update_user,email',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'name_first' => ['required_if:update_user,profile', 'string', 'max:255'],
            'name_second' => ['nullable', 'string', 'max:255'],
            'name_last' => ['required_if:update_user,profile', 'string', 'max:255'],
            'name_display' => ['required_if:update_user,profile', 'string', 'max:255'],
            'phone' => ['nullable', 'required_with:phone_prefix', 'string', 'max:32', 'not_regex:/[^0-9 )(-.]+/'],
            'phone_prefix' => ['nullable', 'required_with:phone', 'numeric', 'max_digits:8'],
        ])->validateWithBag('updateProfileInformation');

        $data = [];

        switch ($input['update_user']) {
            case 'profile':
                $data = [
                    'name_first' => $input['name_first'],
                    'name_second' => $input['name_second'],
                    'name_last' => $input['name_last'],
                    'name_display' => $input['name_display'],
                    'phone' => $input['phone'],
                    'phone_prefix' => $input['phone_prefix'],
                ];
                break;

            case 'username':
                $data = [
                    'username' => $input['username'],
                ];
                break;

            case 'email':
                $data = [
                    'email' => $input['email'],
                ];
                break;
        }

        if (isset($data['email']) && $data['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $data);
        } else {
            $user->forceFill($data)->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
