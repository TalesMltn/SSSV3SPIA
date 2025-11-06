<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Estudiante;
use App\Models\CargaDatos;
use Phpml\Classification\NaiveBayes;
use TCPDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\InformeAcademicoMail;

class CargaArchivo extends Component
{
    use WithFileUploads;

    public $archivo;
    public $errores = [];
    public $validos = 0;

    /**
     * Procesa el archivo Excel cargado, valida filas y entrena el modelo IA.
     */
    public function procesar()
    {
        $this->validate([
            'archivo' => 'required|file|mimes:csv,xlsx'
        ]);

        $data = Excel::toArray([], $this->archivo)[0];
        $this->errores = [];
        $this->validos = 0;
        $samples = [];
        $labels = [];

        foreach ($data as $i => $row) {
            if ($i == 0) continue; // Saltar encabezado

            $error = $this->validarFila($row);
            if ($error) {
                $this->errores[] = ['fila' => $i + 1, 'error' => $error];
            } else {
                $this->validos++;
                $estudiante = Estudiante::create($this->mapearFila($row));

                $calificacion = $row[2] ?? 0;
                $asistencia = $row[3] ?? 0;
                $samples[] = [$calificacion, $asistencia];
                $labels[] = $calificacion < 13 ? 'riesgo' : 'aprobado';
            }
        }

        // Entrenamiento del modelo IA
        $precision_ia = 0;
        if (count($samples) > 0) {
            $classifier = new NaiveBayes();
            $classifier->train($samples, $labels);

            foreach (Estudiante::latest()->take(count($samples))->get() as $est) {
                $pred = $classifier->predict([$est->calificacion, $est->asistencia ?? 0]);
                $est->update(['riesgo_predicho' => $pred[0]]);
            }

            $correctos = 0;
            foreach ($samples as $j => $sample) {
                $pred = $classifier->predict($sample);
                if ($pred[0] === $labels[$j]) $correctos++;
            }
            $precision_ia = ($correctos / count($samples)) * 100;
        }

        $total = count($data) - 1;
        $precision_validacion = $total > 0 ? ($this->validos / $total) * 100 : 0;

        // Registrar carga en la BD
        CargaDatos::create([
            'precision_validacion' => round($precision_validacion, 2),
            'precision_ia' => round($precision_ia, 2),
            'total_filas' => $total,
            'validos' => $this->validos,
            'errores' => count($this->errores),
        ]);

        // Emitir evento para la interfaz Livewire
        $this->dispatch('carga-completada', [
            'validos' => $this->validos,
            'errores' => count($this->errores),
            'precision_ia' => round($precision_ia, 2)
        ]);
    }

    /**
     * Valida una fila del archivo Excel.
     */
    private function validarFila($row)
    {
        if (count($row) < 4 || !is_numeric($row[2] ?? '') || !is_numeric($row[3] ?? '')) {
            return 'Fila inválida: debe tener nombre, código, calificación y asistencia (números).';
        }
        return null;
    }

    /**
     * Mapea una fila de datos a los campos del modelo Estudiante.
     */
    private function mapearFila($row)
    {
        return [
            'nombre' => $row[0] ?? '',
            'codigo' => $row[1] ?? '',
            'calificacion' => $row[2] ?? 0,
            'asistencia' => $row[3] ?? 0,
        ];
    }

    /**
     * Genera el informe PDF con los datos procesados.
     */
    public function generarPDF()
    {
        $estudiantes = Estudiante::latest()->take($this->validos)->get();

        $pdf = new TCPDF();
        $pdf->SetCreator('Universidad Continental');
        $pdf->SetAuthor('Sistema IA');
        $pdf->SetTitle('Informe Académico - 2025');
        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'INFORME ACADÉMICO AUTOMÁTICO', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, "Total: {$this->validos} | Errores: " . count($this->errores), 0, 1);
        $pdf->Cell(0, 10, "Precisión IA: " . round(($this->validos / ($this->validos + count($this->errores)) * 100), 2) . '%', 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(50, 8, 'Nombre', 1);
        $pdf->Cell(30, 8, 'Código', 1);
        $pdf->Cell(30, 8, 'Nota', 1);
        $pdf->Cell(30, 8, 'Asist. %', 1);
        $pdf->Cell(40, 8, 'Riesgo (IA)', 1);
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        foreach ($estudiantes as $est) {
            $riesgo = $est->riesgo_predicho == 'riesgo' ? 'ALTO RIESGO' : 'APROBADO';
            $pdf->SetTextColor($est->riesgo_predicho == 'riesgo' ? 255 : 0, $est->riesgo_predicho == 'riesgo' ? 0 : 128, 0);
            $pdf->Cell(50, 8, $est->nombre, 1);
            $pdf->Cell(30, 8, $est->codigo, 1);
            $pdf->Cell(30, 8, $est->calificacion, 1);
            $pdf->Cell(30, 8, $est->asistencia . '%', 1);
            $pdf->Cell(40, 8, $riesgo, 1);
            $pdf->Ln();
            $pdf->SetTextColor(0, 0, 0);
        }

        $this->agregarGraficoPDF($pdf);

        $ruta = storage_path('app/public/informe_' . now()->format('Ymd_His') . '.pdf');
        $pdf->Output($ruta, 'F');

        session()->flash('mensaje', 'PDF generado: ' . basename($ruta));
        session(['ultimo_pdf' => $ruta]);
        $this->dispatch('pdf-generado');
    }

    /**
     * Agrega gráfico de barras al PDF.
     */
    private function agregarGraficoPDF($pdf)
    {
        $validos = $this->validos;
        $errores = count($this->errores);
        $total = $validos + $errores;
        if ($total == 0) return;

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Validación de Datos', 0, 1, 'C');

        $x = 50; 
        $y = $pdf->GetY(); 
        $ancho = 100; 
        $alto = 15;

        $pdf->SetFillColor(76, 175, 80); // verde
        $pdf->Rect($x, $y, $ancho * ($validos / $total), $alto, 'F');

        $pdf->SetFillColor(244, 67, 54); // rojo
        $pdf->Rect($x + ($ancho * ($validos / $total)), $y, $ancho * ($errores / $total), $alto, 'F');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Text($x + 5, $y + 6, "Válidos: {$validos}");
        $pdf->Text($x + $ancho + 5, $y + 6, "Errores: {$errores}");
    }

    /**
     * Envía el PDF generado por correo al docente.
     */
    public function enviarPorCorreo()
    {
        if (!session('ultimo_pdf')) {
            $this->generarPDF();
        }

        $ruta = session('ultimo_pdf');

        if (file_exists($ruta)) {
            /** @var \Illuminate\Contracts\Mail\Mailable $mailable */
            $mailable = new InformeAcademicoMail($ruta, $this->validos, count($this->errores));

            Mail::to('docente@continental.edu.pe')->send($mailable);

            session()->flash('mensaje', 'Informe enviado por correo.');
        } else {
            session()->flash('mensaje', 'Error al enviar correo.');
        }
    }
}
