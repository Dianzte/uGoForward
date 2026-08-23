<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'usuario', // <-- AGREGADO AQUÍ
        'nombre',       
        'correo',
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

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function foros(){
        return $this->hasMany(Foro::class);
    }

    public function avatarImg(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'avatar', 'id');
    }

    public function bannerImg(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'banner', 'id');
    }
}