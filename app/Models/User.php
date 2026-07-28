<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

 protected $fillable = [
    'nombre',
    'usuario',
    'correo',
    'email',
    'password',
    'contrasena',
    'fechaNac',
    'departamento',
    'nie',
    'dui',
    'bio',
    'avatar',
    'banner',
];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Indicar a Laravel que la contraseña es el campo 'contrasena'
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}