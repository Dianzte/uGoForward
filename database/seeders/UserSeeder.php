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
            ['usuario' => 'antho',
            'nombre' => 'anthony',
            'correo' => 'ant@gmail.com',
            'contrasena' => Hash::make('123456789'),
            'fechaNac' => '2000-10-10',
            'departamento' => 'San Vicente',
            'dui' => '123456',
            'banner' => null,
            'bio' => null,],

            ['usuario' => 'rob',
            'nombre' => 'robert',
            'correo' => 'rob@gmail.com',
            'contrasena' => Hash::make('123456789'),
            'fechaNac' => '2000-10-10',
            'departamento' => 'San Vicente',
            'dui' => '123456',
            'banner' => null,
            'bio' => null,],

            ['usuario' => 'owen',
            'nombre' => 'owen',
            'correo' => 'owen@gmail.com',
            'contrasena' => Hash::make('123456789'),
            'fechaNac' => '2000-10-10',
            'departamento' => 'San Vicente',
            'dui' => '123456',
            'banner' => null,
            'bio' => null,],
        ];
foreach ($usuarios as $usuarios) {
            User::create($usuarios);
        }    }
}
