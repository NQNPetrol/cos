<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evento o Incidente</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
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
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 5px;
        }
        
        .detail-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .detail-item {
            display: table-cell;
            padding: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            vertical-align: top;
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
        
        .location-link {
            color: #2563eb;
            text-decoration: underline;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .description {
            background: white;
            padding: 12px;
            border: 1px solid #e2e8f0;
            line-height: 1.5;
            font-size: 11px;
            color: #475569;
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
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 11px;
            text-align: center;
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
            display: table;
            width: 100%;
        }
        
        .generated-by, .timestamp {
            display: table-cell;
            vertical-align: middle;
        }
        
        .timestamp {
            text-align: right;
        }
        
        /* Prioridades */
        .priority-high { background: #fef2f2; border-color: #ef4444; }
        .priority-medium { background: #fef3c7; border-color: #f59e0b; }
        .priority-low { background: #f0fdf4; border-color: #22c55e; }
    </style>
</head>
<body>
    <div class="reserved-header">
        RESERVADO PARA SSP - CENTRO DE OPERACIONES DE SEGURIDAD
    </div>

    <div class="container">
        <div class="header">
            <h1>Reporte de Incidente</h1>
            <p class="subtitle">Documento Oficial del Centro de Operaciones de Seguridad</p>
            <div class="event-badge">
                Evento ID: {{ $evento->id }}
            </div>
        </div>
        
        <div class="content">
            <!-- Sección de Detalles Principales -->
            <div class="section {{ isset($evento->prioridad) ? 'priority-' . strtolower($evento->prioridad) : '' }}">
                <h2 class="section-title">Información del Evento</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Categoría</span>
                        <span class="detail-value">{{ $evento->categoria_id->nombre ?? 'No especificado' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tipo de Evento</span>
                        <span class="detail-value">{{ $evento->tipo ?? 'No especificado' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Fecha del Evento</span>
                        <span class="detail-value">
                            {{ $evento->fecha ? $evento->fecha->format('d/m/Y H:i') : 'Fecha no especificada' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ubicación</span>
                        <span class="detail-value">
                            @if($evento->ubicacion && filter_var($evento->ubicacion, FILTER_VALIDATE_URL))
                                Ubicación en Google Maps
                            @else
                                {{ $evento->ubicacion ?? 'Ubicación no especificada' }}
                            @endif
                    </div>
                </div>
                
                @if(isset($evento->prioridad) || isset($evento->estado) || isset($evento->responsable))
                <div class="detail-grid" style="margin-top: 10px;">
                    @if(isset($evento->prioridad))
                    <div class="detail-item">
                        <span class="detail-label">Prioridad</span>
                        <span class="detail-value">{{ ucfirst($evento->prioridad) }}</span>
                    </div>
                    @endif
                    @if(isset($evento->estado))
                    <div class="detail-item">
                        <span class="detail-label">Estado</span>
                        <span class="detail-value">{{ ucfirst($evento->estado) }}</span>
                    </div>
                    @endif
                    @if(isset($evento->responsable))
                    <div class="detail-item">
                        <span class="detail-label">Responsable</span>
                        <span class="detail-value">{{ $evento->responsable }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Sección de Descripción -->
            @if($evento->descripcion)
            <div class="section">
                <h2 class="section-title">Descripción Detallada</h2>
                <div class="description">
                    {{ $evento->descripcion }}
                </div>
            </div>
            @endif
            
            <!-- Sección de Evidencia -->
            <div class="section images-section">
                <h2 class="section-title">Evidencia Gráfica</h2>

                @php
                    $imagenes = $evento->media ?? colect([]);
                @endphp

                @if($imagenes->count() > 0)
                    @foreach($imagenes as $index => $imagen)
                    <div class="image-container">
                        <div class="image-placeholder">
                            Imagen {{ $index + 1 }}: {{ $imagen->file_name }}<br>
                            <small>(Disponible en el sistema digital)</small>
                        </div>
                        <div class="image-caption">
                            Evidencia {{ $index + 1 }} - {{ $imagen->file_name }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="image-placeholder">
                        No hay imágenes adjuntas para este evento
                    </div>
                @endif
            </div>
            
            <!-- Sección de Observaciones Adicionales -->
            @if(isset($evento->observaciones) && $evento->observaciones)
            <div class="section">
                <h2 class="section-title">Observaciones Adicionales</h2>
                <div class="description">
                    {{ $evento->observaciones }}
                </div>
            </div>
            @endif

            <!-- Sección de Inventario de Elementos Sustraídos -->
            <div class="section">
                <h2 class="section-title">Inventario de Elementos Sustraídos</h2>
                
                <div class="inventory-placeholder">
                    🔍 No se registraron elementos sustraídos en este incidente
                </div>
                
                <!-- Tabla de ejemplo (puedes personalizar según necesites) -->
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Valor Aprox.</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <div class="generated-by">
                    Elaborado por {{ auth()->user()->name ?? 'Sistema' }}
                </div>
                <div class="timestamp">
                    {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>