<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $routeCategory = $this->route('category') ?? $this->route('id');
        $ignoreId = $routeCategory instanceof Category
            ? $routeCategory->id
            : (is_numeric($routeCategory) ? (int) $routeCategory : null);

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($ignoreId)],
            'icon' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('name'))) {
            $this->merge([
                'name' => trim($this->input('name')),
            ]);
        }
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
