<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>En Mantenimiento | {{ config('app.name', 'COS') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #020e4a 0%, #0a1e6e 40%, #1a3a8f 100%);
            color: #e2e8f0;
            overflow: hidden;
            position: relative;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .bg-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: .15;
            pointer-events: none;
        }
        .bg-glow--1 { top: -200px; left: -100px; background: #3b82f6; }
        .bg-glow--2 { bottom: -200px; right: -100px; background: #6366f1; }

        .card {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 520px;
            width: 90%;
            padding: 3rem 2.5rem;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 24px;
            backdrop-filter: blur(20px);
            box-shadow: 0 30px 80px rgba(0,0,0,.3);
        }

        .icon-wrapper {
            width: 90px;
            height: 90px;
            margin: 0 auto 1.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59,130,246,.2), rgba(99,102,241,.2));
            border: 1px solid rgba(99,102,241,.25);
            animation: pulse-ring 3s ease-in-out infinite;
        }

        .icon-wrapper svg {
            width: 40px;
            height: 40px;
            color: #93aafd;
        }

        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,.3); }
            50% { box-shadow: 0 0 0 20px rgba(99,102,241,0); }
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: .5rem;
            letter-spacing: -.02em;
        }

        .subtitle {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,.08);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .progress-bar__fill {
            height: 100%;
            width: 30%;
            background: linear-gradient(90deg, #3b82f6, #6366f1, #3b82f6);
            background-size: 200% 100%;
            border-radius: 4px;
            animation: progress-move 2s ease-in-out infinite;
        }

        @keyframes progress-move {
            0%   { width: 10%; margin-left: 0; }
            50%  { width: 40%; margin-left: 30%; }
            100% { width: 10%; margin-left: 90%; }
        }

        .info-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            margin-bottom: 2rem;
        }

        .info-card {
            padding: 1rem;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 12px;
            text-align: center;
        }

        .info-card__icon {
            width: 20px;
            height: 20px;
            margin: 0 auto .5rem;
            color: #6366f1;
        }

        .info-card__label {
            font-size: .75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .2rem;
        }

        .info-card__value {
            font-size: .9rem;
            font-weight: 600;
            color: #cbd5e1;
        }

        .footer-text {
            font-size: .8rem;
            color: #475569;
        }
        .footer-text a {
            color: #6366f1;
            text-decoration: none;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .85rem;
            background: rgba(234,179,8,.1);
            border: 1px solid rgba(234,179,8,.2);
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            color: #facc15;
            margin-bottom: 1.5rem;
        }

        .badge__dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #facc15;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: .3; }
        }

        @media (max-width: 480px) {
            .card { padding: 2rem 1.5rem; }
            h1 { font-size: 1.4rem; }
            .info-cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-glow bg-glow--1"></div>
    <div class="bg-glow bg-glow--2"></div>

    <div class="card">
        <div class="icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.194-.14 1.743" />
            </svg>
        </div>

        <span class="badge">
            <span class="badge__dot"></span>
            Mantenimiento programado
        </span>

        <h1>Estamos mejorando el sistema</h1>
        <p class="subtitle">
            El Centro de Operaciones de Seguridad se encuentra en mantenimiento.
            Estamos trabajando para brindarte una mejor experiencia.
        </p>

        <div class="progress-bar">
            <div class="progress-bar__fill"></div>
        </div>

        <div class="info-cards">
            <div class="info-card">
                <svg class="info-card__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div class="info-card__label">Duración estimada</div>
                <div class="info-card__value">Pocos minutos</div>
            </div>
            <div class="info-card">
                <svg class="info-card__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                <div class="info-card__label">Estado</div>
                <div class="info-card__value">Actualizando</div>
            </div>
        </div>

        <p class="footer-text">
            La página se actualizará automáticamente cuando el servicio esté disponible.<br>
            Si necesitas ayuda, contacta a <a href="mailto:soporte@tudominio.com">soporte</a>.
        </p>
    </div>

    <script>
        setTimeout(function refresh() {
            fetch(window.location.href, { method: 'HEAD' })
                .then(function(r) {
                    if (r.ok) window.location.reload();
                    else setTimeout(refresh, 15000);
                })
                .catch(function() { setTimeout(refresh, 15000); });
        }, 15000);
    </script>
</body>
</html>
