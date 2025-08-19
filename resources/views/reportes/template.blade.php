<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evento #{{ $evento->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #334155;
            background-color: #f8fafc;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        }
        
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .event-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 32px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            position: relative;
        }
        
        .section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
            border-radius: 2px;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            padding-left: 16px;
        }
        
        .section-title::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
            margin-right: 12px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding-left: 16px;
        }
        
        .detail-item {
            background: white;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .detail-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        
        .detail-value {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
        }
        
        .description {
            padding-left: 16px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            line-height: 1.7;
            font-size: 1rem;
            color: #475569;
        }
        
        .images-info {
            padding-left: 16px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #f59e0b;
            display: flex;
            align-items: center;
        }
        
        .images-info::before {
            content: '';
            font-size: 1.5rem;
            margin-right: 12px;
        }
        
        .footer {
            background: #f1f5f9;
            padding: 24px 40px;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .generated-by {
            font-size: 0.875rem;
            color: #64748b;
            display: flex;
            align-items: center;
        }
        
        .generated-by::before {
            content: '';
            margin-right: 8px;
        }
        
        .timestamp {
            font-size: 0.875rem;
            color: #64748b;
            display: flex;
            align-items: center;
        }
        
        .timestamp::before {
            content: '';
            margin-right: 8px;
        }
        
        .priority-high {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-color: #ef4444;
        }
        
        .priority-medium {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: #f59e0b;
        }
        
        .priority-low {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-color: #22c55e;
        }
        
        /* Print styles */
        @media print {
            body {
                background: white;
            }
            
            .container {
                box-shadow: none;
                border-radius: 0;
            }
            
            .section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte de Incidente</h1>
            <p class="subtitle">Documento Oficial del Sistema de Gestión</p>
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
                        <div class="detail-label">Tipo de Evento</div>
                        <div class="detail-value">{{ $evento->tipo ?? 'No especificado' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Fecha del Evento</div>
                        <div class="detail-value">
                            {{ $evento->fecha ? $evento->fecha->format('d/m/Y H:i') : 'Fecha no especificada' }}
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Ubicación</div>
                        <div class="detail-value">{{ $evento->ubicacion ?? 'Ubicación no especificada' }}</div>
                    </div>
                    @if(isset($evento->prioridad))
                    <div class="detail-item">
                        <div class="detail-label">Prioridad</div>
                        <div class="detail-value">{{ ucfirst($evento->prioridad) }}</div>
                    </div>
                    @endif
                    @if(isset($evento->estado))
                    <div class="detail-item">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value">{{ ucfirst($evento->estado) }}</div>
                    </div>
                    @endif
                    @if(isset($evento->responsable))
                    <div class="detail-item">
                        <div class="detail-label">Responsable</div>
                        <div class="detail-value">{{ $evento->responsable }}</div>
                    </div>
                    @endif
                </div>
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
            @if(isset($evento->imagenes) && $evento->imagenes->count())
            <div class="section">
                <h2 class="section-title">Evidencia Fotográfica</h2>
                <div class="images-info">
                    <div>
                        <strong>{{ $evento->imagenes->count() }}</strong> imagen(es) adjunta(s) disponible(s) en el sistema digital.
                        <br>
                        <small>Las imágenes pueden ser consultadas en la plataforma web para mayor detalle.</small>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Sección de Observaciones Adicionales -->
            @if(isset($evento->observaciones) && $evento->observaciones)
            <div class="section">
                <h2 class="section-title">Observaciones Adicionales</h2>
                <div class="description">
                    {{ $evento->observaciones }}
                </div>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <div class="generated-by">
                    Generado por: {{ auth()->user()->name ?? 'Sistema' }}
                </div>
                <div class="timestamp">
                    {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>