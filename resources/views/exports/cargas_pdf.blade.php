<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cargas Académicas</title>
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
            border-bottom: 3px solid #6366f1;
            margin-bottom: 25px;
        }
        .logo {
            height: 50px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        tr:nth-child(even) {
            background-color: #f9f9ff;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-excelente { background: #d1fae5; color: #065f46; }
        .badge-bueno     { background: #fef3c7; color: #92400e; }
        .badge-regular   { background: #fee2e2; color: #991b1b; }
        .progress {
            display: inline-block;
            width: 70px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
            margin-right: 8px;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            border-radius: 9999px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <div class="header">
        <!-- Logo (opcional) -->
        <!-- <img src="{{ public_path('img/logo.png') }}" class="logo" alt="Logo"> -->
        <h1 class="title">REPORTE DE CARGAS ACADÉMICAS</h1>
        <p class="subtitle">Sistema Académico Inteligente • {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Tabla -->
    <table>
        <thead>
            <tr>
                <th>Archivo</th>
                <th>Asignatura</th>
                <th>Registros Válidos</th>
                <th>Precisión IA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cargas as $carga)
                <tr>
                    <td>
                        <strong>{{ $carga->archivo_nombre }}</strong>
                    </td>
                    <td>
                        {{ $carga->asignatura->nombre ?? 'Sin asignatura' }}
                    </td>
                    <td>
                        <span class="badge
                            {{ $carga->validos >= 90 ? 'badge-excelente' : ($carga->validos >= 70 ? 'badge-bueno' : 'badge-regular') }}">
                            {{ $carga->validos }}
                        </span>
                    </td>
                    <td>
                        <div style="display: inline-flex; align-items: center;">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $carga->precision_ia }}%"></div>
                            </div>
                            <span style="font-weight: 600;">{{ $carga->precision_ia }}%</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">
                        No hay cargas registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        © {{ date('Y') }} Sistema Académico Inteligente. Generado automáticamente.
    </div>

</body>
</html>