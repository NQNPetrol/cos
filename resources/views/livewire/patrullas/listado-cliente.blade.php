<div>
    <!-- Contenedor 1: Título -->
    <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
        <h2 class="text-2xl font-bold text-gray-100">Flotas Vehiculares</h2>
    </div>

    <!-- Contenedor 2: Filtros -->
    <div class="bg-[#252728] rounded-lg p-6 mb-6 border border-transparent">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-base font-semibold mb-1 text-gray-200">Buscar</label>
                <input type="text" wire:model.live="search"
                       placeholder="Patente, marca, modelo..."
                       class="w-full bg-transparent border-gray-300 rounded px-3 py-2 text-gray-200">
            </div>
            
            <div>
                <label class="block text-base font-semibold mb-1 text-gray-200">Estado</label>
                <select wire:model.live="estadoFilter" 
                        class="w-full bg-transparent border-gray-300 rounded px-3 py-2 text-gray-200">
                    <option value="">Todos</option>
                    <option value="operativa">Operativa</option>
                    <option value="disponible">Disponible</option>
                    <option value="en mantenimiento">En Mantenimiento</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Contenedor 3: Listado/Tabla -->
    <div class="bg-[#252728] rounded-lg p-6 border border-transparent">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-[#1a1d1f] text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">DOMINIO</th>
                    <th class="px-4 py-2 text-left">MARCA</th>
                    <th class="px-4 py-2 text-left">MODELO</th>
                    <th class="px-4 py-2 text-left">AÑO</th>
                    <th class="px-4 py-2 text-left">ESTADO</th>
                    <th class="px-4 py-2 text-left">OBJETIVO/SERVICIO</th>
                    <th class="px-4 py-2 text-left">OBSERVACION</th>
                    <th class="px-4 py-2 text-left"> </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patrullas as $patrulla)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-4 py-2 font-medium text-gray-300">{{ $patrulla->patente }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ $patrulla->marca }}</td>
                        <td class="px-4 py-2 text-gray-300">{{ $patrulla->modelo }}</td>   
                        <td class="px-4 py-2 text-gray-300">{{ $patrulla->año ?? 'N/A'}}</td>
                        <td class="px-4 py-2">
                            @if ($editingEstadoId === $patrulla->id)
                                <!-- Modo edición -->
                                <div class="flex items-center space-x-2">
                                    <select wire:model="nuevoEstado" 
                                            class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-gray-200">
                                        <option value="operativa">Operativa</option>
                                        <option value="disponible">Disponible</option>
                                        <option value="en mantenimiento">En mantenimiento</option>
                                    </select>
                                    <button wire:click="guardarEstado({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                        Guardar
                                    </button>
                                    <button wire:click="cancelarEdicion"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            @else
                                <!-- Modo visualización -->
                                <div class="flex items-center gap-1">
                                    <span class="font-semibold {{ 
                                        $patrulla->estado === 'operativa' ? 'text-green-600' : 
                                        ($patrulla->estado === 'disponible' ? 'text-blue-600' : 
                                        'text-orange-600')
                                    }}">
                                        {{ ucfirst($patrulla->estado) }}
                                    </span>
                                    <button wire:click="iniciarEdicionEstado({{ $patrulla->id }}, '{{ $patrulla->estado }}')"
                                            class="text-gray-500 hover:text-gray-700 transition-colors"
                                            title="Editar estado">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($editingObjetivoId === $patrulla->id)
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           wire:model="nuevoObjetivo"
                                           class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-gray-200 w-32"
                                           placeholder="Objetivo/Servicio">
                                    <button wire:click="guardarObjetivo({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                        Guardar
                                    </button>
                                    <button wire:click="cancelarEdicionObjetivo"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="max-w-xs truncate text-gray-300">
                                        {{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? 'N/A' }}
                                    </span>
                                    <button wire:click="iniciarEdicionObjetivo({{ $patrulla->id }}, '{{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? '' }}')"
                                            class="text-gray-500 hover:text-gray-700 transition-colors flex-shrink-0"
                                            title="Editar objetivo/servicio">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($editingObservacionId === $patrulla->id)
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           wire:model="nuevaObservacion"
                                           class="bg-gray-800 border border-gray-600 rounded px-2 py-1 text-sm text-gray-200 w-32"
                                           placeholder="Observación">
                                    <button wire:click="guardarObservacion({{ $patrulla->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                        Guardar
                                    </button>
                                    <button wire:click="cancelarEdicionObservacion"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs">
                                        Cancelar
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    <span class="max-w-xs truncate text-gray-300">
                                        {{ $patrulla->ultimoRegistroFlota->observacion ?? 'N/A' }}
                                    </span>
                                    <button wire:click="iniciarEdicionObservacion({{ $patrulla->id }}, '{{ $patrulla->ultimoRegistroFlota->observacion ?? '' }}')"
                                            class="text-gray-500 hover:text-gray-700 transition-colors flex-shrink-0"
                                            title="Editar observación">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </td>

                       
                        <td class="px-4 py-2">
                            <div class="flex space-x-6">
                                <button wire:click="abrirModal({{ $patrulla->id }})" 
                                        class="action-button relative"
                                        data-tooltip="Documentación">
                                    <div class="w-9 h-9 rounded-full bg-[#1877f2] flex items-center justify-center transition-all hover:bg-[#0866ff]">
                                        <i class="bi bi-file-earmark-text text-white text-lg" style="font-size: 20px; font-weight: 600;"></i>
                                    </div>
                                </button>
                                <a href="{{ route('client.patrullas.location') }}" 
                                   class="action-button relative"
                                   data-tooltip="Ubicación">
                                    <div class="w-9 h-9 rounded-full bg-[#1877f2] flex items-center justify-center transition-all hover:bg-[#0866ff]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z"/>
                                            <path fill-rule="evenodd" d="M8 1a3 3 0 1 0 0 6 3 3 0 0 0 0-6M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center px-4 py-8 text-gray-300">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <p>No se encontraron patrullas</p>
                                <p class="text-sm">Intenta ajustar los filtros o agrega una nueva patrulla</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $patrullas->links() }}
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <!-- Modal -->
    @if($mostrarModal && $patrullaSeleccionada)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50">
            <div class="bg-[#252728] rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
                <!-- Header del Modal -->
                <div class="bg-[#1a1d1f] px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-200">
                            PATRULLA {{ $patrullaSeleccionada->patente }}
                        </h2>
                        <button wire:click="cerrarModal" 
                                class="text-gray-400 hover:text-gray-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-300 mt-1">
                        {{ $patrullaSeleccionada->marca }} {{ $patrullaSeleccionada->modelo }} - {{ $patrullaSeleccionada->año }}
                    </p>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)] custom-scrollbar">
                    <div class="mb-6">
                        @livewire('flotas-vehiculares.sistema-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                    </div>

                    <!-- Documental -->
                    <div>
                        @livewire('flotas-vehiculares.documentacion-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                    </div>
                </div>
            </div>
        </div>

        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 10px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #252728;
                border-radius: 4px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #3a3b3c;
                border-radius: 4px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #4a4b4c;
            }
            
            /* Para Firefox */
            .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: #3a3b3c #252728;
            }
        </style>
    @endif

    <!-- Estilos para botones de acción y scrollbar -->
    <style>
        .action-button {
            position: relative;
            display: inline-block;
        }

        /* Tooltip para botones de acción */
        .action-button[data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 8px;
            padding: 6px 10px;
            background-color: #d1d5db;
            color: #1c1e21;
            font-size: 13px;
            font-weight: 400;
            white-space: nowrap;
            border-radius: 8px;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.1s ease 0.05s, visibility 0.1s ease 0.05s;
            z-index: 10000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .action-button[data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Flecha del tooltip (apunta hacia abajo) */
        .action-button[data-tooltip]::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 2px;
            border: 5px solid transparent;
            border-top-color: #d1d5db;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.1s ease 0.05s, visibility 0.1s ease 0.05s;
            z-index: 10001;
        }

        .action-button[data-tooltip]:hover::before {
            opacity: 1;
            visibility: visible;
        }

        /* Scrollbar personalizado para la tabla */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #252728;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #3a3b3c;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #4a4b4c;
        }

        /* Para Firefox */
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: #3a3b3c #252728;
        }

        /* Línea más fina y hover para filas de tabla */
        .table-row-hover {
            border-bottom: 0.1px solid #e5e7eb !important;
        }

        .table-row-hover:hover {
            background-color: #1f2937 !important;
        }

        .table-row-hover:hover td {
            color: #e5e7eb !important;
        }

        /* Estilos para filas de tablas en el modal */
        .table-row-hover-modal {
            border-bottom: 0.1px solid #e5e7eb !important;
        }

        .table-row-hover-modal:hover {
            background-color: #1f2937 !important;
        }

        .table-row-hover-modal:hover td {
            color: #e5e7eb !important;
        }

        /* Estilos para opciones de dropdowns en el modal */
        .modal-select option {
            background-color:rgb(195, 201, 211);
            color:rgb(22, 27, 35);
        }
    </style>
</div>