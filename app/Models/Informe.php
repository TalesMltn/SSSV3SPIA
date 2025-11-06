<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $fillable = [
        'carga_datos_id',
        'ruta_pdf',
        'ruta_excel',
        'generado_en',
        'enviado_por_correo',
        'destinatario_email',
        'estado'
    ];

    protected $casts = [
        'generado_en' => 'datetime',
        'enviado_por_correo' => 'boolean'
    ];

    // Relación con carga
    public function cargaDatos()
    {
        return $this->belongsTo(CargaDatos::class);
    }
}