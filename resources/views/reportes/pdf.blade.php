<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evento o Incidente</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.4;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        
        .reserved-header {
            background: #d1d5db;
            color: #374151;
            padding: 8px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border-bottom: 2px solid #9ca3af;
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
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .event-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 11px;
            margin-top: 8px;
            display: inline-block;
        }
        
        .content {
            padding: 20px;
        }
        
        .section {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background: #f8fafc;
            page-break-inside: avoid;
            width: 100%;
            box-sizing: border-box;
        }

        .section-flow {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background: #f8fafc;
            width: 100%;
            box-sizing: border-box;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 5px;
            padding: 15px 15px 5px 15px;
        }
        
        .detail-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .detail-grid td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            vertical-align: top;
            width: 25%;
        }
        
        .detail-label {
            font-weight: bold;
            color: #475569;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 3px;
            display: block;
        }
        
        .detail-value {
            font-size: 11px;
            color: #1e293b;
        }
        
        .description {
            background: white;
            padding: 12px;
            border: 1px solid #e2e8f0;
            line-height: 1.5;
            font-size: 11px;
            color: #475569;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
        }
        
        .images-section {
            page-break-inside: avoid;
        }
        
        .image-container {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        .image-placeholder {
            width: 100%;
            height: 150px;
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
            font-size: 11px;
        }
        
        .image-caption {
            font-size: 10px;
            color: #6b7280;
            text-align: center;
            margin-top: 5px;
            font-style: italic;
        }
        
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        
        .inventory-table th,
        .inventory-table td {
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: left;
        }
        
        .inventory-table th {
            background: #f3f4f6;
            font-weight: bold;
        }
        
        .inventory-table td {
            background: white;
        }
        
        .inventory-placeholder {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            font-size: 10px;
            padding: 20px;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
        }
        
        .footer {
            background: #f1f5f9;
            padding: 15px 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #64748b;
        }
        
        .footer-content {
            width: 100%;
        }
        
        .footer-content table {
            width: 100%;
        }
        
        .footer-content .generated-by {
            text-align: left;
        }
        
        .footer-content .timestamp {
            text-align: right;
        }
        
        /* Prioridades */
        .priority-high { background: #fef2f2; border-color: #ef4444; }
        .priority-medium { background: #fef3c7; border-color: #f59e0b; }
        .priority-low { background: #f0fdf4; border-color: #22c55e; }

        .images-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }

        .actual-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background: white;
            page-break-inside: avoid;
        }

        .image-wrapper {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .image-caption {
            font-size: 9px;
            color: #6b7280;
            text-align: center;
            margin-top: 4px;
            font-style: italic;
            page-break-inside: avoid;
        }

        /* Forzar salto de página después de cada par de imágenes */
        .images-grid::after {
            content: "";
            display: table;
            clear: both;
            page-break-after: always;
        }

        /* Asegurar que las imágenes no se corten entre páginas */
        .image-wrapper {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .section > table,
        .section > .description {
            margin-left: 0;
            margin-right: 0;
            width: 100%;
        }

        .section > *:not(.section-title) {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>
</head>
<body>
    <div class="reserved-header">
        RESERVADO PARA SSP - CENTRO DE OPERACIONES DE SEGURIDAD
    </div>

    <div class="container">
        <div class="header">
            <h1>Informe de {{ $evento->tipo }}</h1>
            <p class="subtitle">Documento Oficial del Centro de Operaciones de Seguridad</p>
            <div class="event-badge">
                Evento ID: {{ $evento->id }}
            </div>
        </div>
        
        <div class="content">
            <!-- Sección de Detalles Principales -->
            <div class="section {{ isset($evento->prioridad) ? 'priority-' . strtolower($evento->prioridad) : '' }}">
                <h2 class="section-title">Información del Evento</h2>
                <table class="detail-grid">
                    <tr>
                        <td>
                            <span class="detail-label">Categoría</span>
                            <span class="detail-value">{{ $evento->categoria->nombre ?? 'No especificado' }}</span>
                        </td>
                        <td>
                            <span class="detail-label">Tipo de Evento</span>
                            <span class="detail-value">{{ $evento->tipo ?? 'No especificado' }}</span>
                        </td>
                        <td>
                            <span class="detail-label">Fecha del Evento</span>
                            <span class="detail-value">
                                {{ $evento->fecha_hora ? $evento->fecha_hora->format('d/m/Y H:i') : 'Fecha no especificada' }}
                            </span>
                        </td>
                        <td>
                            <span class="detail-label">Ubicación</span>
                            <span class="detail-value">
                                @if($evento->latitud && $evento->longitud)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $evento->latitud }},{{ $evento->longitud }}">
                                        Ubicación en Google Maps
                                    </a>
                                    <br>
                                    <small style="font-size: 9px; color: #6b7280;">
                                        (Lat: {{ $evento->latitud }}, Long: {{ $evento->longitud }})
                                    </small>
                                @else
                                    Ubicación no especificada
                                @endif
                            </span>
                        </td>
                    </tr>
                </table>
                
                @if(isset($evento->prioridad) || isset($evento->estado) || isset($evento->responsable))
                <table class="detail-grid" style="margin-top: 10px;">
                    <tr>
                        @if(isset($evento->prioridad))
                        <td>
                            <span class="detail-label">Prioridad</span>
                            <span class="detail-value">{{ ucfirst($evento->prioridad) }}</span>
                        </td>
                        @endif
                        @if(isset($evento->estado))
                        <td>
                            <span class="detail-label">Estado</span>
                            <span class="detail-value">{{ ucfirst($evento->estado) }}</span>
                        </td>
                        @endif
                        @if(isset($evento->responsable))
                        <td>
                            <span class="detail-label">Responsable</span>
                            <span class="detail-value">{{ $evento->responsable }}</span>
                        </td>
                        @endif
                        <!-- Relleno para celdas faltantes -->
                        @php
                            $cellCount = (isset($evento->prioridad) ? 1 : 0) + (isset($evento->estado) ? 1 : 0) + (isset($evento->responsable) ? 1 : 0);
                            $emptyCells = 4 - $cellCount;
                        @endphp
                        @for($i = 0; $i < $emptyCells; $i++)
                            <td></td>
                        @endfor
                    </tr>
                </table>
                @endif
            </div>
            
            <!-- Sección de Descripción -->
            @if(isset($evento->descripcion))
            <div class="section-flow">
                <h2 class="section-title">Descripción Detallada</h2>
                <div class="description" style="white-space: pre-line;">
                    {{ $evento->descripcion }}
                </div>
            </div>
            @endif
            
            <!-- Sección de Evidencia -->
            <div class="section images-section">
                <h2 class="section-title">Evidencia Gráfica</h2>

                @if(isset($imagenesBase64) && count($imagenesBase64) > 0)
                    <div class="images-grid">
                        @foreach($imagenesBase64 as $index => $imagen)
                        <div class="image-wrapper">
                            <img src="{{ $imagen['data'] }}" alt="{{ $imagen['name'] }}" class="actual-image" style="max-width: 100%; height: auto; max-height: 320px;">
                            <div class="image-caption">
                                Evidencia {{ $index + 1 }} - {{ $imagen['name'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="image-placeholder">
                        No hay imágenes adjuntas para este evento
                    </div>
                @endif
            </div>
            

            <!-- Sección de Inventario de Elementos Sustraídos -->
            <div class="section">
                <h2 class="section-title">Elementos involucrados en el evento</h2>
                
                @if(!empty($evento->elementos_sustraidos) && !empty($evento->cantidad) && count($evento->elementos_sustraidos) > 0)
                <!-- Tabla-->
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < count($evento->elementos_sustraidos); $i++)
                                @if(isset($evento->elementos_sustraidos[$i]) && isset($evento->cantidad[$i]))
                                    <tr>
                                        <td>{{ $evento->elementos_sustraidos[$i] }}</td>
                                        <td>{{ $evento->cantidad[$i] }}</td>
                                    </tr>
                                @endif
                            @endfor
                        </tbody>
                    </table>
                @else
                    <div class="inventory-placeholder">
                        [INVENTARIO] No se registraron elementos en este incidente
                    </div>
                @endif
            </div>
            
        <!-- Sección de Observaciones Adicionales -->
        @if(isset($evento->observaciones) && $evento->observaciones)
        <div class="section">
            <h2 class="section-title">Observaciones Adicionales</h2>
            <div class="description" style="white-space: pre-line;">
                {{ $evento->observaciones }}
            </div>
        </div>
        @endif
        
        <div class="footer">
            <div class="footer-content">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 60%; vertical-align: top;">
                            <div style="margin-bottom: 5px;">
                                <strong>Registrado por:</strong><br>
                                {{ $evento->creador->name ?? 'Sistema' }}
                            </div>
                            <div>
                                <strong>Firmado por:</strong><br>
                                {{ $usuarioGenerador->name ?? 'Sistema' }}
                            </div>
                        </td>
                        <td style="width: 40%; text-align: right; vertical-align: top;">
                            {{ now()->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>