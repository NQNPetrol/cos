@extends('layouts.cliente')

@section('title', 'Mesa de Ayuda')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                Mesa de Ayuda
            </h1>
            <p class="text-gray-400 mt-1 ml-[52px]">¿En qué podemos ayudarte? Elegí un tema o escribí tu consulta.</p>
        </div>

        {{-- Chat Container --}}
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl border border-zinc-700 shadow-xl overflow-hidden flex flex-col"
            style="height: calc(100vh - 220px); min-height: 500px;">

            {{-- Messages Area --}}
            <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4" style="scroll-behavior: smooth;">
                {{-- Welcome message from bot --}}
                <div class="flex items-start gap-3 chat-message" data-sender="bot">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-zinc-700/50 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[85%]">
                            <p class="text-gray-200 text-sm">¡Hola! 👋 Soy el asistente del Centro de Operaciones. Puedo
                                guiarte en el uso de la plataforma.</p>
                            <p class="text-gray-300 text-sm mt-2">Elegí un tema o escribí tu consulta:</p>
                        </div>
                        {{-- Quick Action Buttons --}}
                        <div class="flex flex-wrap gap-2 mt-3" id="quickActions">
                            <button class="quick-action-btn" data-topic="crear-ticket" id="btn-crear-ticket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                Crear un ticket
                            </button>
                            <button class="quick-action-btn" data-topic="crear-evento" id="btn-crear-evento">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Crear un evento
                            </button>
                            <button class="quick-action-btn" data-topic="documentacion-patrullas"
                                id="btn-documentacion-patrullas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                Documentación de patrullas
                            </button>
                            <button class="quick-action-btn" data-topic="ver-recorridos" id="btn-ver-recorridos">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ver recorridos
                            </button>
                            <button class="quick-action-btn" data-topic="dashboard" id="btn-dashboard">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Usar el dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input Area --}}
            <div class="border-t border-zinc-700 p-4 bg-zinc-800/50">
                <form id="chatForm" class="flex gap-3">
                    <input type="text" id="chatInput" placeholder="Escribí tu consulta..."
                        class="flex-1 bg-zinc-700/50 border border-zinc-600 text-white text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none placeholder-gray-500 transition-all duration-200"
                        autocomplete="off">
                    <button type="submit" id="chatSendBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm font-medium transition-all duration-200 flex items-center gap-2 hover:shadow-lg hover:shadow-blue-600/20">
                        <span class="hidden sm:inline">Enviar</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        /* Quick Action Buttons */
        .quick-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
            border-radius: 9999px;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .quick-action-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            color: #bfdbfe;
            transform: translateY(-1px);
        }

        .quick-action-btn:active {
            transform: translateY(0);
        }

        /* Chat scrollbar */
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }

        #chatMessages::-webkit-scrollbar-track {
            background: transparent;
        }

        #chatMessages::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 3px;
        }

        #chatMessages::-webkit-scrollbar-thumb:hover {
            background-color: #6b7280;
        }

        #chatMessages {
            scrollbar-width: thin;
            scrollbar-color: #4b5563 transparent;
        }

        /* Bot typing animation */
        .typing-dots {
            display: inline-flex;
            gap: 4px;
            padding: 8px 0;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            background: #6b7280;
            border-radius: 50%;
            animation: typing-bounce 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing-bounce {

            0%,
            80%,
            100% {
                transform: scale(0.6);
                opacity: 0.4;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Message appearing animation */
        .chat-message {
            animation: message-appear 0.3s ease-out;
        }

        @keyframes message-appear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Link button in bot messages */
        .bot-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #93c5fd;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .bot-link-btn:hover {
            background: rgba(59, 130, 246, 0.25);
            border-color: rgba(59, 130, 246, 0.5);
            color: #bfdbfe;
        }

        /* Step list in bot messages */
        .bot-step-list {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0;
        }

        .bot-step-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.25rem 0;
            font-size: 0.8125rem;
            color: #d1d5db;
        }

        .bot-step-number {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6875rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatMessages = document.getElementById('chatMessages');
            const chatForm = document.getElementById('chatForm');
            const chatInput = document.getElementById('chatInput');
            const quickActions = document.getElementById('quickActions');

            // Knowledge base: topics and responses
            const topics = {
                'crear-ticket': {
                    label: 'Crear un ticket',
                    response: `<p class="text-gray-200 text-sm font-medium mb-2">📋 Cómo crear un ticket nuevo:</p>
                    <ol class="bot-step-list">
                        <li><span class="bot-step-number">1</span><span>Andá a la sección <strong>Sistema y Configuración</strong> en el menú lateral.</span></li>
                        <li><span class="bot-step-number">2</span><span>Hacé clic en <strong>Tickets de Sistema</strong>.</span></li>
                        <li><span class="bot-step-number">3</span><span>Presioná el botón <strong>"Nuevo Ticket"</strong>.</span></li>
                        <li><span class="bot-step-number">4</span><span>Completá el <strong>título</strong> (breve descripción del problema).</span></li>
                        <li><span class="bot-step-number">5</span><span>Elegí la <strong>categoría</strong> y <strong>prioridad</strong> correspondientes.</span></li>
                        <li><span class="bot-step-number">6</span><span>Escribí la <strong>descripción completa</strong> del problema o pedido.</span></li>
                        <li><span class="bot-step-number">7</span><span>Hacé clic en <strong>Enviar</strong> para crear el ticket.</span></li>
                    </ol>
                    <p class="text-gray-400 text-xs mt-2">💡 Una vez creado, podrás ver el estado y las respuestas desde el mismo listado de tickets.</p>
                    <a href="{{ route('client.tickets.nuevo') }}" class="bot-link-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ir a Tickets
                    </a>`
                },
                'crear-evento': {
                    label: 'Crear un evento',
                    response: `<p class="text-gray-200 text-sm font-medium mb-2">🚨 Cómo crear un evento nuevo:</p>
                    <ol class="bot-step-list">
                        <li><span class="bot-step-number">1</span><span>Andá a la sección <strong>Eventos</strong> en el menú superior.</span></li>
                        <li><span class="bot-step-number">2</span><span>En el panel lateral, hacé clic en <strong>Nuevo</strong>.</span></li>
                        <li><span class="bot-step-number">3</span><span>Completá los datos: <strong>categoría, tipo de evento, fecha/hora y ubicación</strong>.</span></li>
                        <li><span class="bot-step-number">4</span><span>Agregá una <strong>descripción</strong> con los detalles del evento.</span></li>
                        <li><span class="bot-step-number">5</span><span>Opcionalmente adjuntá archivos o fotos relacionadas.</span></li>
                        <li><span class="bot-step-number">6</span><span>Hacé clic en <strong>Guardar</strong> para registrar el evento.</span></li>
                    </ol>
                    <p class="text-gray-400 text-xs mt-2">💡 Los eventos quedan registrados y pueden consultarse en el listado y el dashboard.</p>
                    <a href="{{ route('client.eventos.create') }}" class="bot-link-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Crear Evento
                    </a>`
                },
                'documentacion-patrullas': {
                    label: 'Documentación de patrullas',
                    response: `<p class="text-gray-200 text-sm font-medium mb-2">🚔 Documentación de patrullas:</p>
                    <ol class="bot-step-list">
                        <li><span class="bot-step-number">1</span><span>Andá a la sección <strong>Patrullas</strong> en el menú superior.</span></li>
                        <li><span class="bot-step-number">2</span><span>En el panel lateral, hacé clic en <strong>Administrar Patrullas</strong> para ver todas las patrullas.</span></li>
                        <li><span class="bot-step-number">3</span><span>Seleccioná una patrulla para ver su <strong>documentación</strong>, dispositivos y personal asignado.</span></li>
                        <li><span class="bot-step-number">4</span><span>Usá el <strong>Checklist</strong> para registrar las verificaciones diarias de la patrulla.</span></li>
                        <li><span class="bot-step-number">5</span><span>Consultá el <strong>Calendario</strong> para ver la planificación de patrullas.</span></li>
                    </ol>
                    <p class="text-gray-400 text-xs mt-2">💡 También podés ver las patrullas en el mapa en tiempo real desde "Ver en el Mapa".</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('client.patrullas.index') }}" class="bot-link-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Administrar Patrullas
                        </a>
                        <a href="{{ route('client.checklist.index') }}" class="bot-link-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Checklist
                        </a>
                    </div>`
                },
                'ver-recorridos': {
                    label: 'Ver recorridos',
                    response: `<p class="text-gray-200 text-sm font-medium mb-2">🗺️ Cómo ver los recorridos:</p>
                    <ol class="bot-step-list">
                        <li><span class="bot-step-number">1</span><span>Andá a la sección <strong>Supervisores y Recorridos</strong> en el menú superior.</span></li>
                        <li><span class="bot-step-number">2</span><span>Hacé clic en <strong>Mis Recorridos</strong> para ver los recorridos activos.</span></li>
                        <li><span class="bot-step-number">3</span><span>Seleccioná un recorrido para ver su ruta en el mapa, waypoints y velocidad máxima.</span></li>
                        <li><span class="bot-step-number">4</span><span>Para ver el historial, hacé clic en <strong>Historial de Recorridos</strong>.</span></li>
                    </ol>
                    <p class="text-gray-400 text-xs mt-2">💡 Los recorridos se cargan desde archivos KML/KMZ con la ruta planificada.</p>
                    <a href="{{ route('client.recorridos.index') }}" class="bot-link-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Ver Recorridos
                    </a>`
                },
                'dashboard': {
                    label: 'Usar el dashboard',
                    response: `<p class="text-gray-200 text-sm font-medium mb-2">📊 Cómo usar el dashboard:</p>
                    <ol class="bot-step-list">
                        <li><span class="bot-step-number">1</span><span>Hacé clic en el ícono de <strong>Inicio</strong> (🏠) en el menú superior para ir al dashboard principal.</span></li>
                        <li><span class="bot-step-number">2</span><span>Vas a encontrar <strong>estadísticas de eventos</strong>, gráficos y mapas de calor.</span></li>
                        <li><span class="bot-step-number">3</span><span>Usá los <strong>filtros de fecha y cliente</strong> en la parte superior para acotar la información.</span></li>
                        <li><span class="bot-step-number">4</span><span>Podés generar un <strong>PDF del reporte</strong> con el botón "Generar PDF".</span></li>
                        <li><span class="bot-step-number">5</span><span>También tenés acceso al <strong>Dashboard de Patrullas</strong> y al <strong>Dashboard Operacional</strong> desde el menú lateral.</span></li>
                    </ol>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('client.dashboard') }}" class="bot-link-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Ir al Dashboard
                        </a>
                        <a href="{{ route('client.dashboard-patrullas') }}" class="bot-link-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Dashboard Patrullas
                        </a>
                    </div>`
                }
            };

            // Keyword matching for free text input
            const keywords = {
                'crear-ticket': ['ticket', 'tickets', 'problema', 'reclamo', 'soporte', 'incidencia', 'reporte', 'reportar', 'crear ticket', 'nuevo ticket', 'ayuda técnica', 'incidente'],
                'crear-evento': ['evento', 'eventos', 'crear evento', 'nuevo evento', 'registrar evento', 'alarma', 'novedad', 'alerta'],
                'documentacion-patrullas': ['patrulla', 'patrullas', 'documentación', 'documentacion', 'checklist', 'check', 'verificación', 'verificacion', 'móvil', 'movil', 'vehículo', 'vehiculo'],
                'ver-recorridos': ['recorrido', 'recorridos', 'ruta', 'rutas', 'kml', 'kmz', 'mapa', 'trayecto', 'waypoints', 'historial recorrido'],
                'dashboard': ['dashboard', 'estadísticas', 'estadisticas', 'gráfico', 'grafico', 'resumen', 'pdf', 'informe', 'reporte general']
            };

            // Find matching topic from user text
            function findTopic(text) {
                const lower = text.toLowerCase().trim();
                let bestMatch = null;
                let bestScore = 0;

                for (const [topicId, kws] of Object.entries(keywords)) {
                    for (const kw of kws) {
                        if (lower.includes(kw) && kw.length > bestScore) {
                            bestMatch = topicId;
                            bestScore = kw.length;
                        }
                    }
                }
                return bestMatch;
            }

            // Add a user message to chat
            function addUserMessage(text) {
                const msgHtml = `
                <div class="flex items-start gap-3 justify-end chat-message" data-sender="user">
                    <div class="flex-1 flex justify-end">
                        <div class="bg-blue-600/20 border border-blue-500/20 rounded-2xl rounded-tr-sm px-4 py-3 max-w-[85%]">
                            <p class="text-blue-100 text-sm">${escapeHtml(text)}</p>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-zinc-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            `;
                chatMessages.insertAdjacentHTML('beforeend', msgHtml);
                scrollToBottom();
            }

            // Show typing indicator
            function showTyping() {
                const typingHtml = `
                <div class="flex items-start gap-3 chat-message" id="typingIndicator" data-sender="bot">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="bg-zinc-700/50 rounded-2xl rounded-tl-sm px-4 py-3">
                        <div class="typing-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            `;
                chatMessages.insertAdjacentHTML('beforeend', typingHtml);
                scrollToBottom();
            }

            // Remove typing indicator
            function removeTyping() {
                const typing = document.getElementById('typingIndicator');
                if (typing) typing.remove();
            }

            // Add a bot message to chat
            function addBotMessage(html, showQuickActions = true) {
                removeTyping();
                let actionsHtml = '';
                if (showQuickActions) {
                    actionsHtml = `
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button class="quick-action-btn" data-topic="crear-ticket">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            Crear un ticket
                        </button>
                        <button class="quick-action-btn" data-topic="crear-evento">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Crear un evento
                        </button>
                        <button class="quick-action-btn" data-topic="documentacion-patrullas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Documentación de patrullas
                        </button>
                        <button class="quick-action-btn" data-topic="ver-recorridos">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Ver recorridos
                        </button>
                        <button class="quick-action-btn" data-topic="dashboard">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Usar el dashboard
                        </button>
                    </div>
                `;
                }

                const msgHtml = `
                <div class="flex items-start gap-3 chat-message" data-sender="bot">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="bg-zinc-700/50 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[85%]">
                            ${html}
                        </div>
                        ${actionsHtml}
                    </div>
                </div>
            `;
                chatMessages.insertAdjacentHTML('beforeend', msgHtml);
                scrollToBottom();
                bindQuickActions();
            }

            // Fallback message
            function addFallbackMessage() {
                const html = `
                <p class="text-gray-200 text-sm">No encontré una respuesta para eso. 🤔</p>
                <p class="text-gray-300 text-sm mt-2">Podés elegir alguno de estos temas, o escribir palabras clave como "<strong>ticket</strong>", "<strong>evento</strong>", "<strong>patrullas</strong>", "<strong>recorridos</strong>" o "<strong>dashboard</strong>".</p>
            `;
                addBotMessage(html, true);
            }

            // Handle topic selection
            function handleTopic(topicId) {
                const topic = topics[topicId];
                if (!topic) return;

                addUserMessage(topic.label);

                setTimeout(() => {
                    showTyping();
                    setTimeout(() => {
                        addBotMessage(topic.response, true);
                    }, 600 + Math.random() * 400);
                }, 200);
            }

            // Handle free text
            function handleFreeText(text) {
                if (!text.trim()) return;

                addUserMessage(text);
                chatInput.value = '';

                const topicId = findTopic(text);

                setTimeout(() => {
                    showTyping();
                    setTimeout(() => {
                        if (topicId && topics[topicId]) {
                            addBotMessage(topics[topicId].response, true);
                        } else {
                            addFallbackMessage();
                        }
                    }, 600 + Math.random() * 400);
                }, 200);
            }

            // Bind quick action buttons (initial + newly added)
            function bindQuickActions() {
                document.querySelectorAll('.quick-action-btn').forEach(btn => {
                    // Prevent duplicate listeners
                    if (btn.dataset.bound) return;
                    btn.dataset.bound = 'true';
                    btn.addEventListener('click', function () {
                        const topicId = this.dataset.topic;
                        handleTopic(topicId);
                    });
                });
            }

            // Scroll to bottom of chat
            function scrollToBottom() {
                setTimeout(() => {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 50);
            }

            // Escape HTML to prevent XSS
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Form submit handler
            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const text = chatInput.value.trim();
                if (text) {
                    handleFreeText(text);
                }
            });

            // Focus input on load
            chatInput.focus();

            // Bind initial quick actions
            bindQuickActions();
        });
    </script>
@endpush