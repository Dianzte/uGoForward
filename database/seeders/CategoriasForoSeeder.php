<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoriasForo;


class CategoriasForoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $categorias = [
            ['categorias' => 'experiencia' ],
            ['categorias' => 'opinion' ],
            ['categorias' => 'metodos_estudio' ],
            ['categorias' => 'variado' ],
            
        ];

        foreach ($categorias as $categoria) {
            CategoriasForo::create($categoria);
        }
    }
}
