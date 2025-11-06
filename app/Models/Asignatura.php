<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'creditos',
        'docente_id'
    ];

    // Relación con estudiantes
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}