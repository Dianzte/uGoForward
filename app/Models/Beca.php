<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beca extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titulo', 'titulo_en', 'descripcion', 'descripcion_en', 'universidad_id', 'carrera_id', 'condicion_id', 'vencimiento', 'imagen_id', 'ayuda_id',
        'url_oficial', 'pais_destino', 'pais_destino_en', 'nivel_academico', 'nivel_academico_en', 'modalidad', 'modalidad_en', 'cobertura_resumen', 'cobertura_resumen_en', 'requisitos', 'requisitos_en', 'carreras_cobertura', 'carreras_cobertura_en', 'cum_promedio_minimo', 'estado'
    ];

    protected $casts = [
        'vencimiento' => 'date',
        'requisitos' => 'array',
        'requisitos_en' => 'array',
        'carreras_cobertura' => 'array',
        'carreras_cobertura_en' => 'array',
    ];

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
