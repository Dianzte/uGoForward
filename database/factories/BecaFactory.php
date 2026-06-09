<?php

namespace Database\Factories;

use App\Models\Beca;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Universidad;

/**
 * @extends Factory<Beca>
 */
class BecaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' =>fake()->sentence(),
            'descripcion' => fake()->paragraph(),
            'universidad_id' => Universidad::inRandomOrder()->value('id'),
        ];
    }
}
