<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ayuda;

class AyudaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ayuda = [
            ['nombre' => 'Matrícula'],
            ['nombre' => 'Manutención'],
            ['nombre' => 'Transporte'],
            ['nombre' => 'Materiales escolares'],
            ['nombre' => 'Completa'],
        ];

        foreach ($ayuda as $ayuda) {
            Ayuda::create($ayuda);
        }
    }
}
