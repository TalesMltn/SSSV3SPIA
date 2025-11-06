<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

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
        'asignatura_id',
    ];

    // === RELACIONES ===
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'carga_datos_id');
    }

    public function informe(): HasOne
    {
        return $this->hasOne(Informe::class, 'carga_datos_id');
    }

    // =============================================
    // MÉTODOS PARA LLAMAR A STORED PROCEDURES
    // =============================================

    /**
     * 1. Estadísticas por asignatura
     */
    public static function callEstadisticasAsignatura(int $asignatura_id, ?int $carga_datos_id = null): ?object
    {
        $results = DB::select('CALL GetEstadisticasAsignatura(?, ?)', [$asignatura_id, $carga_datos_id]);
        return $results[0] ?? null;
    }

    /**
     * 2. Top 10 estudiantes en riesgo (por esta carga)
     */
    public function callTop10Riesgo(): \Illuminate\Support\Collection
    {
        return collect(DB::select('CALL GetTop10EstudiantesEnRiesgo(?)', [$this->id]));
    }

    /**
     * 3. Resumen de cargas por docente
     */
    public static function callResumenPorDocente(int $docente_id): \Illuminate\Support\Collection
    {
        return collect(DB::select('CALL GetResumenCargasPorDocente(?)', [$docente_id]));
    }

    /**
     * 4. Registrar informe (devuelve ID del informe)
     */
    public function callRegistrarInforme(string $ruta_pdf, ?string $ruta_excel = null, ?string $email = null): int
    {
        DB::statement(
            'CALL RegistrarInformeGenerado(?, ?, ?, ?, @id)',
            [$this->id, $ruta_pdf, $ruta_excel, $email]
        );
        return DB::select('SELECT @id AS id')[0]->id;
    }

    /**
     * 5. Ejecutar IA de riesgo en esta carga
     */
    public function callEjecutarIA(): void
    {
        DB::statement('CALL ValidarEstudiantesIA(?)', [$this->id]);
    }
}