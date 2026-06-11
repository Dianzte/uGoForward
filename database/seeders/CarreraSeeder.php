<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = [
            ['nombre' => 'Ingeniería en sistemas'],
            ['nombre' => 'Administración de empresas'],
            ['nombre' => 'Arquitectura'],
            ['nombre' => 'Doctorado en medicina'],
            ['nombre' => 'Ciencias jurídicas'],
            ['nombre' => 'Ingeniería civil'],
            
        ];

        foreach ($carreras as $carrera) {
            Carrera::create($carrera);
        }
    }
}
