<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }}</title>
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
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-alta { background: #fee2e2; color: #dc2626; }
        .priority-normal { background: #fef9c3; color: #ca8a04; }
        .priority-baja { background: #dcfce7; color: #16a34a; }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $notification->title }}</h1>
            <p>Centro de Operaciones de Seguridad</p>
        </div>

        <div class="content">
            <div class="message-box">
                {!! nl2br(e($notification->message)) !!}
            </div>

            <p style="font-size: 13px; color: #888;">
                Prioridad:
                <span class="priority-badge priority-{{ strtolower($notification->priority) }}">
                    {{ $notification->priority }}
                </span>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Centro de Operaciones de Seguridad. Todos los derechos reservados.</p>
            <p>Este es un mensaje automático del Centro de Operaciones de Seguridad — no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
