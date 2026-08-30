<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // ── Relaciones del Student Hub ──────────────────────────────
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class)->latest();
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->latest();
    }

    /**
     * Retorna las iniciales del usuario para avatares generados.
     */
    public function getInicialAttribute(): string
    {
        return strtoupper(substr($this->nombre ?? 'U', 0, 1));
    }
}