<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'teacher_id',
        'tema',
        'descripcion',
        'fecha',
    ];
}
