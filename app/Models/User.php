<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'bio',
        'avatar',
        'avatar_url',
        'banner',
        'banner_url',
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
        return $this->belongsTo(Imagen::class, 'avatar_foreign_id', 'id');
    }

    public function getAvatarForeignIdAttribute()
    {
        return $this->getRawOriginal('avatar');
    }

    public function bannerImg(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'banner_foreign_id', 'id');
    }

    public function getBannerForeignIdAttribute()
    {
        return $this->getRawOriginal('banner');
    }

    /**
     * Retorna la URL del avatar del usuario (URL directa o imagen subida en storage).
     */
    public function getAvatarAttribute($value): ?string
    {
        if (!empty($this->avatar_url)) {
            return $this->avatar_url;
        }

        $raw = $this->getRawOriginal('avatar');
        if (!empty($raw)) {
            if (is_numeric($raw)) {
                $img = $this->relationLoaded('avatarImg') ? $this->getRelation('avatarImg') : Imagen::find($raw);
                return $img && $img->ruta ? asset('storage/' . $img->ruta) : null;
            }
            if (is_string($raw) && (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://'))) {
                return $raw;
            }
        }

        return null;
    }

    /**
     * Asigna el avatar: si es una URL se guarda en avatar_url, si es numérico en avatar (FK).
     */
    public function setAvatarAttribute($value): void
    {
        if (is_numeric($value)) {
            $this->attributes['avatar'] = $value;
        } elseif (is_string($value) && (str_starts_with($value, 'http://') || str_starts_with($value, 'https://'))) {
            $this->attributes['avatar_url'] = $value;
        } elseif (empty($value)) {
            $this->attributes['avatar'] = null;
            $this->attributes['avatar_url'] = null;
        } else {
            $this->attributes['avatar'] = $value;
        }
    }

    /**
     * Retorna la URL del banner del usuario (URL directa o imagen subida en storage).
     */
    public function getBannerAttribute($value): ?string
    {
        if (!empty($this->banner_url)) {
            return $this->banner_url;
        }

        $raw = $this->getRawOriginal('banner');
        if (!empty($raw)) {
            if (is_numeric($raw)) {
                $img = $this->relationLoaded('bannerImg') ? $this->getRelation('bannerImg') : Imagen::find($raw);
                return $img && $img->ruta ? asset('storage/' . $img->ruta) : null;
            }
            if (is_string($raw) && (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://'))) {
                return $raw;
            }
        }

        return null;
    }

    /**
     * Asigna el banner: si es una URL se guarda en banner_url, si es numérico en banner (FK).
     */
    public function setBannerAttribute($value): void
    {
        if (is_numeric($value)) {
            $this->attributes['banner'] = $value;
        } elseif (is_string($value) && (str_starts_with($value, 'http://') || str_starts_with($value, 'https://'))) {
            $this->attributes['banner_url'] = $value;
        } elseif (empty($value)) {
            $this->attributes['banner'] = null;
            $this->attributes['banner_url'] = null;
        } else {
            $this->attributes['banner'] = $value;
        }
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
     * Becas a las que se ha postulado el usuario.
     */
    public function postulaciones(): BelongsToMany
    {
        return $this->belongsToMany(Beca::class, 'postulaciones')
                    ->withPivot('estado', 'postulado_at')
                    ->withTimestamps();
    }

    /**
     * Becas que el usuario ha guardado como favorito.
     */
    public function becasGuardadas(): BelongsToMany
    {
        return $this->belongsToMany(Beca::class, 'becas_guardadas')
                    ->withTimestamps();
    }

    /**
     * Retorna las iniciales del usuario para avatares generados.
     */
    public function getInicialAttribute(): string
    {
        return strtoupper(substr($this->nombre ?? 'U', 0, 1));
    }
}