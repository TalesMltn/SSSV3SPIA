@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📚 Lista de Estudiantes</h1>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabla de estudiantes --}}
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Calificación</th>
                <th>Asistencia (%)</th>
                <th>Asignatura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $e)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $e->nombre }}</td>
                    <td>{{ $e->codigo }}</td>
                    <td>{{ $e->calificacion }}</td>
                    <td>{{ $e->asistencia }}</td>
                    <td>{{ $e->asignatura->nombre ?? 'Sin asignatura' }}</td>
                    <td>
                        <form action="{{ route('estudiantes.destroy', $e->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este estudiante?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay estudiantes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('cargas.create') }}" class="btn btn-primary mt-3">⬅️ Volver a Cargar Datos</a>
</div>
@endsection
