<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Validator as Rule;

class Username implements ValidationRule, ValidatorAwareRule
{
    /**
     * The validator performing the validation.
     */
    protected Validator $validator;

    /**
     * Set the current validator.
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Rule::make(
            [$attribute => $value],
            [],
            array_merge(
                [
                    'username.invalid_characters' => ':attribute must only contain letters, numbers and dots or dashes or underscores.',
                    'username.invalid_start_end' => ':attribute must start and end with a letter or number.',
                    'username.mixed_special_characters' => ':attribute must not contain mixed special characters.',
                    'username.multiple_consecutive_special_characters' => ':attribute must not contain multiple consecutive special characters.',
                ],
                $this->validator->customMessages
            ),
            $this->validator->customAttributes
        );

        $validator->after(function ($validator) use ($attribute, $value): void {
            if (! is_string($value)) {
                return;
            }

            // Only alpha numeric values with dots or dashes or underscores allowed

            if (! preg_match('/^[a-z0-9_.-]+$/', $value)) {
                $validator->addFailure($attribute, 'username.invalid_characters');
            }

            // Only alpha numeric characters allowed at the start and end of the value

            if (! preg_match('/^[a-z0-9].*[a-z0-9]$/', $value)) {
                $validator->addFailure($attribute, 'username.invalid_start_end');
            }

            // Only one of the following types of special characters is allowed: dots or dashes or underscores
            // For example "user.name.valid" is allowed but "user.name-invalid" is not

            if (preg_match('/^.*(((\.).*(_|-))|((_).*(\.|-))|((-).*(\.|_))).*$/', $value)) {
                $validator->addFailure($attribute, 'username.mixed_special_characters');
            }

            // Special characters cannot be repeated multiple times in a row
            // For example "username.valid" is allowed but "username..invalid" is not

            if (preg_match('/^.*(\.{2,}|_{2,}|-{2,}).*$/', $value)) {
                $validator->addFailure($attribute, 'username.multiple_consecutive_special_characters');
            }
        });

        if ($validator->fails()) {
            foreach ($validator->messages()->get($attribute) as $message) {
                $fail($message);
            }
        }
    }
}
