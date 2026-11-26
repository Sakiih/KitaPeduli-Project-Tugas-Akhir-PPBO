<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Campaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'story' => ['sometimes', 'required', 'string'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'target_amount' => ['sometimes', 'required', 'numeric', 'min:100000'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['sometimes', 'required', 'date'],
            'organizer_name' => ['sometimes', 'required', 'string', 'max:255'],
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
        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => filter_var($this->input('is_featured'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        if ($this->has('is_verified_organizer')) {
            $this->merge([
                'is_verified_organizer' => filter_var($this->input('is_verified_organizer'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->hasAny(['start_date', 'end_date'])) {
                return;
            }

            $routeCampaign = $this->route('campaign') ?? $this->route('id');
            $campaign = $routeCampaign instanceof Campaign
                ? $routeCampaign
                : (is_numeric($routeCampaign) ? Campaign::find((int) $routeCampaign) : null);

            $startDate = $this->filled('start_date')
                ? (string) $this->input('start_date')
                : $campaign?->start_date?->toDateString();

            $endDate = $this->filled('end_date')
                ? (string) $this->input('end_date')
                : $campaign?->end_date?->toDateString();

            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->startOfDay();
                if ($end->lt($start)) {
                    $validator->errors()->add(
                        'end_date',
                        'Tanggal berakhir kampanye harus sama dengan atau setelah tanggal mulai.'
                    );
                }
            }
        });
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
            'organizer_name.required' => 'Nama pengelola kampanye wajib diisi.',
            'organizer_name.max' => 'Nama pengelola maksimal 255 karakter.',
            'organizer_type.max' => 'Jenis lembaga pengelola maksimal 100 karakter.',
            'organizer_description.max' => 'Deskripsi pengelola maksimal 1500 karakter.',
            'status.in' => 'Status kampanye harus bernilai active, completed, atau inactive.',
        ];
    }
}
