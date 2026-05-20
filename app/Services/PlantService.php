<?php

namespace App\Services;

use App\Models\Picture;
use App\Models\Plant;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class PlantService
{
    public function create(array $data): Plant
    {
        $plant = new Plant;
        $strainId = $this->resolveStrainId($data);
        $plant->fill(Arr::only($data, ['name']));
        $plant->strain_id = $strainId;
        $plant->created_by = auth()->id();

        $startDate = Arr::get($data, 'start_date');
        if ($startDate) {
            $tz = auth()->user() ? User::getUserTimezone(auth()->user()) : config('app.timezone');
            $createdAt = Carbon::parse($startDate, $tz)->startOfDay()->setTimezone(config('app.timezone'));
            $plant->created_at = $createdAt;
            $plant->updated_at = $createdAt;
        }

        $plant->statut_id = Statut::getStatut($plant)->id;
        $plant->save();

        $this->syncRelations($plant, $data, true);
        $this->attachPlantPhoto($plant, $data);
        $plant->scheduleStageDueDates();

        return $plant;
    }

    public function update(Plant $plant, array $data): Plant
    {
        $plant->fill(Arr::only($data, ['name', 'strain_id']));

        $this->syncRelations($plant, $data, false);
        $plant->scheduleStageDueDates();

        $plant->save();

        return $plant;
    }

    private function syncRelations(Plant $plant, array $data, bool $isNew): void
    {
        $dashboards = Arr::get($data, 'dashboards', []);
        $tags = Arr::get($data, 'tags', []);
        $preferences = Arr::get($data, 'preferences', null);

        $properties = Arr::get($data, 'properties', []);
        $values = Arr::get($data, 'values', []);
        $propertyValue = [];

        if ($properties && $values) {
            foreach ($values as $k => $v) {
                $propertyId = $properties[$k] ?? null;
                if ($propertyId && $v !== null && $v !== '') {
                    $propertyValue[] = [
                        'plant_id' => $plant->id,
                        'property_id' => $propertyId,
                        'value' => $v,
                    ];
                }
            }
        }

        if ($isNew) {
            $plant->dashboards()->attach($dashboards);
            if ($tags) {
                $plant->tags()->attach($tags);
            }
            if ($propertyValue) {
                $plant->properties()->attach($propertyValue);
            }
        } else {
            if ($dashboards) {
                $plant->dashboards()->sync($dashboards);
            } else {
                $plant->dashboards()->sync([]);
            }

            if ($tags) {
                $plant->tags()->sync($tags);
            } else {
                $plant->tags()->sync([]);
            }

            if ($propertyValue) {
                $plant->properties()->sync($propertyValue);
            } else {
                $plant->properties()->sync([]);
            }
        }

        if ($preferences !== null) {
            $values = [];
            if ($preferences) {
                foreach ($preferences as $preferenceId => $value) {
                    if ($value !== null && $value !== '') {
                        $values[] = [
                            'plant_id' => $plant->id,
                            'preference_id' => $preferenceId,
                            'value' => is_array($value) ? json_encode($value) : $value,
                        ];
                    }
                }
            }

            if ($values) {
                $plant->preferences()->sync($values);
            } else {
                $plant->preferences()->sync([]);
            }
        }
    }

    private function resolveStrainId(array $data): int
    {
        $strainId = Arr::get($data, 'strain_id');
        if ($strainId) {
            return (int) $strainId;
        }

        $externalId = Arr::get($data, 'external_strain_id');
        $externalName = Arr::get($data, 'external_strain_name');
        $externalImageUrl = Arr::get($data, 'external_strain_image_url');

        if (! $externalId || ! $externalName) {
            throw new \InvalidArgumentException('Missing strain data.');
        }

        $strain = Strain::where('external_source', 'perenual')
            ->where('external_id', (string) $externalId)
            ->first();

        if (! $strain) {
            $strain = Strain::create([
                'name' => $externalName,
                'external_source' => 'perenual',
                'external_id' => (string) $externalId,
            ]);
        }

        if ($externalImageUrl && ! $strain->defaultPicture()) {
            $picture = Picture::fromUrl($externalImageUrl, sprintf('%s.jpg', $externalName));
            if ($picture) {
                $strain->pictures()->attach($picture->id, ['default' => true]);
            }
        }

        return (int) $strain->id;
    }

    private function attachPlantPhoto(Plant $plant, array $data): void
    {
        $mode = Arr::get($data, 'photo_mode', 'api');
        $file = Arr::get($data, 'plant_photo');
        if ($mode !== 'upload' || ! $file) {
            return;
        }

        $picture = Picture::upload($file);
        $plant->pictures()->attach($picture->id, ['default' => true]);
    }
}
