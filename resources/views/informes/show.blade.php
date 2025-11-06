@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Informe Generado</h1>
    <div class="alert alert-success">
        PDF generado con éxito: {{ $carga->validos }} estudiantes válidos | Precisión IA: {{ $carga->precision_ia }}%
    </div>

    <a href="{{ route('cargas.index') }}" class="btn btn-secondary">Volver</a>
    <a href="{{ route('informes.descargar', $informe->id) }}" class="btn btn-primary">Descargar PDF</a>
</div>
@endsection