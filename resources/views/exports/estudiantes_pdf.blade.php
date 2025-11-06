<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo ?? 'Reporte Académico' }}</title>
    <style>
        @page { margin: 1.5cm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 3px solid #10b981;
            margin-bottom: 25px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            color: #059669;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 5px;
        }
        .info {
            font-size: 10px;
            color: #4b5563;
            margin-top: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f0fdf4;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-aprobado   { background: #d1fae5; color: #065f46; }
        .badge-reprobado  { background: #fee2e2; color: #991b1b; }
        .progress {
            display: inline-block;
            width: 70px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
            margin-right: 8px;
            vertical-align: middle;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #10b981, #059669);
            border-radius: 9999px;
        }
        .stats {
            margin-top: 30px;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            display: flex;
            justify-content: space-around;
            font-size: 11px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- Logo (opcional - reemplaza con tu logo) -->
    <div style="text-align: center;">
        <!-- <img src="{{ public_path('img/logo.png') }}" class="logo" alt="Logo"> -->
        <!-- Descomenta y sube tu logo a public/img/logo.png -->
    </div>

    <!-- Encabezado -->
    <div class="header">
        <h1 class="title">{{ $titulo }}</h1>
        <p class="subtitle">Sistema Académico Inteligente • {{ now()->format('d/m/Y H:i') }}</p>
        @if(request()->has('asignatura'))
            <p class="info">Filtrado por: <strong>{{ $estudiantes->first()->asignatura->nombre }}</strong></p>
        @else
            <p class="info">Todos los estudiantes registrados</p>
        @endif
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Código</th>
                <th>Asignatura</th>
                <th>Calificación</th>
                <th>Asistencia (%)</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $e)
                <tr>
                    <td><strong>{{ $e->nombre }}</strong></td>
                    <td>{{ $e->codigo }}</td>
                    <td>{{ $e->asignatura->nombre }}</td>
                    <td>{{ $e->calificacion }}</td>
                    <td>
                        <div style="display: inline-flex; align-items: center; justify-content: center;">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $e->asistencia }}%"></div>
                            </div>
                            <span style="font-weight: 600;">{{ $e->asistencia }}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $e->calificacion >= 13 ? 'badge-aprobado' : 'badge-reprobado' }}">
                            {{ $e->calificacion >= 13 ? 'Aprobado' : 'Reprobado' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #6b7280; font-style: italic;">
                        No se encontraron estudiantes con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $estudiantes->count() }}</div>
            <div>Total Estudiantes</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($estudiantes->avg('calificacion'), 1) }}</div>
            <div>Promedio Calificación</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($estudiantes->avg('asistencia'), 1) }}%</div>
            <div>Promedio Asistencia</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">
                {{ $estudiantes->where('calificacion', '>=', 13)->count() }} / {{ $estudiantes->count() }}
            </div>
            <div>Aprobados</div>
        </div>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        © {{ date('Y') }} Sistema Académico Inteligente | Generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>