<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Creado Exitosamente</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .ticket-info {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .highlight {
            color: #667eea;
            font-weight: bold;
            font-size: 1.1em;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Has creado un ticket al COS</h1>
            <p>Centro de Operaciones de Seguridad</p>
        </div>
        
        <div class="content">
            <p>Hola <span class="highlight">{{ $userName }}</span>,</p>
            
            <p>Tu ticket ha sido creado exitosamente. Estos son los detalles:</p>
            
            <div class="ticket-info">
                <h3>Información del Ticket</h3>
                <p><strong>Número de Seguimiento:</strong> <span class="highlight">#{{ $ticket->id }}</span></p>
                <p><strong>Título:</strong> {{ $ticket->titulo }}</p>
                <p><strong>Descripción:</strong> {{ $ticket->descripcion }}</p>
                <p><strong>Categoría:</strong> {{ $ticket->categoria }}</p>
                <p><strong>Prioridad:</strong> 
                    @if($ticket->prioridad == 'alta' || $ticket->prioridad == 'urgente')
                        <span style="color: #dc3545; font-weight: bold;">{{ ucfirst($ticket->prioridad) }}</span>
                    @elseif($ticket->prioridad == 'media')
                        <span style="color: #ffc107; font-weight: bold;">{{ ucfirst($ticket->prioridad) }}</span>
                    @else
                        <span style="color: #28a745; font-weight: bold;">{{ ucfirst($ticket->prioridad) }}</span>
                    @endif
                </p>
                <p><strong>Estado:</strong> <span style="color: #28a745; font-weight: bold;">{{ ucfirst($ticket->estado) }}</span></p>
                <p><strong>Fecha de Creación:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <div class="info-box">
                <p>Te estarán contactando del <strong>Centro de Operaciones de Seguridad (COS)</strong> para dar seguimiento a tu solicitud.</p>
                <p>Mantén este número de ticket (#{{ $ticket->id }}) para cualquier consulta.</p>
            </div>
            
            
            <div style="text-align: center;">
                <a href="{{ url('/tickets/nuevo') }}" class="button">Ver Mi Ticket en el Sistema</a>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Centro de Operaciones de Seguridad. Todos los derechos reservados.</p>
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>