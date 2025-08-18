<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evento #{{ $evento->id }}</title>
    <link rel="stylesheet" href="{{ public_path('css/reporte.css') }}">
</head>
<body>
    <div class="header">
        <h1>Reporte de Incidente</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="content">
        <h2>Detalles del Evento</h2>
        <p><strong>Tipo:</strong> {{ $evento->tipo }}</p>
        <p><strong>Fecha:</strong> {{ $evento->fecha ? $evento->fecha->format('d/m/Y') : 'Fecha no especificada' }}</p>
        <p><strong>Ubicación:</strong> {{ $evento->ubicacion }}</p>
        
        <h3>Descripción</h3>
        <p>{{ $evento->descripcion }}</p>
        
        @if(isset($evento->imagenes) && $evento->imagenes->count())
        <h3>Evidencia Fotográfica</h3>
        <p>Ver imágenes adjuntas en el sistema</p>
        @endif
    </div>
    
    <div class="footer">
        <p>Generado por: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>