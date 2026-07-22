<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Foro extends Model
{
    protected $fillable = ['universidad_id', 'titulo', 'slug', 'contenido', 'categoriaforo_id', 'carrera_id'];

    protected static function booted()
    {
        static::creating(function ($foro) {
            if (empty($foro->slug)) {
                $foro->slug = Str::slug($foro->titulo.'-'.time());
            }
        });
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->latest(); 
    }
}
