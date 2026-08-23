<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSocioemocionalResultado extends Model
{
    use HasFactory;

    protected $table = 'test_socioemocional_resultados';

    protected $fillable = [
        'user_id',
        'answers',
        'reflection',
        'scores',
        'primary_dimension',
        'secondary_dimension',
        'carrera_sugerida',
        'universidades_sugeridas',
    ];

    protected $casts = [
        'answers' => 'array',
        'scores' => 'array',
        'universidades_sugeridas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}