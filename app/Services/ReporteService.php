<?php

namespace App\Services;

use App\Models\CargaDatos;
use App\Models\Informe;
use TCPDF;

class ReporteService
{
    public function generarPDF($carga_id)
    {
        $carga = CargaDatos::with('estudiantes.asignatura')->findOrFail($carga_id);
        $estudiantes = $carga->estudiantes;

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        $pdf = new \TCPDF();

        $pdf->SetCreator('Universidad Continental');
        $pdf->SetTitle('Informe Académico - IA 2025');
        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'INFORME ACADÉMICO AUTOMÁTICO', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 12);
        $asignatura = $estudiantes->first()->asignatura->nombre ?? 'N/A';
        $pdf->Cell(0, 10, "Asignatura: {$asignatura}", 0, 1);
        $pdf->Cell(0, 10, "Total: {$carga->validos} | Precisión IA: {$carga->precision_ia}%", 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(50, 8, 'Estudiante', 1);
        $pdf->Cell(30, 8, 'Código', 1);
        $pdf->Cell(25, 8, 'Nota', 1);
        $pdf->Cell(25, 8, 'Asist.%', 1);
        $pdf->Cell(40, 8, 'Riesgo IA', 1);
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        foreach ($estudiantes as $est) {
            $riesgo = $est->riesgo_predicho == 'riesgo' ? 'ALTO RIESGO' : 'APROBADO';
            $pdf->SetTextColor($est->riesgo_predicho == 'riesgo' ? 255 : 0, 0, 0);
            $pdf->Cell(50, 8, $est->nombre, 1);
            $pdf->Cell(30, 8, $est->codigo, 1);
            $pdf->Cell(25, 8, $est->calificacion, 1);
            $pdf->Cell(25, 8, $est->asistencia . '%', 1);
            $pdf->Cell(40, 8, $riesgo, 1);
            $pdf->Ln();
            $pdf->SetTextColor(0, 0, 0);
        }

        $ruta = 'informes/informe_' . $carga_id . '_' . now()->format('Ymd_His') . '.pdf';
        $fullPath = storage_path('app/public/' . $ruta);
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $pdf->Output($fullPath, 'F');

        Informe::create([
            'carga_datos_id' => $carga_id,
            'ruta_pdf' => $ruta,
            'generado_en' => now(),
            'estado' => 'generado'
        ]);

        return $fullPath;
    }
}