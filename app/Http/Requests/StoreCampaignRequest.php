<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Campaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $endDateRules = ['required', 'date', 'after_or_equal:today'];
        if ($this->filled('start_date')) {
            $endDateRules[] = 'after_or_equal:start_date';
        }

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'story' => ['required', 'string'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'target_amount' => ['required', 'numeric', 'min:100000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => $endDateRules,
            'organizer_name' => ['required', 'string', 'max:255'],
            'organizer_type' => ['nullable', 'string', 'max:100'],
            'organizer_description' => ['nullable', 'string', 'max:1500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_verified_organizer' => ['nullable', 'boolean'],
            'status' => [
                'nullable',
                'string',
                Rule::in([Campaign::STATUS_ACTIVE, Campaign::STATUS_COMPLETED, Campaign::STATUS_INACTIVE]),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => filter_var($this->input('is_featured', false), FILTER_VALIDATE_BOOLEAN),
            'is_verified_organizer' => filter_var($this->input('is_verified_organizer', true), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
