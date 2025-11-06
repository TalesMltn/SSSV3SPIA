<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use App\Models\Estudiante;
use App\Models\Asignatura;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Phpml\Classification\NaiveBayes;
use Illuminate\Support\Facades\Auth;

class CargaController extends Controller
{
    public function index()
    {
        // ANTES (causaba el error)
        // $cargas = CargaDatos::withCount('estudiantes')->latest()->take(10)->get();
    
        // AHORA (CORREGIDO - con paginación real)
        $cargas = CargaDatos::withCount('estudiantes')
                            ->with('asignatura')  // para mostrar el nombre
                            ->latest()
                            ->paginate(10);       // ← PAGINACIÓN REAL
    
    // ← AÑADE ESTO: Obtener asignaturas para el filtro
    $asignaturas = Asignatura::all();

    // ← AÑADE ESTO: Estudiantes paginados (5 por página)
    $estudiantes = Estudiante::with('asignatura')->paginate(5);

    // ← PASA LAS 3 VARIABLES
    return view('cargas.index', compact('cargas', 'estudiantes', 'asignaturas'));
    }
    
    public function create()
    {
        $total_estudiantes = Estudiante::count();
        $total_cargas = CargaDatos::count();
        $precision_promedio = CargaDatos::avg('precision_ia') ?? 0;
    
$asignaturas = Asignatura::all();
    $estudiantes = Estudiante::with('asignatura')->get();

    return view('cargas.create', compact('asignaturas', 'estudiantes'));
    }

    public function store(Request $request)
    {
        // Validación mínima
        $request->validate([
            'asignatura_id' => 'required|exists:asignaturas,id',
        ]);

        // Caso 1: se sube un archivo
        if ($request->hasFile('archivo')) {
            $request->validate([
                'archivo' => 'file|mimes:csv,xlsx|max:2048',
            ]);

            $path = $request->file('archivo')->store('cargas');
            $data = Excel::toArray([], $request->file('archivo'))[0];

            $errores = [];
            $validos = 0;
            $samples = [];
            $labels = [];

            foreach ($data as $i => $row) {
                if ($i == 0) continue; // Saltar encabezado

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
                    'asignatura_id' => $request->asignatura_id,
                    'carga_datos_id' => null,

                ]);

                $samples[] = [$row[2], $row[3]];
                $labels[] = $row[2] < 13 ? 'riesgo' : 'aprobado';
            }

            // Entrenamiento IA
            $precision_ia = 0;
            if (count($samples) > 0) {
                $classifier = new NaiveBayes();
                $classifier->train($samples, $labels);

                foreach (Estudiante::latest()->take(count($samples))->get() as $est) {
                    $pred = $classifier->predict([$est->calificacion, $est->asistencia]);
                    $est->update(['riesgo_predicho' => $pred[0]]);
                }

                $correctos = 0;
                foreach ($samples as $j => $s) {
                    if ($classifier->predict($s)[0] === $labels[$j]) $correctos++;
                }
                $precision_ia = round(($correctos / count($samples)) * 100, 2);
            }

            $total = count($data) - 1;
            $precision_validacion = $total > 0 ? round(($validos / $total) * 100, 2) : 0;

            $carga = CargaDatos::create([
                'precision_validacion' => $precision_validacion,
                'precision_ia' => $precision_ia,
                'total_filas' => $total,
                'validos' => $validos,
                'errores' => count($errores),
                'archivo_nombre' => $request->file('archivo')->getClientOriginalName(),
                'usuario_id' => Auth::check() ? Auth::user()->id : 1,
            ]);

            Estudiante::where('carga_datos_id', 0)->update(['carga_datos_id' => $carga->id]);

            return redirect()->route('cargas.index')
                ->with('success', "Archivo procesado con éxito: {$validos} registros válidos, precisión IA {$precision_ia}%");
        }

        // Caso 2: no se sube archivo → registro manual
        if ($request->filled(['nombre', 'codigo', 'calificacion', 'asistencia'])) {
            $estudiante = Estudiante::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'calificacion' => $request->calificacion,
                'asistencia' => $request->asistencia,
                'asignatura_id' => $request->asignatura_id,
                'carga_datos_id' => null,

            ]);

            return redirect()->route('cargas.index')
                ->with('success', "Estudiante '{$estudiante->nombre}' registrado correctamente.");
        }

        // Si no se envió ni archivo ni datos
        return back()->withErrors(['Debe subir un archivo o ingresar los datos del estudiante.']);
    }
}
