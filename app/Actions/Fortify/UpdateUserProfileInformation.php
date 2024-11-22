<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Events\User\EmailUpdated;
use App\Events\User\UsernameUpdated;
use App\Models\User;
use App\Rules\Username;
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
        $messages = [
            'required' => __('updateProfileInformation.validation.required'),
            'required_if' => __('updateProfileInformation.validation.required_if'),
            'required_with' => __('updateProfileInformation.validation.required_with'),
            'min' => __('updateProfileInformation.validation.min'),
            'max' => __('updateProfileInformation.validation.max'),
            'max_digits' => __('updateProfileInformation.validation.max_digits'),
            'email' => __('updateProfileInformation.validation.email'),
            'not_regex' => __('updateProfileInformation.validation.not_regex'),
            'numeric' => __('updateProfileInformation.validation.numeric'),
            'string' => __('updateProfileInformation.validation.string'),
            'unique' => __('updateProfileInformation.validation.unique'),
        ];

        Validator::make($input, [
            'update_user' => ['required', 'string', 'in:profile,username,email'],
            'username' => ['required_if:update_user,username', 'string', 'min:4', 'max:32', new Username, Rule::unique(User::class)->ignore($user->id)],
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
            'phone' => ['nullable', 'required_with:phone_prefix', 'string', 'max:32', 'not_regex:/[^\d\s.,\-]+/'],
            'phone_prefix' => ['nullable', 'required_with:phone', 'numeric', 'max_digits:8'],
        ], $messages)->validateWithBag('updateProfileInformation');

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

        if (isset($data['username']) && $data['username'] !== $user->username) {
            $oldUsername = $user->username;

            $user->forceFill($data)->save();

            event(new UsernameUpdated($user, $oldUsername));
        } elseif (
            isset($data['email']) && $data['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $oldEmail = $user->email;

            $this->updateVerifiedUser($user, $data);

            event(new EmailUpdated($user, $oldEmail));
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
