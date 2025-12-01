<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     * Es crucial incluir 'user_id' ya que se proporciona en el método create().
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'fecha',
    ];

    /**
     * Define la relación con el profesor que tomó la capacitación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}