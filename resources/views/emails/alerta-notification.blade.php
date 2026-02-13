<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta del Sistema</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .alert-box h3 {
            margin-top: 0;
            color: #92400e;
        }
        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-label {
            font-weight: bold;
            width: 140px;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Alerta del Sistema</h1>
        </div>
        <div class="content">
            <p>Hola {{ $userName }},</p>
            <p>Se ha activado la siguiente alerta:</p>

            <div class="alert-box">
                <h3>{{ $alerta->titulo }}</h3>
                @if($alerta->descripcion)
                    <p>{{ $alerta->descripcion }}</p>
                @endif
            </div>

            <div style="margin: 20px 0;">
                <div class="detail-row">
                    <span class="detail-label">Tipo:</span>
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $alerta->tipo)) }}</span>
                </div>
                @if($alerta->fecha_alerta)
                <div class="detail-row">
                    <span class="detail-label">Fecha alerta:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($alerta->fecha_alerta)->format('d/m/Y') }}</span>
                </div>
                @endif
                @if($alerta->rodado)
                <div class="detail-row">
                    <span class="detail-label">Vehículo:</span>
                    <span class="detail-value">{{ $alerta->rodado->patente ?? 'N/A' }} - {{ $alerta->rodado->marca ?? '' }} {{ $alerta->rodado->modelo ?? '' }}</span>
                </div>
                @endif
                @if($alerta->cliente)
                <div class="detail-row">
                    <span class="detail-label">Cliente:</span>
                    <span class="detail-value">{{ $alerta->cliente->nombre ?? 'N/A' }}</span>
                </div>
                @endif
                @if($alerta->recurrente)
                <div class="detail-row">
                    <span class="detail-label">Recurrencia:</span>
                    <span class="detail-value">{{ ucfirst($alerta->frecuencia_recurrencia ?? 'N/A') }}</span>
                </div>
                @endif
            </div>

            <p style="color: #6b7280; font-size: 14px;">Este es un correo automático generado por el sistema de alertas.</p>
        </div>
        <div class="footer">
            <p>Este correo fue enviado automáticamente. Por favor no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>
