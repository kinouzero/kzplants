<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlantStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'strain_id' => ['required_without:external_strain_id', 'integer', 'exists:strains,id'],
            'external_strain_id' => ['required_without:strain_id', 'string', 'max:255'],
            'external_strain_name' => ['required_with:external_strain_id', 'string', 'max:255'],
            'external_strain_image_url' => ['nullable', 'url', 'max:2048'],
            'photo_mode' => ['nullable', 'in:api,upload'],
            'plant_photo' => ['nullable', 'image', 'max:5120'],
            'start_date' => ['required', 'date'],
            'dashboards' => ['required', 'array'],
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
