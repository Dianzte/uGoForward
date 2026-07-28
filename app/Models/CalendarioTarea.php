<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalendarioTarea extends Model
{
    use HasFactory;

    protected $table = 'calendario_tareas';

    // ¡CRUCIAL! Define los campos que se pueden guardar masivamente:
    protected $fillable = [
        'user_id',
        'beca_id',
        'titulo',
        'fecha',
        'completado'
    ];

    
}