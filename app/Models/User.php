<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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