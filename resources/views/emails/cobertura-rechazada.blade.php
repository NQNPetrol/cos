<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobertura Rechazada</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        .reject-box {
            background: #fef2f2;
            border: 1px solid #ef4444;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .reject-box h3 {
            margin-top: 0;
            color: #991b1b;
        }
        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-label {
            font-weight: bold;
            width: 160px;
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
            <h1>Cobertura Rechazada</h1>
        </div>
        <div class="content">
            <p>Hola {{ $userName }},</p>
            <p>Le informamos que la solicitud de cobertura para el siguiente turno ha sido <strong>rechazada</strong>:</p>

            <div class="reject-box">
                <h3>Turno Rechazado</h3>
                <p>La empresa no cubrirá los gastos asociados a este turno mecánico.</p>
            </div>

            <div style="margin: 20px 0;">
                <div class="detail-row">
                    <span class="detail-label">Vehículo:</span>
                    <span class="detail-value">{{ $turno->rodado->patente ?? 'Sin patente' }} - {{ $turno->rodado->marca ?? '' }} {{ $turno->rodado->modelo ?? '' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha del turno:</span>
                    <span class="detail-value">{{ $turno->fecha_hora->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Taller:</span>
                    <span class="detail-value">{{ $turno->taller->nombre ?? 'N/A' }}</span>
                </div>
                @if($turno->descripcion)
                <div class="detail-row">
                    <span class="detail-label">Descripción:</span>
                    <span class="detail-value">{{ $turno->descripcion }}</span>
                </div>
                @endif
            </div>

            <p style="color: #6b7280; font-size: 14px;">Si tiene consultas sobre esta decisión, comuníquese con el administrador del sistema.</p>
        </div>
        <div class="footer">
            <p>Este correo fue enviado automáticamente. Por favor no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>
