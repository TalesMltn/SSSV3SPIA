@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-12 px-6 sm:px-8 lg:px-10">
    <div class="max-w-7xl mx-auto">

        <!-- Encabezado -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600">
                Panel de Control
            </h1>
            <p class="mt-4 text-lg text-gray-700">
                Gestiona cargas, informes y envíos académicos con facilidad
            </p>
        </div>

        <!-- Grid de Tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

            <!-- CARGAR DATOS - AZUL VIVO -->
            <a href="{{ route('cargas.index') }}"
               class="group relative bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-xl p-8 text-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 transform">
                <div class="relative z-10 text-center">
                    <div class="mb-4 flex justify-center">
                        <svg class="w-16 h-16 text-white/90" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M4 12l8-8 8 8M12 4v12"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Cargar Datos</h3>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Sube archivos Excel o CSV con estudiantes, asignaturas y calificaciones.
                    </p>
                </div>
                <div classmt-6 flex justify-center">
                    <span class="inline-flex items-center text-white font-semibold text-sm group-hover:underline">
                        Ir al módulo
                        <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>

            <!-- VER INFORMES - VERDE VIVO -->
            <a href="{{ route('informes.show', 1) }}"
               class="group relative bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-xl p-8 text-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 transform">
                <div class="relative z-10 text-center">
                    <div class="mb-4 flex justify-center">
                        <svg class="w-16 h-16 text-white/90" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M5 8h14M4 6a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Ver Informes</h3>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Revisa los boletines generados automáticamente para cada estudiante.
                    </p>
                </div>
                <div class="mt-6 flex justify-center">
                    <span class="inline-flex items-center text-white font-semibold text-sm group-hover:underline">
                        Ver informes
                        <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>

            <!-- ENVIAR INFORMES - AMARILLO VIVO -->
            @if(isset($carga))
                <a href="{{ route('envios.formulario', ['carga_id' => $carga->id]) }}"
                   class="group relative bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-8 text-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 transform">
                    <div class="relative z-10 text-center">
                        <div class="mb-4 flex justify-center">
                            <svg class="w-16 h-16 text-white/90" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Enviar Informes</h3>
                        <p class="text-white/80 text-sm leading-relaxed">
                            Envía los boletines por correo a estudiantes y padres.
                        </p>
                    </div>
                    <div class="mt-6 flex justify-center">
                        <span class="inline-flex items-center text-white font-semibold text-sm group-hover:underline">
                            Enviar ahora
                            <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </a>
            @else
                <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-md p-8 border border-gray-300">
                    <div class="text-center">
                        <div class="text-6xl mb-4 text-gray-400">Warning</div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Enviar Informes</h3>
                        <p class="text-sm text-gray-500">No hay cargas disponibles. Sube datos primero.</p>
                    </div>
                </div>
            @endif

        </div>

        <!-- Footer -->
        <div class="mt-20 text-center text-sm text-gray-600">
            <p>
                © {{ date('Y') }} <span class="text-indigo-600 font-bold">Sistema Académico Inteligente</span>. Todos los derechos reservados.
            </p>
        </div>
    </div>
</div>
@endsection