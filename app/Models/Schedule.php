<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_codigo',
        'salon',
        'ciclo',
        'dia',
        'hora_inicio',
        'hora_fin'
    ];

    public function profesor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_codigo', 'codigo');
    }
}
