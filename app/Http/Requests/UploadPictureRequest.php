<?php

namespace App\Http\Requests;

use App\Models\Plant;
use App\Models\Strain;
use Illuminate\Foundation\Http\FormRequest;

class UploadPictureRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        $plantId = $this->input('plant_id');
        $strainId = $this->input('strain_id');

        if ($plantId) {
            $plant = Plant::find($plantId);

            return $plant ? $user->can('update', $plant) : false;
        }

        if ($strainId) {
            $strain = Strain::find($strainId);

            return $strain ? $user->can('update', $strain) : false;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'plant_id' => 'nullable|integer|exists:plants,id|required_without:strain_id',
            'strain_id' => 'nullable|integer|exists:strains,id|required_without:plant_id',
            'pictures' => 'required',
            'pictures.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ];
    }
}
