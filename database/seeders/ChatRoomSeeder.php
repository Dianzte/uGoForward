<?php

namespace Database\Seeders;

use App\Models\ChatRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChatRoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'nombre'      => 'General',
                'slug'        => 'general',
                'descripcion' => 'Sala principal para estudiantes de todas las carreras.',
                'tipo'        => 'general',
                'icono'       => '🌐',
            ],
            [
                'nombre'      => 'Matemáticas',
                'slug'        => 'matematicas',
                'descripcion' => 'Cálculo, álgebra, estadística y todo lo numérico.',
                'tipo'        => 'materia',
                'icono'       => '📐',
            ],
            [
                'nombre'      => 'Programación',
                'slug'        => 'programacion',
                'descripcion' => 'Desarrollo de software, algoritmos, estructuras de datos.',
                'tipo'        => 'materia',
                'icono'       => '💻',
            ],
            [
                'nombre'      => 'Física',
                'slug'        => 'fisica',
                'descripcion' => 'Mecánica, termodinámica, electromagnetismo y más.',
                'tipo'        => 'materia',
                'icono'       => '⚛️',
            ],
            [
                'nombre'      => 'Química',
                'slug'        => 'quimica',
                'descripcion' => 'Orgánica, inorgánica, laboratorio y fórmulas.',
                'tipo'        => 'materia',
                'icono'       => '🧪',
            ],
            [
                'nombre'      => 'Becas y Oportunidades',
                'slug'        => 'becas',
                'descripcion' => 'Comparte convocatorias, tips y experiencias sobre becas.',
                'tipo'        => 'general',
                'icono'       => '🎓',
            ],
            [
                'nombre'      => 'Estudio Libre',
                'slug'        => 'estudio-libre',
                'descripcion' => 'Zona de apoyo mutuo, consejos de estudio y motivación.',
                'tipo'        => 'general',
                'icono'       => '📚',
            ],
        ];

        foreach ($rooms as $room) {
            ChatRoom::firstOrCreate(
                ['slug' => $room['slug']],
                $room
            );
        }

        $this->command->info('✅ Salas de chat creadas correctamente.');
    }
}
