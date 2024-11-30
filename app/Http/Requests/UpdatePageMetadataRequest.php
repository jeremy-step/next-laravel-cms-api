<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageMetadataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['robots' => str($this->input('robots', ''))->replace(',', '.')->toString()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permalink' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'robots' => ['required', 'string', 'in:index.follow,index.nofollow,noindex.follow,noindex.nofollow'],
            'sitemap_include' => ['required', 'boolean'],
            'sitemap_prio' => ['required', 'decimal:1', 'in:0.0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1.0'],
            'sitemap_change_freq' => ['required', 'string', 'in:always,hourly,daily,weekly,monthly,yearly,never'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'permalink' => $this->validated('permalink') ?? '',
            'description' => $this->validated('description') ?? '',
            'robots' => str($this->validated('robots'))->replace('.', ',')->toString(),
        ]);
    }
}
