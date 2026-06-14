<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beca extends Model
{
    use HasFactory;
    
    protected $fillable = ['titulo', 'descripcion', 'universidad_id', 'carrera_id', 'condicion_id', 'vencimiento', 'imagen_id', 'ayuda_id'];

    public function universidad(): BelongsTo
    {
        return $this->belongsTo(Universidad::class, 'universidad_id');
    }

    public function imagen(): BelongsTo
    {
        return $this->belongsTo(Imagen::class, 'imagen_id');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function condicion(): BelongsTo
    {
        return $this->belongsTo(Condicion::class, 'condicion_id');
    }

    public function ayuda(): BelongsTo
    {
        return $this->belongsTo(Ayuda::class, 'ayuda_id');
    }
}
