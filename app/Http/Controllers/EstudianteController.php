<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;

class EstudianteController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'codigo' => 'required|string|max:255',
        'calificacion' => 'required|numeric|min:0|max:20',
        'asistencia' => 'required|integer|min:0|max:100',
        'asignatura_id' => 'required|integer|exists:asignaturas,id',
    ]);

    Estudiante::create($validated);

    return redirect()->back()->with('success', '✅ Estudiante registrado correctamente.');
} 
    public function index()
    {
        $estudiantes = \App\Models\Estudiante::with('asignatura')->get();
        return view('estudiantes.index', compact('estudiantes'));
    }
}