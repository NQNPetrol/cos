<!-- Modal para revisar y aprobar/rechazar cobertura -->
<div id="revisar-cobertura-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-md bg-gray-800 border-gray-700 max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Revisar Cobertura de Servicio</h3>
                <button onclick="closeRevisarCoberturaModal()"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="revisar-cobertura-content" class="space-y-4">
                <!-- El contenido se cargará dinámicamente -->
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeRevisarCoberturaModal()"
                    class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition">
                    Cerrar
                </button>
                <button type="button" onclick="aprobarCobertura()" id="btn-aprobar-cobertura"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Aprobar cobertura del servicio
                </button>
                <button type="button" onclick="rechazarCobertura()" id="btn-rechazar-cobertura"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    Rechazar cobertura del servicio
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentTurnoId = null;

    function openRevisarCoberturaModal(turnoId) {
        currentTurnoId = turnoId;
        const modal = document.getElementById('revisar-cobertura-modal');
        const content = document.getElementById('revisar-cobertura-content');
        
        // Cargar detalles del turno via AJAX
        fetch(`{{ route('rodados.index') }}`)
            .then(response => response.text())
            .then(html => {
                // Aquí se cargarían los detalles del turno
                // Por ahora mostramos un mensaje de carga
                content.innerHTML = '<p class="text-gray-300">Cargando detalles del servicio...</p>';
                modal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<p class="text-red-400">Error al cargar los detalles del servicio.</p>';
            });
    }

    function closeRevisarCoberturaModal() {
        document.getElementById('revisar-cobertura-modal').classList.add('hidden');
        currentTurnoId = null;
    }

    function aprobarCobertura() {
        if (!currentTurnoId) return;
        
        fetch(`{{ route('rodados.turnos.aprobar-cobertura', ':id') }}`.replace(':id', currentTurnoId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                alert('Cobertura aprobada exitosamente.');
                closeRevisarCoberturaModal();
                location.reload();
            } else if (data && data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al aprobar la cobertura.');
        });
    }

    function rechazarCobertura() {
        if (!currentTurnoId) return;
        
        fetch(`{{ route('rodados.turnos.rechazar-cobertura', ':id') }}`.replace(':id', currentTurnoId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                alert('Cobertura rechazada exitosamente.');
                closeRevisarCoberturaModal();
                location.reload();
            } else if (data && data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al rechazar la cobertura.');
        });
    }
</script>

