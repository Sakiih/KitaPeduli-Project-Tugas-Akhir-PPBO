<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisbursementReportRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:1000'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'document_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'disbursed_at' => ['required', 'date'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul laporan penyaluran dana wajib diisi.',
            'title.max' => 'Judul laporan maksimal 255 karakter.',
            'amount.required' => 'Nominal penyaluran dana wajib diisi.',
            'amount.numeric' => 'Nominal penyaluran dana harus berupa angka.',
            'amount.min' => 'Nominal penyaluran dana minimal Rp 1.000.',
            'recipient_name.required' => 'Nama penerima dana wajib diisi.',
            'recipient_name.max' => 'Nama penerima maksimal 255 karakter.',
            'purpose.required' => 'Keperluan penyaluran wajib diisi.',
            'purpose.max' => 'Keperluan penyaluran maksimal 255 karakter.',
            'description.required' => 'Deskripsi penyaluran dana wajib diisi.',
            'document_image.image' => 'Dokumen bukti harus berupa file gambar.',
            'document_image.mimes' => 'Format gambar bukti harus jpg, jpeg, png, atau webp.',
            'document_image.max' => 'Ukuran gambar bukti maksimal 4 MB.',
            'disbursed_at.required' => 'Tanggal penyaluran dana wajib diisi.',
            'disbursed_at.date' => 'Format tanggal penyaluran tidak valid.',
        ];
    }
}
