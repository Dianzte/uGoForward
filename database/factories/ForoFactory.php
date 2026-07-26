<?php

namespace Database\Factories;

use App\Models\Foro;
use App\Models\CategoriasForo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Foro>
 */
class ForoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categorias' => CategoriasForo::inRandomOrder()->value('id'),
        ];
    }
}
