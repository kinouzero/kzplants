<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlantUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'strain_id' => ['sometimes', 'required', 'integer', 'exists:strains,id'],
            'dashboards' => ['sometimes', 'array'],
            'dashboards.*' => ['integer', 'exists:dashboards,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'properties' => ['sometimes', 'array'],
            'properties.*' => ['nullable', 'integer', 'exists:properties,id'],
            'values' => ['sometimes', 'array'],
            'values.*' => ['nullable', 'string', 'max:255'],
            'preferences' => ['sometimes', 'array'],
        ];
    }
}
