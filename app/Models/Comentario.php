<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['user_id', 'foro_id', 'contenido', 'padre_id'];

    // hay que completar esto luego
    /* public function user()
    {
        return $this->belongsTo(User::class);
    }
*/

    public function foro()
    {
        return $this->belongsTo(Foro::class);
    }

    
    public function comentarista()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Comentario::class, 'padre_id')->latest();
    }

    
    public function padre()
    {
        return $this->belongsTo(Comentario::class, 'padre_id');
    }
}
