<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'usuario',
        'nombre',
        'correo',
        'contrasena',
        'fechaNac',
        'departamento',
        'nie',
        'dui',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'fechaNac' => 'date',
        ];
    }

    /**
     * Laravel usa 'email' internamente para varias piezas de Auth
     * (notificaciones de reseteo de password, etc). Como tu columna
     * se llama 'correo', le decimos a Laravel dónde buscarlo.
     */
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    /**
     * Laravel llama a este método (no a $this->password directamente)
     * cuando verifica credenciales con Auth::attempt(). Como tu columna
     * de contraseña se llama 'contrasena', la exponemos aquí.
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}

