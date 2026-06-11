<?php

namespace Database\Factories;

use App\Models\Beca;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Universidad;
use App\Models\Carrera;
use App\Models\Condicion;
use App\Models\Ayuda;
use App\Models\Imagen;

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
            'carrera_id' => Carrera::inRandomOrder()->value('id'),
            'condicion_id' => Condicion::inRandomOrder()->value('id'),
            'ayuda_id' => Ayuda::inRandomOrder()->value('id'),
            'duracion' => fake()->dateTime(),
            'imagen_id' => Imagen::inRandomOrder()->value('id'),
        ];
    }
}
