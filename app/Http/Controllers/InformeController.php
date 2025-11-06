<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use App\Models\Informe;

class InformeController extends Controller
{
    // Genera el PDF y lo descarga
    public function generar($carga_id)
    {
        $carga = CargaDatos::with('estudiantes')->findOrFail($carga_id);
        $estudiantes = $carga->estudiantes;

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('Universidad Continental');
        $pdf->SetAuthor('Sistema IA');
        $pdf->SetTitle('Informe Académico - 2025');
        $pdf->SetSubject('Análisis de Rendimiento');
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 15, 'INFORME ACADÉMICO AUTOMÁTICO', 0, 1, 'C');
        $pdf->Ln(5);

        // Resumen
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, "Total estudiantes: {$carga->validos}", 0, 1);
        $pdf->Cell(0, 10, "Precisión IA: {$carga->precision_ia}%", 0, 1);
        $pdf->Ln(5);

        // Tabla
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 8, 'Estudiante', 1);
        $pdf->Cell(30, 8, 'Código', 1);
        $pdf->Cell(25, 8, 'Nota', 1);
        $pdf->Cell(25, 8, 'Asist.%', 1);
        $pdf->Cell(40, 8, 'Riesgo IA', 1);
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        foreach ($estudiantes as $est) {
            $riesgo = $est->riesgo_predicho == 'riesgo' ? 'ALTO RIESGO' : 'APROBADO';
            $pdf->SetTextColor($est->riesgo_predicho == 'riesgo' ? 255 : 0, 0, 0);
            $pdf->Cell(60, 8, $est->nombre, 1);
            $pdf->Cell(30, 8, $est->codigo, 1);
            $pdf->Cell(25, 8, $est->calificacion, 1);
            $pdf->Cell(25, 8, $est->asistencia . '%', 1);
            $pdf->Cell(40, 8, $riesgo, 1);
            $pdf->Ln();
            $pdf->SetTextColor(0, 0, 0);
        }

        // Guardar PDF
        $ruta = 'informes/informe_' . $carga_id . '_' . now()->format('Ymd_His') . '.pdf';
        $fullPath = storage_path('app/public/' . $ruta);
        
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $pdf->Output($fullPath, 'F');

        $informe = Informe::create([
            'carga_datos_id' => $carga_id,
            'ruta_pdf' => $ruta,
            'generado_en' => now(),
            'estado' => 'generado'
        ]);

        return response()->download($fullPath);
    }

    // Muestra un informe en HTML
    public function show($id)
    {
        $informe = Informe::findOrFail($id);
        $carga = $informe->cargaDatos; // relación Informe -> CargaDatos

        return view('informes.show', compact('informe', 'carga'));
    }

    // Descarga el PDF generado
    public function descargar($id)
    {
        $informe = Informe::findOrFail($id);
        $fullPath = storage_path('app/public/' . $informe->ruta_pdf);

        if (!file_exists($fullPath)) {
            abort(404, 'El archivo PDF no existe.');
        }

        return response()->download($fullPath);
    }
}
