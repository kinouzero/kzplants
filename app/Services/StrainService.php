<?php

namespace App\Services;

use App\Models\Strain;
use Illuminate\Support\Arr;

class StrainService
{
    public function create(array $data): Strain
    {
        $strain = new Strain;
        $strain->fill(Arr::only($data, ['name']));
        $strain->save();

        $this->syncRelations($strain, $data, true);

        return $strain;
    }

    public function update(Strain $strain, array $data): Strain
    {
        $strain->fill(Arr::only($data, ['name']));

        $this->syncRelations($strain, $data, false);

        $strain->save();

        return $strain;
    }

    private function syncRelations(Strain $strain, array $data, bool $isNew): void
    {
        $tags = Arr::get($data, 'tags', []);

        $properties = Arr::get($data, 'properties', []);
        $values = Arr::get($data, 'values', []);
        $propertyValue = [];

        if ($properties && $values) {
            foreach ($values as $k => $v) {
                $propertyId = $properties[$k] ?? null;
                if ($propertyId && $v !== null && $v !== '') {
                    $propertyValue[] = [
                        'strain_id' => $strain->id,
                        'property_id' => $propertyId,
                        'value' => $v,
                    ];
                }
            }
        }

        if ($isNew) {
            if ($tags) {
                $strain->tags()->attach($tags);
            }
            if ($propertyValue) {
                $strain->properties()->attach($propertyValue);
            }
        } else {
            if ($tags) {
                $strain->tags()->sync($tags);
            } else {
                $strain->tags()->sync([]);
            }

            if ($propertyValue) {
                $strain->properties()->sync($propertyValue);
            } else {
                $strain->properties()->sync([]);
            }
        }
    }
}
