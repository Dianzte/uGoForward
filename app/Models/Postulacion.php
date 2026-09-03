<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postulacion extends Model
{
    protected $table = 'postulaciones';

    protected $fillable = [
        'user_id',
        'beca_id',
        'estado',
    ];

    protected $casts = [
        'postulado_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function beca(): BelongsTo
    {
        return $this->belongsTo(Beca::class);
    }
}
