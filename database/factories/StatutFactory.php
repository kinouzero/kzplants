<?php

namespace Database\Factories;

use App\Models\Statut;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatutFactory extends Factory
{
    protected $model = Statut::class;

    public function definition(): array
    {
        return [
            'name' => 'new',
            'color' => '#000000',
        ];
    }
}
