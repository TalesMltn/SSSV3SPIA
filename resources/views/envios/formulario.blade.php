@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Enviar Informe por Correo</h1>
    <p><strong>Archivo:</strong> {{ $carga->archivo_nombre }}</p>
    <p><strong>Estudiantes válidos:</strong> {{ $carga->validos }}</p>

    <form method="POST" action="{{ route('envio.enviar', $carga->id) }}">
        @csrf
        <div class="mb-3">
            <label>Correo del docente</label>
            <input type="email" name="email" class="form-control" required placeholder="docente@continental.edu.pe">
        </div>
        <button type="submit" class="btn btn-success">Enviar por Correo</button>
        <a href="{{ route('cargas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection