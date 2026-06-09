<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beca extends Model
{
    use HasFactory;
    
    protected $fillable = ['titulo', 'descripcion', 'universidad_id'];

    public function universidad(): BelongsTo
    {
        return $this->belongsTo(Universidad::class, 'universidad_id');
    }
}
