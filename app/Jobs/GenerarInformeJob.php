<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Informe;
use App\Models\Estudiante;
use TCPDF;

class GenerarInformeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cargaId;

    public function __construct($cargaId)
    {
        $this->cargaId = $cargaId;
    }

    public function handle()
    {
        $estudiantes = Estudiante::where('carga_datos_id', $this->cargaId)->get();
        $total = $estudiantes->count();
        $aprobados = $estudiantes->where('calificacion', '>=', 11)->count();
        $riesgo = $estudiantes->where('riesgo_ia', 'alto')->count();

        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Write(0, 'Informe Académico - Universidad Continental', '', 0, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', '', 12);
        $html = "
        <h3>Resumen General</h3>
        <table border='1' cellpadding='5'>
            <tr><th>Total Estudiantes</th><td>$total</td></tr>
            <tr><th>Aprobados</th><td>$aprobados</td></tr>
            <tr><th>En Riesgo (IA)</th><td>$riesgo</td></tr>
        </table><br><br>

        <h3>Lista de Estudiantes</h3>
        <table border='1' cellpadding='4'>
            <tr><th>Nombre</th><th>Código</th><th>Nota</th><th>Asistencia</th><th>Riesgo IA</th></tr>
        ";

        foreach ($estudiantes as $e) {
            $color = $e->riesgo_ia == 'alto' ? ' style="background-color:#ffcccc"' : '';
            $html .= "<tr$color>
                <td>{$e->nombre}</td>
                <td>{$e->codigo}</td>
                <td>{$e->calificacion}</td>
                <td>{$e->asistencia}%</td>
                <td>" . ucfirst($e->riesgo_ia) . "</td>
            </tr>";
        }

        $html .= "</table>";
        $pdf->writeHTML($html);

        $ruta = storage_path("app/public/informes/informe_{$this->cargaId}.pdf");
        $pdf->Output($ruta, 'F');

        Informe::create([
            'carga_datos_id' => $this->cargaId,
            'ruta_pdf' => "informes/informe_{$this->cargaId}.pdf",
            'generado_en' => now(),
        ]);
    }
}