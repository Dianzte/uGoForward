<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'usuario' => 'antho',
                'nombre' => 'anthony',
                'correo' => 'ant@gmail.com',
                'contrasena' => Hash::make('123456789'),
                'fechaNac' => '2000-10-10',
                'departamento' => 'San Vicente',
                'nie' => '12345678',
                'dui' => '12345678-9',
                'banner' => null,
                'bio' => null,
            ],
            [
                'usuario' => 'rob',
                'nombre' => 'robert',
                'correo' => 'rob@gmail.com',
                'contrasena' => Hash::make('123456789'),
                'fechaNac' => '2000-10-10',
                'departamento' => 'San Vicente',
                'nie' => '23456789',
                'dui' => '23456789-0',
                'banner' => null,
                'bio' => null,
            ],
            [
                'usuario' => 'owen',
                'nombre' => 'owen',
                'correo' => 'owen@gmail.com',
                'contrasena' => Hash::make('123456789'),
                'fechaNac' => '2000-10-10',
                'departamento' => 'San Vicente',
                'nie' => '34567890',
                'dui' => '34567890-1',
                'banner' => null,
                'bio' => null,
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
