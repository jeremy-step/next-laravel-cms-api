<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | outcome such as failure due to an invalid password / reset token.
    |
    */

    'reset' => 'Your password has been reset.',
    'sent' => 'We have emailed your password reset link.',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This password reset token is invalid.',
    'user' => "We can't find a user with that email address.",

    'validation' => [
        'current_password' => 'The provided password does not match your current password.',
        'password' => [
            'letters' => ':attribute must contain at least one letter.',
            'mixed' => ':attribute must contain at least one uppercase and one lowercase letter.',
            'numbers' => ':attribute must contain at least one number.',
            'symbols' => ':attribute must contain at least one symbol.',
            'uncompromised' => ':attribute has appeared in a data leak. Please choose a different :attribute.',
        ],
        'required' => ':attribute is required.',
        'string' => ':attribute must be a string.',
        'confirmed' => ':attribute confirmation does not match.',
    ],

];
