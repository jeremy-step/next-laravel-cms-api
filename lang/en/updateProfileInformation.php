<?php

declare(strict_types=1);

return [
    'validation' => [
        'required' => ':attribute is required.',
        'required_if' => ':attribute is required.',
        'required_with' => ':attribute is required.',
        'min' => ':attribute must be at least :min characters.',
        'max' => ':attribute must not be greater than :max characters.',
        'max_digits' => ':attribute must not have more than :max digits.',
        'email' => ':attribute must be a valid email address.',
        'not_regex' => ':attribute format is invalid.',
        'numeric' => ':attribute must be a number.',
        'string' => ':attribute must be a string.',
        'unique' => ':attribute has already been taken.',
    ],
];
