<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\factories\HasFactory;

class Beca extends Model
{
    use HasFactory;
    
    protected $fillable = ['titulo', 'descripcion'];
}
