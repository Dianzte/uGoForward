<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BecaGuardada extends Model
{
    protected $table = 'becas_guardadas';

    protected $fillable = [
        'user_id',
        'beca_id',
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
