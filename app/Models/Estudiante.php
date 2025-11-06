<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'asignatura_id',
        'calificacion',
        'asistencia',
        'riesgo_predicho',
        'carga_datos_id'
    ];

    // Relación con asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    // Relación con carga
    public function cargaDatos()
    {
        return $this->belongsTo(CargaDatos::class);
    }
}