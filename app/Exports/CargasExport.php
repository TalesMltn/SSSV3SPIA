<?php

namespace App\Exports;

use App\Models\CargaDatos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CargasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CargaDatos::with('asignatura')->get(['id', 'archivo_nombre', 'validos', 'precision_ia']);
    }

    public function headings(): array
    {
        return ['ID', 'Archivo', 'Registros Válidos', 'Precisión IA'];
    }
}
