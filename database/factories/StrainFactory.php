<?php

namespace Database\Factories;

use App\Models\Strain;
use Illuminate\Database\Eloquent\Factories\Factory;

class StrainFactory extends Factory
{
    protected $model = Strain::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
        ];
    }
}
