<!-- Modal para registrar kilometraje -->
<div id="kilometraje-modal" class="hidden fixed inset-0 bg-zinc-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-zinc-800 border-zinc-700">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-100">Registrar Kilometraje</h3>
                <button onclick="closeKilometrajeModal()"
                    class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="kilometraje-form" method="POST" action="{{ route('rodados.kilometraje.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Vehículo *</label>
                        <select id="kilometraje-rodado" name="rodado_id" required
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                            <option value="">Seleccione un vehículo</option>
                            @foreach($rodados as $rodado)
                                <option value="{{ $rodado->id }}">
                                    {{ $rodado->patente ?? 'Sin patente' }}
                                    @if($rodado->kilometrajeActual)
                                        (Actual: {{ number_format($rodado->kilometrajeActual->kilometraje, 0, ',', '.') }} km)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Kilometraje *</label>
                        <input type="number" id="kilometraje-valor" name="kilometraje" required min="0"
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            placeholder="Ingrese el kilometraje">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Fecha de Registro *</label>
                        <input type="date" id="kilometraje-fecha" name="fecha_registro" required
                            value="{{ date('Y-m-d') }}"
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Observaciones</label>
                        <textarea id="kilometraje-observaciones" name="observaciones" rows="3"
                            class="w-full rounded-md bg-zinc-700 border-zinc-600 text-white px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-500"
                            placeholder="Observaciones adicionales (opcional)"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeKilometrajeModal()"
                        class="px-4 py-2 bg-zinc-700 text-gray-300 rounded-md hover:bg-zinc-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function closeKilometrajeModal() {
        document.getElementById('kilometraje-modal').classList.add('hidden');
        document.getElementById('kilometraje-form').reset();
    }

    function openKilometrajeModal(rodadoId = null) {
        if (rodadoId) {
            document.getElementById('kilometraje-rodado').value = rodadoId;
        }
        document.getElementById('kilometraje-modal').classList.remove('hidden');
    }
</script>

