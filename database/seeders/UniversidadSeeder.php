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
            ['siglas' => 'utec', 'nombre_completo' => 'Universidad Tecnológica'],
            ['siglas' => 'ufg', 'nombre_completo' => 'Universidad Francisco Gavidia'],
            ['siglas' => 'uees', 'nombre_completo' => 'Universidad Evangélica'],
            ['siglas' => 'unicaes', 'nombre_completo' => 'Universidad Católica'],
        ];

        foreach ($universidades as $universidad) {
            Universidad::create($universidad);
        }
    }
}
