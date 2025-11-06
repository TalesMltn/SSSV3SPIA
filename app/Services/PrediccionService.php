<?php

namespace App\Services;

use Phpml\Classification\NaiveBayes;
use App\Models\Estudiante;

class PrediccionService
{
    public function predecirRiesgo($carga_id)
    {
        $estudiantes = Estudiante::where('carga_datos_id', 0)->get();
        $samples = [];
        $labels = [];

        foreach ($estudiantes as $est) {
            $samples[] = [$est->calificacion, $est->asistencia];
            $labels[] = $est->calificacion < 13 ? 'riesgo' : 'aprobado';
        }

        if (empty($samples)) return 0;

        $classifier = new NaiveBayes();
        $classifier->train($samples, $labels);

        $correctos = 0;
        foreach ($estudiantes as $est) {
            $pred = $classifier->predict([$est->calificacion, $est->asistencia])[0];
            $est->update(['riesgo_predicho' => $pred]);
            if ($pred === ($est->calificacion < 13 ? 'riesgo' : 'aprobado')) {
                $correctos++;
            }
        }

        return round(($correctos / count($samples)) * 100, 2);
    }
}