<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StrainUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'properties' => ['sometimes', 'array'],
            'properties.*' => ['nullable', 'integer', 'exists:properties,id'],
            'values' => ['sometimes', 'array'],
            'values.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
