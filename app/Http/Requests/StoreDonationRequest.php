<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $campaignRule = $this->route('slug') === null
            ? ['required', 'integer', 'exists:campaigns,id']
            : ['sometimes', 'required', 'integer', 'exists:campaigns,id'];

        return [
            'campaign_id' => $campaignRule,
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'donor_phone' => ['nullable', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:1000', 'max:1000000000'],
            'payment_method' => [
                'required',
                'string',
                Rule::in([Payment::METHOD_QRIS, Payment::METHOD_BANK_TRANSFER]),
            ],
            'donor_message' => ['nullable', 'string', 'max:1000'],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user('sanctum') ?? $this->user();

        if ($user && empty($this->input('donor_name'))) {
            $this->merge([
                'donor_name' => $user->name,
            ]);
        }

        if ($user && empty($this->input('donor_email'))) {
            $this->merge([
                'donor_email' => $user->email,
            ]);
        }

        if ($this->has('is_anonymous')) {
            $this->merge([
                'is_anonymous' => filter_var($this->input('is_anonymous'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'campaign_id.required' => 'ID kampanye wajib diisi.',
            'campaign_id.exists' => 'Kampanye tidak ditemukan.',
            'donor_name.required' => 'Nama donatur wajib diisi.',
            'donor_email.email' => 'Format email donatur tidak valid.',
            'amount.required' => 'Nominal donasi wajib diisi.',
            'amount.numeric' => 'Nominal donasi harus berupa angka.',
            'amount.min' => 'Nominal donasi minimal Rp 1.000.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran hanya boleh bernilai qris atau bank_transfer.',
        ];
    }
}
