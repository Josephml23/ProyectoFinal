<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // ESTO ES OBLIGATORIO PARA GUARDAR DATOS
    protected $fillable = [
        'user_id',
        'salon',
        'ciclo',
        'dia',
        'hora_inicio',
        'hora_fin',
    ];
}