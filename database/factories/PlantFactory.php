<?php

namespace Database\Factories;

use App\Models\Plant;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlantFactory extends Factory
{
    protected $model = Plant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'created_by' => User::factory(),
            'statut_id' => Statut::factory(),
            'strain_id' => Strain::factory(),
        ];
    }
}
