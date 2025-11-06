<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CargaDatos extends Model
{
    protected $fillable = [
        'precision_validacion',
        'precision_ia',
        'total_filas',
        'validos',
        'errores',
        'archivo_nombre',
        'usuario_id',
        'asignatura_id', // ← AÑADE ESTO
    ];

    // RELACIÓN: Una carga pertenece a una asignatura
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    // RELACIÓN: Una carga tiene muchos estudiantes
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'carga_datos_id');
    }

    // RELACIÓN: Una carga tiene un informe
    public function informe(): HasOne
    {
        return $this->hasOne(Informe::class, 'carga_datos_id');
    }
}