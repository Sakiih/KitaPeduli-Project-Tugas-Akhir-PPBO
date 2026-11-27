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

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori kampanye wajib dipilih.',
            'category_id.integer' => 'Kategori kampanye tidak valid.',
            'category_id.exists' => 'Kategori kampanye tidak ditemukan.',
            'title.required' => 'Judul kampanye wajib diisi.',
            'title.max' => 'Judul kampanye maksimal 255 karakter.',
            'short_description.max' => 'Ringkasan singkat maksimal 500 karakter.',
            'story.required' => 'Cerita lengkap kampanye wajib diisi.',
            'banner_image.image' => 'Banner kampanye harus berupa file gambar.',
            'banner_image.mimes' => 'Format banner kampanye harus jpg, jpeg, png, atau webp.',
            'banner_image.max' => 'Ukuran banner kampanye maksimal 4 MB.',
            'target_amount.required' => 'Target dana kampanye wajib diisi.',
            'target_amount.numeric' => 'Target dana kampanye harus berupa angka.',
            'target_amount.min' => 'Target dana kampanye minimal Rp 100.000.',
            'start_date.date' => 'Format tanggal mulai kampanye tidak valid.',
            'end_date.required' => 'Tanggal berakhir kampanye wajib diisi.',
            'end_date.date' => 'Format tanggal berakhir kampanye tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir kampanye harus sama dengan atau setelah hari ini maupun tanggal mulai.',
            'organizer_name.required' => 'Nama pengelola kampanye wajib diisi.',
            'organizer_name.max' => 'Nama pengelola maksimal 255 karakter.',
            'organizer_type.max' => 'Jenis lembaga pengelola maksimal 100 karakter.',
            'organizer_description.max' => 'Deskripsi pengelola maksimal 1500 karakter.',
            'status.in' => 'Status kampanye harus bernilai active, completed, atau inactive.',
        ];
    }
}
