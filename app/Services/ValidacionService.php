<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Estudiante;
use App\Models\CargaDatos;

class ValidacionService
{
    public function procesarArchivo($archivo, $asignatura_id)
    {
        $data = Excel::toArray([], $archivo)[0];
        $errores = [];
        $validos = 0;
        $total = count($data) - 1;

        foreach ($data as $i => $row) {
            if ($i == 0) continue;

            if (count($row) < 4 || !is_numeric($row[2]) || !is_numeric($row[3])) {
                $errores[] = ['fila' => $i + 1, 'error' => 'Datos incompletos o no numéricos'];
                continue;
            }

            $validos++;
            Estudiante::create([
                'nombre' => $row[0],
                'codigo' => $row[1],
                'calificacion' => $row[2],
                'asistencia' => $row[3],
                'asignatura_id' => $asignatura_id,
                'carga_datos_id' => 0
            ]);
        }

        $precision = $total > 0 ? round(($validos / $total) * 100, 2) : 0;

        return [
            'validos' => $validos,
            'errores' => $errores,
            'precision' => $precision,
            'total' => $total
        ];
    }
}