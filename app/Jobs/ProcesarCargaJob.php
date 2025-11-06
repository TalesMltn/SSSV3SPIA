<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CargaDatos;
use App\Models\Estudiante;
use Phpml\Classification\NaiveBayes;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcesarCargaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $archivoPath;
    protected $cargaId;

    public function __construct($archivoPath, $cargaId)
    {
        $this->archivoPath = $archivoPath;
        $this->cargaId = $cargaId;
    }

    public function handle()
    {
        $carga = CargaDatos::find($this->cargaId);
        $spreadsheet = IOFactory::load($this->archivoPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $total = count($rows) - 1;
        $validos = 0;
        $errores = [];
        $datosEntrenamiento = [];
        $etiquetas = [];

        // Entrenar modelo IA
        foreach ($rows as $index => $row) {
            if ($index == 0) continue;
            $calificacion = $row[2] ?? 0;
            $asistencia = $row[3] ?? 0;
            $riesgo = ($calificacion < 11 || $asistencia < 70) ? 'alto' : 'bajo';
            $datosEntrenamiento[] = [$calificacion, $asistencia];
            $etiquetas[] = $riesgo;
        }

        $classifier = new NaiveBayes();
        $classifier->train($datosEntrenamiento, $etiquetas);

        // Procesar estudiantes
        foreach ($rows as $index => $row) {
            if ($index == 0) continue;
            $nombre = $row[0] ?? '';
            $codigo = $row[1] ?? '';
            $calificacion = $row[2] ?? 0;
            $asistencia = $row[3] ?? 0;

            if (empty($nombre) || empty($codigo)) {
                $errores[] = "Fila $index: Nombre o código vacío";
                continue;
            }

            $prediccion = $classifier->predict([$calificacion, $asistencia])[0];

            Estudiante::create([
                'nombre' => $nombre,
                'codigo' => $codigo,
                'calificacion' => $calificacion,
                'asistencia' => $asistencia,
                'riesgo_ia' => $prediccion,
                'carga_datos_id' => $carga->id,
            ]);

            $validos++;
        }

        $precision_ia = $validos > 0 ? round(($validos / $total) * 100, 2) : 0;

        $carga->update([
            'total_filas' => $total,
            'validos' => $validos,
            'errores' => count($errores),
            'precision_ia' => $precision_ia,
        ]);
    }
}