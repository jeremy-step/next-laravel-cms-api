<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Page;
use App\Models\Setting;
use App\Rules\Host;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::check('update', Setting::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'settings' => ['required', 'array'],
        ];

        switch ($this->route()->parameter('type')) {
            case 'general':
                $rules['settings.general.website_name'] = ['required', 'string', 'max:255'];
                $rules['settings.general.frontpage'] = ['required', 'uuid', Rule::exists(Page::class, 'id')];

                break;

            case 'emails':
                $rules['settings.mail.mailers.*.transport'] = ['required', 'string', 'in:log,smtp'];
                $rules['settings.mail.mailers.*.host'] = ['nullable', 'required_if:settings.mail.mailers.*.transport,smtp', 'string', 'max:255', new Host];
                $rules['settings.mail.mailers.*.port'] = ['nullable', 'required_if:settings.mail.mailers.*.transport,smtp', 'integer', 'max:65535'];
                $rules['settings.mail.mailers.*.encryption'] = ['nullable', 'string', 'in:ssl,tls'];
                $rules['settings.mail.mailers.*.username'] = ['nullable', 'required_with:settings.mail.mailers.*.encryption', 'string', 'max:255'];
                $rules['settings.mail.mailers.*.password'] = ['nullable', 'string', 'max:255'];
                $rules['settings.mail.mailers.*.from.email'] = ['required', 'string', 'email', 'max:255'];
                $rules['settings.mail.mailers.*.from.name'] = ['nullable', 'string', 'max:255'];

                break;
        }

        return $rules;
    }

    /**
     * {@inheritDoc}
     */
    protected function passedValidation(): void
    {
        $settings = $this->input('settings');

        switch ($this->route()->parameter('type')) {
            case 'general':
                break;

            case 'emails':
                foreach ($this->input('settings.mail.mailers') as $mailer => $data) {
                    $settings['mail']['mailers'][$mailer]['password'] = $data['password'] ?? config("mail.mailers.$mailer.password");
                }

                break;
        }

        $this->merge([
            'settings' => Arr::dot($settings),
        ]);
    }
}
