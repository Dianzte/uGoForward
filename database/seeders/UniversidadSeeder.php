<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Universidad;

class UniversidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universidades = [
            ['siglas' => 'uca', 'nombre_completo' => 'Universidad Centroamericana José Simeón Cañas'],
            ['siglas' => 'udb', 'nombre_completo' => 'Universidad Don Bosco'],
            ['siglas' => 'utec', 'nombre_completo' => 'Universidad Tecnológica de El Salvador'],
            ['siglas' => 'ufg', 'nombre_completo' => 'Universidad Francisco Gavidia El Salvador'],
            ['siglas' => 'uees', 'nombre_completo' => 'Universidad Evangélica de El Salvador'],
            ['siglas' => 'unicaes', 'nombre_completo' => 'Universidad Católica de El Salvador'],
        ];

        foreach ($universidades as $universidad) {
            Universidad::create($universidad);
        }
    }
}
