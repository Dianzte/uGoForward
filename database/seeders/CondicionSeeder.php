<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Condicion;

class CondicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $condiciones = [
            ['nombre' => 'Promedio global mayor a 7'],
            ['nombre' => 'Promedio global entre 8 y 10'],
            ['nombre' => 'Buena conducta'],
            ['nombre' => 'Participación en actividades extracurriculares'],
            ['nombre' => 'Asistencia regular a clases'],
            ['nombre' => 'Sin restricciones'],
            ['nombre' => 'Otro']
        ];

        foreach ($condiciones as $Condicion) {
            Condicion::create($Condicion);
        }
    }
}
