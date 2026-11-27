<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul kabar terbaru wajib diisi.',
            'title.max' => 'Judul kabar terbaru maksimal 255 karakter.',
            'content.required' => 'Isi perkembangan kabar terbaru wajib diisi.',
            'image.image' => 'Foto dokumentasi harus berupa file gambar.',
            'image.mimes' => 'Format foto dokumentasi harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran foto dokumentasi maksimal 4 MB.',
            'published_at.date' => 'Format waktu publikasi tidak valid.',
        ];
    }
}
