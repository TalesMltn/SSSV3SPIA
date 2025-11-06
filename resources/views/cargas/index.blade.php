@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8 px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Encabezado con botón -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Gestión Académica</h1>
                <p class="mt-2 text-gray-600">Carga, registra y exporta estudiantes</p>
            </div>

            <!-- BOTÓN PREMIUM: NUEVA CARGA -->
            <a href="{{ route('cargas.create') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Carga
            </a>
        </div>

        <!-- FORMULARIO DE CARGA -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-10 border border-gray-100">
            <form method="POST" action="{{ route('cargas.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Subir archivo -->
                <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border-2 border-dashed border-indigo-300 mb-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Archivo (CSV / XLSX)</label>
                            <input type="file" name="archivo" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                            <p class="mt-2 text-xs text-gray-500">
                                Columnas: <code class="bg-gray-200 px-1 rounded">nombre</code>, <code class="bg-gray-200 px-1 rounded">código</code>, <code class="bg-gray-200 px-1 rounded">calificación</code>, <code class="bg-gray-200 px-1 rounded">asistencia</code>
                            </p>
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                            Cargar y Procesar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- FILTRO Y EXPORTACIÓN -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filtrar por Asignatura</label>
                    <select id="filtro_export" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
                        <option value="">Todas</option>
                        @foreach($asignaturas as $a)
                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <a href="#" id="btn-excel" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        Exportar Excel
                    </a>
                    <a href="#" id="btn-pdf" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        Exportar PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- TABLA DE ESTUDIANTES (5 por página) -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 mb-10">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Estudiantes Registrados</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="tabla-estudiantes">
                    <thead class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Nombre</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Código</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Asignatura</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase">Calificación</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase">Asistencia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($estudiantes as $e)
                            <tr class="hover:bg-gray-50" data-asignatura="{{ $e->asignatura_id }}">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $e->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $e->codigo }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-800">
                                        {{ $e->asignatura->nombre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                        {{ $e->calificacion >= 13 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $e->calificacion }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full" style="width: {{ $e->asistencia }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium">{{ $e->asistencia }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-gray-500">
                                    <p class="text-lg">No hay estudiantes registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $estudiantes->links() }}
            </div>
        </div>

        <!-- TABLA DE CARGAS (Historial) -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Últimas Cargas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Archivo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold uppercase">Asignatura</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase">Válidos</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase">Precisión IA</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($cargas as $carga)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ Str::limit($carga->archivo_nombre, 30) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-800">
                                        {{ $carga->asignatura->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs
                                        {{ $carga->validos >= 90 ? 'bg-green-100 text-green-800' : ($carga->validos >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $carga->validos }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full" style="width: {{ $carga->precision_ia }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium">{{ $carga->precision_ia }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('informes.generar', $carga->id) }}" class="text-xs px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">PDF</a>
                                    <a href="{{ route('informes.descargar', $carga->id) }}" class="text-xs px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200">Excel</a>
                                    <a href="{{ route('envios.formulario', $carga->id) }}" class="text-xs px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">Correo</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-500">
                                    <p class="text-lg">No hay cargas aún</p>
                                    <p class="text-sm">Sube tu primer archivo arriba</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $cargas->links() }}
            </div>
        </div>

    </div>
</div>

<script>
    // Filtro en vivo
    document.getElementById('filtro_export').addEventListener('change', function() {
        const asignaturaId = this.value;
        const filas = document.querySelectorAll('#tabla-estudiantes tbody tr');

        filas.forEach(fila => {
            const asignaturaFila = fila.getAttribute('data-asignatura');
            fila.style.display = (asignaturaId === '' || asignaturaFila === asignaturaId) ? '' : 'none';
        });
    });

    // Exportar con filtro
    document.getElementById('btn-excel').addEventListener('click', function(e) {
        e.preventDefault();
        const asignaturaId = document.getElementById('filtro_export').value;
        window.location = `{{ route('export.excel') }}${asignaturaId ? '?asignatura=' + asignaturaId : ''}`;
    });

    document.getElementById('btn-pdf').addEventListener('click', function(e) {
        e.preventDefault();
        const asignaturaId = document.getElementById('filtro_export').value;
        window.location = `{{ route('export.pdf') }}${asignaturaId ? '?asignatura=' + asignaturaId : ''}`;
    });
</script>
@endsection