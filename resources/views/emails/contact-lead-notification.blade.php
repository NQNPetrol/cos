<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Lead de Contacto</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1b3761;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1b3761;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #1b3761;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .field-value {
            color: #333;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 6px;
            border-left: 3px solid #3b82f6;
        }
        .message-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        .message-box h3 {
            color: #0369a1;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .cta-button {
            display: inline-block;
            background-color: #1b3761;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo Lead de Contacto</h1>
            <p>Solicitud desde el Landing Page de COS</p>
        </div>
        
        <div style="text-align: center;">
            <span class="badge">NUEVO LEAD</span>
        </div>
        
        <div class="info-grid">
            <div class="field">
                <div class="field-label">Nombre</div>
                <div class="field-value">{{ $lead->nombre }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value">
                    <a href="mailto:{{ $lead->email }}" style="color: #3b82f6;">{{ $lead->email }}</a>
                </div>
            </div>
            
            @if($lead->telefono)
            <div class="field">
                <div class="field-label">Teléfono</div>
                <div class="field-value">
                    <a href="tel:{{ $lead->telefono }}" style="color: #3b82f6;">{{ $lead->telefono }}</a>
                </div>
            </div>
            @endif
            
            @if($lead->empresa)
            <div class="field">
                <div class="field-label">Empresa</div>
                <div class="field-value">{{ $lead->empresa }}</div>
            </div>
            @endif
            
            @if($lead->cargo)
            <div class="field">
                <div class="field-label">Cargo</div>
                <div class="field-value">{{ $lead->cargo }}</div>
            </div>
            @endif
        </div>
        
        <div class="message-box">
            <h3>Mensaje del Lead</h3>
            <p style="margin: 0; white-space: pre-wrap;">{{ $lead->mensaje }}</p>
        </div>
        
        <div style="text-align: center;">
            <a href="mailto:{{ $lead->email }}?subject=Re: Solicitud de Demo COS" class="cta-button">
                Responder al Lead
            </a>
        </div>
        
        <div class="footer">
            <p>
                <strong>Información adicional:</strong><br>
                IP: {{ $lead->ip_address }}<br>
                Fecha: {{ $lead->created_at->format('d/m/Y H:i:s') }}
            </p>
            <p style="margin-top: 15px;">
                Este email fue generado automáticamente desde el sistema COS.<br>
                © {{ date('Y') }} CYHSUR SA - Centro de Operaciones de Seguridad
            </p>
        </div>
    </div>
</body>
</html>
