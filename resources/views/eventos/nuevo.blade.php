<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Registrar Nuevo Evento</h2>
                        <a href="{{ route('eventos.index') }}" class="text-blue-400 hover:text-blue-300 flex items-center">
                            <i class="bi bi-arrow-left mr-2"></i> Volver al listado
                        </a>
                    </div>

                    <form action="{{ route('eventos.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Sección 1: Categoría del Evento -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">1. ¿A qué categoría pertenece el evento?</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach([
                                    'Seguridad física',
                                    'Incidentes con el personal',
                                    'Seguridad vial y patrullaje',
                                    'Tecnológicos/comunicación',
                                    'Entorno y contexto',
                                    'Administrativos/reportables al cliente'
                                ] as $categoria)
                                <div class="flex items-center">
                                    <input id="categoria-{{ Str::slug($categoria) }}" name="categoria" type="radio" value="{{ $categoria }}" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600" required
                                           @if(old('categoria') === $categoria) checked @endif>
                                    <label for="categoria-{{ Str::slug($categoria) }}" class="ml-3 block text-sm font-medium text-gray-300">
                                        {{ $categoria }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('categoria')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 2: Tipo de Evento (dinámico) -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">2. ¿A qué tipo pertenece el evento?</h3>
                            <div id="tipos-container">
                                <!-- Opciones se cargarán dinámicamente con JavaScript -->
                                <p class="text-gray-400 italic">Seleccione primero una categoría</p>
                            </div>
                            @error('tipo')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 3: Fecha y Hora -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">3. Fecha y hora del incidente</h3>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora"
                                   class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ old('fecha_hora') }}" required>
                            @error('fecha_hora')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 4: Coordenadas -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">4. Ubicación (coordenadas)</h3>
                            <div class="mb-4 bg-gray-800 p-3 rounded-md">
                                <p class="text-sm text-gray-300 mb-2">Cómo obtener las coordenadas:</p>
                                <ol class="list-decimal list-inside text-sm text-gray-400 space-y-1">
                                    <li>Entrar a <a href="https://www.google.com/maps" target="_blank" class="text-blue-400 hover:underline">Google Maps</a></li>
                                    <li>Ubicar el punto deseado en el mapa</li>
                                    <li>Apretar click derecho sobre el lugar exacto</li>
                                    <li>Seleccionar la primera opción (copiar coordenadas)</li>
                                    <li>Pegarlas en el campo debajo</li>
                                </ol>
                                <p class="text-sm text-yellow-400 mt-2">Ejemplo: -38.88266056726054, -68.0446703200311</p>
                            </div>
                            <input type="text" name="coordenadas" id="coordenadas" placeholder="Pegue las coordenadas aquí"
                                   class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ old('coordenadas') }}" required
                                   pattern="^-?\d{1,3}\.\d+,\s-?\d{1,3}\.\d+$"
                                   title="Formato requerido: latitud, longitud (ejemplo: -38.88266056726054, -68.0446703200311)">
                            @error('coordenadas')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 5: Cliente -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">5. Cliente</h3>
                            <select name="cliente_id" id="cliente_id" required
                                    class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @if(old('cliente_id') == $cliente->id) selected @endif>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 6: Supervisor -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">6. Supervisor</h3>
                            <select name="supervisor_id" id="supervisor_id" required
                                    class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">
                                <option value="">Seleccione un supervisor</option>
                                @foreach($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}" @if(old('supervisor_id') == $supervisor->id) selected @endif>
                                        {{ $supervisor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 7: Observaciones -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">7. Observaciones (opcional)</h3>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                      class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2">{{ old('observaciones') }}</textarea>
                        </div>

                        <!-- Sección 8: Link del Reporte -->
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">8. Link del reporte (opcional)</h3>
                            <input type="url" name="reporte_url" id="reporte_url" placeholder="https://drive.google.com/..."
                                   class="mt-1 block w-full rounded-md bg-gray-600 border-gray-500 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                   value="{{ old('reporte_url') }}">
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="reset" class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600">
                                Limpiar formulario
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="bi bi-save mr-2"></i> Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    // Mapeo de categorías a tipos
    const tiposPorCategoria = {
        'Seguridad física': [
            'Robo o intento de robo',
            'Intrusión o acceso no autorizado',
            'Sabotaje o vandalismo',
            'Daños a instalaciones o equipos',
            'Hallazgo de objetos sospechosos'
        ],
        'Incidentes con el personal': [
            'Ausencias o abandono de puesto',
            'Conflictos entre vigiladores',
            'Lesiones o accidentes laborales',
            'Mal uso del uniforme o equipamiento',
            'Incumplimiento de procedimientos'
        ],
        'Seguridad vial y patrullaje': [
            'Accidente vehicular (leve, moderado o grave)',
            'Retención de unidad por autoridad',
            'Falla mecánica en ruta',
            'Vehículo fuera de recorrido',
            'Velocidad inadecuada o uso indebido del móvil'
        ],
        'Tecnológicos/comunicación': [
            'Falla en cámaras o GPS',
            'Cortes de conectividad (Starlink, datos, etc.)',
            'Fallos en sistemas de ronda digital o QR',
            'Problemas con radios o teléfonos',
            'Errores en reportes digitales'
        ],
        'Entorno y contexto': [
            'Cortes de ruta o piquetes',
            'Condiciones climáticas extremas',
            'Riesgos naturales (viento Zonda, tormentas, etc.)',
            'Manifestaciones o bloqueos externos',
            'Animales peligrosos (en zonas rurales)'
        ],
        'Administrativos/reportables al cliente': [
            'Demoras en relevos',
            'Incumplimiento del servicio contratado',
            'Problemas de cobertura',
            'Quejas del cliente',
            'Notificaciones a ART / aseguradoras'
        ]
    };

    // Función para cargar tipos basados en categoría seleccionada
    function cargarTipos(categoria) {
        const tiposContainer = document.getElementById('tipos-container');
        tiposContainer.innerHTML = '';
        
        const tipos = tiposPorCategoria[categoria];
        tipos.forEach(tipo => {
            const div = document.createElement('div');
            div.className = 'flex items-center mb-2';
            
            const input = document.createElement('input');
            input.type = 'radio';
            input.id = `tipo-${tipo.replace(/\s+/g, '-').toLowerCase()}`;
            input.name = 'tipo';
            input.value = tipo;
            input.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600';
            input.required = true;
            
            const label = document.createElement('label');
            label.htmlFor = input.id;
            label.className = 'ml-3 block text-sm font-medium text-gray-300';
            label.textContent = tipo;
            
            div.appendChild(input);
            div.appendChild(label);
            tiposContainer.appendChild(div);
        });
    }

    // Escuchar cambios en la selección de categoría
    document.querySelectorAll('input[name="categoria"]').forEach(radio => {
        radio.addEventListener('change', function() {
            cargarTipos(this.value);
        });
    });

    // Inicializar el formulario si hay valores antiguos
    document.addEventListener('DOMContentLoaded', function() {
        const categoriaSeleccionada = document.querySelector('input[name="categoria"]:checked');
        const tipoSeleccionado = "{{ old('tipo') }}";
        
        if (categoriaSeleccionada) {
            cargarTipos(categoriaSeleccionada.value);
            
            // Esperar un momento para que se carguen los tipos antes de seleccionar
            setTimeout(() => {
                if (tipoSeleccionado) {
                    const tipoInput = document.querySelector(`input[name="tipo"][value="${tipoSeleccionado}"]`);
                    if (tipoInput) {
                        tipoInput.checked = true;
                    }
                }
            }, 100);
        }
    });
</script>
@endpush
</x-app-layout>