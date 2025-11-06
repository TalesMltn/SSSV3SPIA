<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    // EXCEL - con filtro opcional
    public function excel(Request $request)
    {
        $asignaturaId = $request->query('asignatura');

        $query = Estudiante::with('asignatura')
            ->select('nombre', 'codigo', 'calificacion', 'asistencia', 'asignatura_id');

        if ($asignaturaId) {
            $query->where('asignatura_id', $asignaturaId);
        }

        $data = $query->get();

        return Excel::download(
            new class($data) implements 
                \Maatwebsite\Excel\Concerns\FromCollection, 
                \Maatwebsite\Excel\Concerns\WithHeadings 
            {
                protected $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
                public function headings(): array 
                { 
                    return ['Nombre', 'Código', 'Calificación', 'Asistencia', 'Asignatura']; 
                }
            }, 
            $asignaturaId 
                ? "estudiantes_" . $data->first()->asignatura->nombre . ".xlsx"
                : "estudiantes_todos.xlsx"
        );
    }

    // PDF - con filtro opcional
    public function pdf(Request $request)
{
    $asignaturaId = $request->query('asignatura');
    $query = Estudiante::with('asignatura');

    if ($asignaturaId) {
        $query->where('asignatura_id', $asignaturaId);
    }

    $estudiantes = $query->get();

    $titulo = $asignaturaId
        ? "Reporte - " . $estudiantes->first()->asignatura->nombre
        : "Reporte General - Todos los Estudiantes";

    $pdf = Pdf::loadView('exports.estudiantes_pdf', compact('estudiantes', 'titulo'));
    $pdf->setPaper('A4', 'landscape');

    return $pdf->download('reporte_academico.pdf');
}
}