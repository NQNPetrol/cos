<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - {{ $empresaAsociadaNombre }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.4;
            color: #334155;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        
        .reserved-header {
            background: #2563eb;
            color: white;
            padding: 8px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .container {
            max-width: 100%;
            background: white;
        }
        
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 0;
        }
        
        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .header-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 11px;
            margin-top: 10px;
            display: inline-block;
        }
        
        .logo-section {
            text-align: center;
            padding: 10px 0;
        }
        
        .logo-section img {
            max-height: 60px;
            max-width: 150px;
        }
        
        .content {
            padding: 20px;
        }
        
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
        }
        
        /* KPIs */
        .kpi-container {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .kpi-container table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .kpi-box {
            width: 33.33%;
            padding: 15px 10px;
            text-align: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        
        .kpi-value {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .kpi-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }
        
        /* Gráfico de barras por categoría */
        .chart-container {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e2e8f0;
        }
        
        .chart-title {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 15px;
        }
        
        /* Colores para categorías - igual que el dashboard */
        .horizontal-bars {
            margin-top: 10px;
        }
        
        .h-bar-row {
            margin-bottom: 12px;
        }
        
        .h-bar-header {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        
        .h-bar-label {
            display: table-cell;
            font-size: 11px;
            color: #334155;
            width: 60%;
            font-weight: 500;
        }
        
        .h-bar-value {
            display: table-cell;
            font-size: 11px;
            color: #2563eb;
            text-align: right;
            font-weight: bold;
            width: 40%;
        }
        
        .h-bar-track {
            height: 22px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .h-bar-fill {
            height: 100%;
            border-radius: 4px;
        }
        
        /* Leyenda */
        .legend {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 12px;
            margin-bottom: 5px;
            font-size: 9px;
            color: #64748b;
        }
        
        .legend-color {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 4px;
            vertical-align: middle;
        }
        
        /* Monthly bars visual */
        .monthly-visual {
            padding: 10px 0;
        }
        
        .monthly-bars-wrapper {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 5px;
        }
        
        .monthly-bars-grid {
            display: table;
            width: 100%;
            height: 120px;
            table-layout: fixed;
        }
        
        .monthly-bar-cell {
            display: table-cell;
            vertical-align: bottom;
            text-align: center;
            padding: 0 2px;
        }
        
        .monthly-bar-inner {
            width: 80%;
            margin: 0 auto;
            background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%);
            border-radius: 3px 3px 0 0;
            position: relative;
            min-height: 1px;
        }
        
        .monthly-bar-count {
            font-size: 9px;
            font-weight: bold;
            color: #1e293b;
            position: absolute;
            top: -15px;
            left: 0;
            right: 0;
            text-align: center;
        }
        
        .monthly-labels-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .monthly-label-cell {
            display: table-cell;
            text-align: center;
            font-size: 8px;
            color: #64748b;
            padding-top: 5px;
        }
        
        .average-indicator {
            text-align: right;
            font-size: 12px;
            color: #64748b;
            margin-top: 10px;
        }
        
        .average-indicator strong {
            color: #2563eb;
            font-size: 14px;
        }
        
        /* Tabla de datos */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .data-table th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #334155;
            background: white;
        }
        
        .data-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        
        /* Footer */
        .footer {
            background: #f1f5f9;
            padding: 15px 20px;
            border-top: 2px solid #2563eb;
            font-size: 10px;
            color: #64748b;
            margin-top: 20px;
        }
        
        .footer-content table {
            width: 100%;
        }
        
        .page-break {
            page-break-after: always;
        }

        /* Info grid */
        .info-grid {
            display: table;
            width: 100%;
        }

        .info-grid-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 8px;
            vertical-align: top;
        }

        .info-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 13px;
            color: #1e293b;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="reserved-header">
        CENTRO DE OPERACIONES DE SEGURIDAD - REPORTE ESTADÍSTICO
    </div>

    <div class="container">
        <div class="header">
            @if($logoBase64)
                <div class="logo-section">
                    <img src="{{ $logoBase64 }}" alt="Logo">
                </div>
            @endif
            <h1>Reporte de Estadísticas de Eventos</h1>
            <p class="subtitle">{{ $clienteNombre }}</p>
            <div class="header-badge">
                {{ $empresaAsociadaNombre }}
            </div>
        </div>
        
        <div class="content">
            <!-- Información del período -->
            <div class="section">
                <div class="section-title">Período del Reporte</div>
                <div class="info-grid">
                    <div class="info-grid-row">
                        <div class="info-cell" style="width: 50%;">
                            <div class="info-label">Fecha Desde</div>
                            <div class="info-value">{{ $fechaDesde }}</div>
                        </div>
                        <div class="info-cell" style="width: 50%;">
                            <div class="info-label">Fecha Hasta</div>
                            <div class="info-value">{{ $fechaHasta }}</div>
                        </div>
                    </div>
                    <div class="info-grid-row">
                        <div class="info-cell" style="padding-top: 10px;">
                            <div class="info-label">Generado</div>
                            <div class="info-value" style="font-size: 11px;">{{ $fechaGeneracion }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs -->
            <div class="section">
                <div class="section-title">Indicadores Clave (KPIs)</div>
                <div class="kpi-container">
                    <table>
                        <tr>
                            <td class="kpi-box">
                                <div class="kpi-value">{{ number_format($totalEventos) }}</div>
                                <div class="kpi-label">Total de Eventos</div>
                            </td>
                            <td style="width: 10px;"></td>
                            <td class="kpi-box">
                                <div class="kpi-value">{{ number_format($eventosUltimos7Dias) }}</div>
                                <div class="kpi-label">Últimos 7 días</div>
                            </td>
                            <td style="width: 10px;"></td>
                            <td class="kpi-box">
                                <div class="kpi-value">{{ number_format($promedioMensual, 1) }}</div>
                                <div class="kpi-label">Promedio Mensual</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Eventos por Categoría -->
            <div class="section">
                <div class="section-title">Eventos por Categoría</div>
                @if(count($chartDataCategorias) > 0)
                    @php
                        $maxCategoria = max(array_column($chartDataCategorias, 'total'));
                        $coloresCategorias = [
                            'Seguridad física' => '#3b82f6',
                            'Seguridad vial y patrullaje' => '#6366f1',
                            'Tecnológicos/comunicación' => '#8b5cf6',
                            'Entorno y contexto' => '#64748b',
                            'Administrativos/reportables al cliente' => '#10b981',
                            'Salud/Emergencias' => '#1e3a5f',
                            'Otros' => '#22c55e',
                        ];
                        $defaultColors = ['#3b82f6', '#6366f1', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#64748b'];
                        $colorIndex = 0;
                    @endphp
                    
                    <div class="chart-container">
                        <div class="chart-title">Distribución de eventos por categoría</div>
                        
                        <!-- Gráfico de barras horizontales - Estructura compatible con dompdf -->
                        @foreach($chartDataCategorias as $cat)
                            @php
                                $color = $coloresCategorias[$cat['nombre']] ?? $defaultColors[$colorIndex % count($defaultColors)];
                                $colorIndex++;
                                $percentage = $maxCategoria > 0 ? round(($cat['total'] / $maxCategoria) * 100) : 0;
                                $percentage = max($percentage, 3); // mínimo visible
                            @endphp
                            <div style="margin-bottom: 8px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 35%; font-size: 10px; color: #334155; font-weight: 500; padding-right: 8px; vertical-align: middle;">
                                            {{ $cat['nombre'] }}
                                        </td>
                                        <td style="width: 50%; vertical-align: middle; padding: 2px 0;">
                                            <!-- Barra usando tabla anidada con td coloreado -->
                                            <table style="width: 100%; border-collapse: collapse; background-color: #e2e8f0;">
                                                <tr>
                                                    <td style="width: {{ $percentage }}%; height: 16px; background-color: {{ $color }};"></td>
                                                    <td style="width: {{ 100 - $percentage }}%; height: 16px;"></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="width: 15%; font-size: 11px; color: #2563eb; font-weight: bold; padding-left: 8px; text-align: right; vertical-align: middle;">
                                            {{ $cat['total'] }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                        
                        <!-- Leyenda -->
                        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0;">
                            @php $colorIndex = 0; @endphp
                            @foreach($chartDataCategorias as $cat)
                                @php
                                    $color = $coloresCategorias[$cat['nombre']] ?? $defaultColors[$colorIndex % count($defaultColors)];
                                    $colorIndex++;
                                    $nombreCorto = strlen($cat['nombre']) > 25 ? substr($cat['nombre'], 0, 22) . '...' : $cat['nombre'];
                                @endphp
                                <table style="display: inline-table; margin-right: 10px; margin-bottom: 5px;">
                                    <tr>
                                        <td style="width: 10px; height: 10px; background-color: {{ $color }};"></td>
                                        <td style="font-size: 9px; color: #64748b; padding-left: 4px;">{{ $nombreCorto }}</td>
                                    </tr>
                                </table>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p style="color: #64748b; font-style: italic; text-align: center; padding: 20px;">
                        No hay datos de categorías para el período seleccionado.
                    </p>
                @endif
            </div>

            <!-- Tendencia Mensual de Eventos -->
            <div class="section">
                <div class="section-title">Tendencia Mensual de Eventos</div>
                @if(count($datosMensuales) > 0)
                    @php
                        $maxMensual = max(array_column($datosMensuales, 'total'));
                        $totalMeses = count($datosMensuales);
                        $sumaMensual = array_sum(array_column($datosMensuales, 'total'));
                        $promedio = $totalMeses > 0 ? round($sumaMensual / $totalMeses, 1) : 0;
                        $maxBarHeight = 80; // altura máxima en píxeles
                    @endphp
                    
                    <div class="chart-container">
                        <div class="chart-title">Evolución de eventos por mes con promedio</div>
                        
                        <!-- Gráfico de barras - Estructura compatible con dompdf -->
                        @foreach($datosMensuales as $index => $mes)
                            @php
                                $barWidth = $maxMensual > 0 ? round(($mes['total'] / $maxMensual) * 100) : 5;
                                $barWidth = max($barWidth, 5);
                            @endphp
                            <div style="margin-bottom: 8px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 80px; font-size: 9px; color: #64748b; padding-right: 8px; text-align: right; vertical-align: middle;">
                                            {{ $mes['mes'] }}
                                        </td>
                                        <td style="vertical-align: middle; padding: 2px 0;">
                                            <table style="width: 100%; border-collapse: collapse;">
                                                <tr>
                                                    <td style="width: {{ $barWidth }}%; height: 18px; background-color: #3b82f6;"></td>
                                                    <td style="width: {{ 100 - $barWidth }}%;"></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="width: 40px; font-size: 11px; color: #1e293b; font-weight: bold; padding-left: 8px; text-align: left; vertical-align: middle;">
                                            {{ $mes['total'] }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                        
                        <!-- Indicador de promedio -->
                        <div style="text-align: right; font-size: 12px; color: #64748b; margin-top: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0;">
                            Promedio mensual: <strong style="color: #2563eb; font-size: 14px;">{{ $promedio }}</strong>
                        </div>
                        
                        <!-- Leyenda -->
                        <div style="margin-top: 10px;">
                            <span style="display: inline-block; margin-right: 15px; font-size: 9px; color: #64748b;">
                                <span style="display: inline-block; width: 12px; height: 12px; background-color: #3b82f6; margin-right: 4px; vertical-align: middle;"></span>
                                Eventos por mes
                            </span>
                        </div>
                    </div>
                @else
                    <p style="color: #64748b; font-style: italic; text-align: center; padding: 20px;">
                        No hay datos mensuales para el período seleccionado.
                    </p>
                @endif
            </div>

            <!-- Estadísticas Geográficas -->
            @if($eventosPorUbicacion->count() > 0)
            <div class="section">
                <div class="section-title">Estadísticas Geográficas (Top 10 Ubicaciones)</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 30%;">Latitud</th>
                            <th style="width: 30%;">Longitud</th>
                            <th style="width: 30%;">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventosPorUbicacion as $index => $ubicacion)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ number_format($ubicacion->lat, 4) }}</td>
                                <td>{{ number_format($ubicacion->lng, 4) }}</td>
                                <td style="color: #2563eb; font-weight: bold;">{{ $ubicacion->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p style="font-size: 9px; color: #64748b; margin-top: 8px;">
                    * Las ubicaciones están agrupadas por coordenadas aproximadas (2 decimales)
                </p>
            </div>
            @endif

            <!-- Resumen del Período -->
            <div class="section">
                <div class="section-title">Resumen del Período</div>
                <table class="data-table">
                    <tbody>
                        <tr>
                            <td style="width: 40%; font-weight: bold;">Cliente</td>
                            <td>{{ $clienteNombre }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Empresa Asociada</td>
                            <td>{{ $empresaAsociadaNombre }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Período</td>
                            <td>{{ $fechaDesde }} - {{ $fechaHasta }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Total de Eventos</td>
                            <td style="color: #2563eb; font-weight: bold;">{{ number_format($totalEventos) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Categorías Registradas</td>
                            <td>{{ count($chartDataCategorias) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Meses con Actividad</td>
                            <td>{{ count($datosMensuales) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table>
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        <div style="margin-bottom: 5px;">
                            <strong style="color: #1e293b;">Generado por:</strong><br>
                            Sistema Centro de Operaciones de Seguridad
                        </div>
                    </td>
                    <td style="width: 40%; text-align: right; vertical-align: top;">
                        <strong style="color: #1e293b;">{{ $fechaGeneracion }}</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
