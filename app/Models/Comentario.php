<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['user_id','foro_id', 'contenido'];

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
}
