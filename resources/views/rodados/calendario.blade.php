<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
        <style>
            .fc-event {
                cursor: pointer;
            }
            .fc-toolbar-title {
                color: #f3f4f6;
            }
            .fc-col-header-cell {
                background-color: #1f2937;
                color: #f3f4f6;
            }
            .fc-daygrid-day {
                background-color: #111827;
            }
            .fc-daygrid-day:hover {
                background-color: #1f2937;
            }
        </style>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Calendario de Rodados</h2>
                            <p class="text-gray-400 mt-1">Visualiza turnos, cambios de equipos y pagos en el calendario</p>
                        </div>
                        <a href="{{ route('rodados.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            Volver a Gestión
                        </a>
                    </div>

                    <!-- Mensajes -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Calendario -->
                    <div id="calendar" class="bg-gray-800 rounded-lg p-4">
                        <div class="text-center text-gray-400 p-8">
                            Cargando calendario...
                        </div>
                    </div>
                    <div id="calendar-error" class="hidden text-center text-red-400 p-4 bg-red-900/30 rounded-lg mt-4">
                        <p class="font-semibold">Error al cargar el calendario</p>
                        <p class="text-sm mt-2">Verifica tu conexión a internet. FullCalendar se carga desde CDN.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de detalles del evento -->
    <div id="evento-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-gray-800 border-gray-700">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="evento-modal-title" class="text-lg font-medium text-gray-100">Detalles del Evento</h3>
                    <button onclick="closeEventoModal()"
                        class="text-gray-400 hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div id="evento-modal-content" class="space-y-4">
                    <!-- El contenido se cargará dinámicamente -->
                </div>

                <div id="evento-modal-actions" class="flex justify-end space-x-3 mt-6">
                    <!-- Los botones se cargarán dinámicamente según el tipo de evento -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js" 
                onerror="console.error('Error al cargar FullCalendar'); document.getElementById('calendar').innerHTML='<div class=\"text-center text-red-400 p-8\">Error: No se pudo cargar la librería del calendario. Verifica tu conexión a internet.</div>';"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/es.js"
                onerror="console.warn('Error al cargar locale español, usando inglés por defecto');"></script>
        <script>
            let calendar = null;

            document.addEventListener('DOMContentLoaded', function() {
                // Verificar que FullCalendar esté disponible
                if (typeof FullCalendar === 'undefined') {
                    const calendarEl = document.getElementById('calendar');
                    const errorDiv = document.getElementById('calendar-error');
                    if (calendarEl) {
                        calendarEl.innerHTML = '<div class="text-center text-red-400 p-8">Error: No se pudo cargar la librería del calendario. Verifica tu conexión a internet.</div>';
                    }
                    if (errorDiv) {
                        errorDiv.classList.remove('hidden');
                    }
                    return;
                }

                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) {
                    console.error('Elemento del calendario no encontrado');
                    return;
                }

                try {
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        events: {
                            url: '{{ route("rodados.calendario.eventos") }}',
                            failure: function() {
                                console.error('Error al cargar eventos del calendario');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-center text-red-400 p-4 bg-red-900/30 rounded-lg';
                                errorDiv.textContent = 'Error al cargar los eventos. Por favor, recarga la página.';
                                calendarEl.appendChild(errorDiv);
                            }
                        },
                        eventClick: function(info) {
                            try {
                                const eventId = info.event.id;
                                if (!eventId) {
                                    console.error('ID de evento no válido');
                                    alert('Error: No se pudo identificar el evento.');
                                    return;
                                }

                                const parts = eventId.split('_');
                                if (parts.length < 2) {
                                    console.error('Formato de ID de evento inválido:', eventId);
                                    alert('Error: Formato de evento no válido.');
                                    return;
                                }

                                const tipo = parts[0];
                                const id = parts[1];

                                if (!tipo || !id) {
                                    console.error('Tipo o ID de evento vacío');
                                    alert('Error: Información del evento incompleta.');
                                    return;
                                }

                                loadEventoDetalle(tipo, id);
                            } catch (error) {
                                console.error('Error en eventClick:', error);
                                alert('Error al abrir los detalles del evento.');
                            }
                        },
                        eventColor: function(info) {
                            try {
                                const eventId = info.event.id || '';
                                if (eventId.startsWith('turno_')) {
                                    const tipo = info.event.extendedProps?.tipo_servicio;
                                    if (tipo === 'turno_service') {
                                        return '#3b82f6'; // Azul
                                    } else if (tipo === 'turno_mecanico') {
                                        return '#f97316'; // Naranja
                                    }
                                } else if (eventId.startsWith('cambio_')) {
                                    return '#10b981'; // Verde
                                } else if (eventId.startsWith('pago_')) {
                                    return '#ef4444'; // Rojo
                                }
                                return '#6b7280'; // Gris por defecto
                            } catch (error) {
                                console.error('Error en eventColor:', error);
                                return '#6b7280';
                            }
                        },
                        loading: function(isLoading) {
                            if (isLoading) {
                                calendarEl.style.opacity = '0.5';
                            } else {
                                calendarEl.style.opacity = '1';
                            }
                        }
                    });

                    calendar.render();
                } catch (error) {
                    console.error('Error al inicializar el calendario:', error);
                    calendarEl.innerHTML = '<div class="text-center text-red-400 p-8">Error al inicializar el calendario. Por favor, recarga la página.</div>';
                }
            });

            function loadEventoDetalle(tipo, id) {
                const modal = document.getElementById('evento-modal');
                const content = document.getElementById('evento-modal-content');
                const actions = document.getElementById('evento-modal-actions');
                const title = document.getElementById('evento-modal-title');

                if (!modal || !content || !actions || !title) {
                    console.error('Elementos del modal no encontrados');
                    alert('Error: No se pudo abrir el modal de detalles.');
                    return;
                }

                // Validar parámetros
                if (!tipo || !id) {
                    console.error('Tipo o ID inválido:', { tipo, id });
                    alert('Error: Información del evento inválida.');
                    return;
                }

                // Mostrar modal y mostrar loading
                modal.classList.remove('hidden');
                content.innerHTML = '<p class="text-gray-300">Cargando detalles...</p>';
                actions.innerHTML = '';

                const url = `{{ route("rodados.calendario.evento", ["tipo" => ":tipo", "id" => ":id"]) }}`.replace(':tipo', encodeURIComponent(tipo)).replace(':id', encodeURIComponent(id));

                // Cargar detalles via AJAX
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data) {
                            throw new Error('Respuesta vacía del servidor');
                        }

                        if (data.error) {
                            content.innerHTML = `<p class="text-red-400">${escapeHtml(data.error)}</p>`;
                            return;
                        }

                        if (!data.tipo || !data.data) {
                            throw new Error('Datos del evento incompletos');
                        }

                        // Construir contenido según el tipo
                        let html = '';
                        let actionButtons = '';

                        if (data.tipo === 'turno') {
                            title.textContent = 'Detalles del Turno';
                            html = `
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400">Vehículo:</label>
                                            <p class="text-gray-100">${data.data.rodado}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400">Taller:</label>
                                            <p class="text-gray-100">${data.data.taller}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Fecha y Hora:</label>
                                        <p class="text-gray-100">${data.data.fecha_hora}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Tipo:</label>
                                        <p class="text-gray-100">${data.data.tipo === 'turno_service' ? 'Turno Service' : 'Turno Mecánico'}</p>
                                    </div>
                                    ${data.data.descripcion ? `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Descripción:</label>
                                        <p class="text-gray-100">${data.data.descripcion}</p>
                                    </div>
                                    ` : ''}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Estado:</label>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                            data.data.estado === 'completado' || data.data.estado === 'atendido' 
                                                ? 'bg-green-900/30 text-green-400 border border-green-800'
                                                : data.data.estado === 'cancelado'
                                                ? 'bg-red-900/30 text-red-400 border border-red-800'
                                                : 'bg-yellow-900/30 text-yellow-400 border border-yellow-800'
                                        }">
                                            ${data.data.estado.charAt(0).toUpperCase() + data.data.estado.slice(1)}
                                        </span>
                                    </div>
                                </div>
                            `;

                            if (data.data.estado !== 'cancelado') {
                                actionButtons = `
                                    <button onclick="cancelarTurno(${data.data.id})"
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                        Cancelar Turno
                                    </button>
                                    <button onclick="abrirReprogramarTurno(${data.data.id})"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                        Reprogramar Turno
                                    </button>
                                `;
                            }
                        } else if (data.tipo === 'cambio_equipo') {
                            title.textContent = 'Detalles del Cambio de Equipo';
                            html = `
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400">Vehículo:</label>
                                            <p class="text-gray-100">${data.data.rodado}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400">Taller:</label>
                                            <p class="text-gray-100">${data.data.taller}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Fecha y Hora Estimada:</label>
                                        <p class="text-gray-100">${data.data.fecha_hora_estimada}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Tipo:</label>
                                        <p class="text-gray-100">${data.data.tipo.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                                    </div>
                                    ${data.data.motivo ? `
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Motivo:</label>
                                        <p class="text-gray-100">${data.data.motivo}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            `;
                        } else if (data.tipo === 'pago') {
                            title.textContent = 'Detalles del Pago';
                            html = `
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400">Vehículo:</label>
                                            <p class="text-gray-100">${data.data.rodado}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-400">Tipo:</label>
                                            <p class="text-gray-100">${data.data.tipo.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Fecha de Pago:</label>
                                        <p class="text-gray-100">${data.data.fecha_pago}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Moneda:</label>
                                        <p class="text-gray-100">${data.data.moneda}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Monto:</label>
                                        <p class="text-gray-100">${data.data.moneda === 'USD' ? 'USD$' : '$'}${parseFloat(data.data.monto).toFixed(2)}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400">Estado:</label>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                            data.data.estado === 'pagado' 
                                                ? 'bg-green-900/30 text-green-400 border border-green-800'
                                                : data.data.estado === 'vencido'
                                                ? 'bg-red-900/30 text-red-400 border border-red-800'
                                                : 'bg-yellow-900/30 text-yellow-400 border border-yellow-800'
                                        }">
                                            ${data.data.estado.charAt(0).toUpperCase() + data.data.estado.slice(1)}
                                        </span>
                                    </div>
                                </div>
                            `;

                            actionButtons = `
                                <a href="{{ route('rodados.index') }}#pagos"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    Ver en Pagos
                                </a>
                            `;
                        }

                        content.innerHTML = html;
                        actions.innerHTML = actionButtons;
                    })
                    .catch(error => {
                        console.error('Error al cargar detalles:', error);
                        content.innerHTML = '<p class="text-red-400">Error al cargar los detalles del evento. Por favor, intenta nuevamente.</p>';
                        actions.innerHTML = '<button onclick="closeEventoModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Cerrar</button>';
                    });
            }

            // Función helper para escapar HTML y prevenir XSS
            function escapeHtml(text) {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return String(text).replace(/[&<>"']/g, m => map[m]);
            }

            function closeEventoModal() {
                document.getElementById('evento-modal').classList.add('hidden');
            }

            function cancelarTurno(id) {
                if (!id) {
                    alert('Error: ID de turno inválido.');
                    return;
                }

                if (!confirm('¿Está seguro de cancelar este turno?')) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    alert('Error: Token de seguridad no encontrado. Por favor, recarga la página.');
                    return;
                }

                const url = `{{ route("rodados.turnos.cancelar", ["turno" => ":id"]) }}`.replace(':id', encodeURIComponent(id));

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || `HTTP error! status: ${response.status}`);
                        });
                    }
                    if (response.redirected) {
                        window.location.href = response.url;
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        if (data.success) {
                            alert('Turno cancelado exitosamente.');
                            closeEventoModal();
                            if (calendar) {
                                calendar.refetchEvents();
                            }
                            location.reload();
                        } else if (data.error) {
                            alert('Error: ' + data.error);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al cancelar turno:', error);
                    alert('Error al cancelar el turno: ' + (error.message || 'Error desconocido'));
                });
            }

            function abrirReprogramarTurno(id) {
                if (!id) {
                    alert('Error: ID de turno inválido.');
                    return;
                }

                // Obtener fecha y hora actual para sugerencia
                const now = new Date();
                const defaultDate = now.toISOString().slice(0, 16);
                
                const nuevaFecha = prompt('Ingrese la nueva fecha y hora (formato: YYYY-MM-DDTHH:mm):\nEjemplo: ' + defaultDate, defaultDate);
                if (!nuevaFecha) return;

                // Validar formato básico de fecha
                const fechaRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;
                if (!fechaRegex.test(nuevaFecha)) {
                    alert('Error: Formato de fecha inválido. Use el formato YYYY-MM-DDTHH:mm (ejemplo: 2024-12-25T14:30)');
                    return;
                }

                // Validar que la fecha sea futura
                const fechaInput = new Date(nuevaFecha);
                if (isNaN(fechaInput.getTime())) {
                    alert('Error: Fecha no válida.');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    alert('Error: Token de seguridad no encontrado. Por favor, recarga la página.');
                    return;
                }

                const url = `{{ route("rodados.turnos.reprogramar", ["turno" => ":id"]) }}`.replace(':id', encodeURIComponent(id));

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        fecha_hora: nuevaFecha
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || `HTTP error! status: ${response.status}`);
                        });
                    }
                    if (response.redirected) {
                        window.location.href = response.url;
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        if (data.success) {
                            alert('Turno reprogramado exitosamente.');
                            closeEventoModal();
                            if (calendar) {
                                calendar.refetchEvents();
                            }
                            location.reload();
                        } else if (data.error) {
                            alert('Error: ' + data.error);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al reprogramar turno:', error);
                    alert('Error al reprogramar el turno: ' + (error.message || 'Error desconocido'));
                });
            }
        </script>
    @endpush
</x-app-layout>

