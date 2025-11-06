@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8 px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Encabezado con botón -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
            <div class="text-left">
                <h1 class="text-4xl font-bold text-gray-900">Cargar y Gestionar Estudiantes</h1>
                <p class="mt-2 text-lg text-gray-600">Sube archivos, registra manualmente o exporta reportes</p>
            </div>

            <!-- Botón Volver -->
            <!-- BOTÓN CON COLOR PREMIUM (IGUAL AL DE GUARDAR) -->
            <a href="{{ route('cargas.index') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Historial
            </a>
            </div>
        
        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORMULARIO DE CARGA + REGISTRO MANUAL -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-10 border border-gray-100">
            <form method="POST" action="{{ route('cargas.store') }}" enctype="multipart/form-data" id="form-carga">
                @csrf

                <!-- Asignatura (única para ambos) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Asignatura</label>
                    <select name="asignatura_id" id="asignatura_id" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100" required>
                        <option value="">Seleccionar asignatura</option>
                        @foreach($asignaturas as $a)
                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subir archivo 
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
            </form> -->

            <!-- Registro Manual -->
            <div class="border-t pt-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">O registrar un estudiante manualmente</h5>
                <form method="POST" action="{{ route('cargas.store') }}" id="form-manual">
                    @csrf
                    <input type="hidden" name="asignatura_id" id="manual_asignatura">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <input type="text" name="nombre" placeholder="Nombre del estudiante" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100" required>
                        <input type="text" name="codigo" placeholder="Código" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100" required>
                        <input type="number" name="calificacion" min="0" max="20" step="0.01" placeholder="Calificación" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100" required>
                        <input type="number" name="asistencia" min="0" max="100" placeholder="Asistencia %" class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100" required>
                    </div>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                        </svg>
                        Guardar Estudiante
                    </button>
                </form>
            </div>
        </div>

        <!-- FILTRO Y EXPORTACIÓN 
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
        </div> -->

        <!-- TABLA DE ESTUDIANTES -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
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
                                    <p class="text-lg">No hay estudiantes</p>
                                    <p class="text-sm">Comienza registrando uno arriba</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script>
    // Sincronizar asignatura
    document.getElementById('asignatura_id').addEventListener('change', function() {
        document.getElementById('manual_asignatura').value = this.value;
    });

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