<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Inventario</h2>
        <button wire:click="openModal" 
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Dispositivo
        </button>
    </div>

    <!-- Filtros -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm mb-1">Buscar</label>
            <input type="text"
                   wire:model.live="search"
                   placeholder="Tipo, Serial, IP, Ubicación, Cliente..."
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
        </div>
        <!-- Filtro cliente -->
        <div>
            <label class="block text-sm mb-1">Cliente</label>
            <select wire:model.live="clienteFilter" 
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos los clientes</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                @endforeach
            </select>
        </div>
        <!-- Filtro inventario -->
        <div>
            <label class="block text-sm mb-1">Estado Inventario</label>
            <select wire:model.live="estadoInventarioFilter" 
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                <option value="En stock">En stock</option>
                <option value="Instalado">Instalado</option>
                <option value="En mantenimiento">En mantenimiento</option>
                <option value="Dado de baja">Dado de baja</option>
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Conectado a Hik</label>
            <select wire:model.live="hikConnectFilter"
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                <option value="Conectado">Conectado</option>
                <option value="Por Conectar">Por Conectar</option>
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Necesita Mantenimiento</label>
            <select wire:model.live="mantenimientoFilter" 
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Necesita Actualización</label>
            <select wire:model.live="actualizacionFilter" 
                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                <option value="">Todos</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>

        <div class="flex items-end">
            <button wire:click="clearFilters" 
                    class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded text-white">
                Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">IP</th>
                    <th class="px-4 py-2 text-left">N° Serie</th>
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2 text-left">Ubicación</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Conectado a Hik</th>
                    <th class="px-4 py-2 text-left">Mantenimiento</th>
                    <th class="px-4 py-2 text-left">Observaciones</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dispositivos as $dispositivo)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2">
                            <div class="font-medium">{{ $dispositivo->tipo }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $dispositivo->direccion_ip ?: 'N/A' }}</div>
                            @if($dispositivo->puerto && $dispositivo->puerto != '8000')
                                <div class="text-xs text-gray-400">:{{ $dispositivo->puerto }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $dispositivo->numero_serie ?: 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $dispositivo->cliente->nombre ?? 'Sin asignar' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $dispositivo->ubicacion ?: 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $dispositivo->estado_inventario_badge }}">
                                {{ $dispositivo->estado_inventario }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($dispositivo->estado_hikconnect == 'Conectado')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $dispositivo->estado_hikconnect }}
                                </span>
                            @elseif($dispositivo->estado_hikconnect == 'Por Conectar')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $dispositivo->estado_hikconnect }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($dispositivo->necesita_mantenimiento)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Requerido
                                </span>
                                @if($dispositivo->proximo_mantenimiento)
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $dispositivo->proximo_mantenimiento->format('d/m/Y') }}
                                    </div>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    OK
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm"> {{ $dispositivo->observaciones ?: 'Sin observaciones' }}
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button wire:click="edit({{ $dispositivo->id }})" 
                                        class="text-blue-400 hover:text-blue-300"
                                        title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $dispositivo->id }})" 
                                        onclick="return confirm('¿Está seguro de que desea eliminar este dispositivo?')"
                                        class="text-red-400 hover:text-red-300"
                                        title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center px-4 py-8 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p>No se encontraron dispositivos</p>
                                <p class="text-sm">Intenta ajustar los filtros o agrega un nuevo dispositivo</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $dispositivos->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div wire:click.self="closeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-6xl max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-100">
                        {{ $editingId ? 'Editar Dispositivo' : 'Nuevo Dispositivo' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        
                        <!-- Información Básica -->
                        <div class="col-span-full">
                            <h4 class="text-lg font-semibold text-gray-200 mb-3 border-b border-gray-700 pb-2">
                                Información Básica
                            </h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 col-span-full">
                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Tipo de Dispositivo <span class="text-red-500">*</span></label>
                                <select wire:model="tipo" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                    <option value="">Seleccionar...</option>
                                    <option value="cámara_ip">Cámara IP</option>
                                    <option value="nvr_dvr">NVR/DVR</option>
                                    <option value="control_acceso">Control de Acceso</option>
                                    <option value="intercomunicador">Intercomunicador</option>
                                    <option value="switch_poe">Switch PoE</option>
                                    <option value="sensor_alarma">Sensor de Alarma</option>
                                    <option value="dispositivo_reconocimiento">Reconocimiento Facial</option>
                                    <option value="gps">GPS</option>
                                    <option value="otros">Otros</option>
                                </select>
                                @error('tipo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Fecha de Instalación</label>
                                <input type="date" wire:model="fecha_instalacion"
                                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                @error('fecha_instalacion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Cliente</label>
                                <select wire:model="cliente_id" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                    <option value="">Sin asignar</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Ubicación</label>
                                <input type="text" wire:model="ubicacion"
                                    class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                    placeholder="Oficina Principal - Recepción">
                                @error('ubicacion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm mb-1 text-gray-300">Observaciones</label>
                                <textarea wire:model="observaciones" rows="3"
                                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                        placeholder="Notas adicionales sobre el dispositivo..."></textarea>
                            </div>
                        </div>


                        <!-- Configuración de Red -->
                        <div class="col-span-full">
                            <h4 class="text-lg font-semibold text-gray-200 mb-3 border-b border-gray-700 pb-2 mt-4">
                                Configuración de Red
                            </h4>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Dirección IPv4</label>
                            <input type="text" wire:model="direccion_ip"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                   placeholder="192.168.0.61">
                            @error('direccion_ip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Puerto</label>
                            <input type="text" wire:model="puerto"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                   placeholder="8000">
                            @error('puerto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Número de Serie</label>
                            <input type="text" wire:model="numero_serie"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                   placeholder="ABC123456789">
                            @error('numero_serie') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>


                        <!-- Configuración Adicional -->
                        <div class="col-span-full">
                            <h4 class="text-lg font-semibold text-gray-200 mb-3 border-b border-gray-700 pb-2 mt-4">
                                Estado y Mantenimiento
                            </h4>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Versión de Software</label>
                            <input type="text" wire:model="version_software"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                   placeholder="V1.2.0build 210616">
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Estado Hik-Connect</label>
                            <select wire:model="estado_hikconnect" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                <option value="Conectado">Conectado</option>
                                <option value="Por Conectar">Por Conectar</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="necesita_actualizacion" id="necesita_actualizacion"
                                   class="bg-gray-800 border-gray-700 rounded">
                            <label for="necesita_actualizacion" class="text-sm text-gray-300">Necesita Actualización</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="necesita_mantenimiento" id="necesita_mantenimiento"
                                   class="bg-gray-800 border-gray-700 rounded">
                            <label for="necesita_mantenimiento" class="text-sm text-gray-300">Necesita Mantenimiento</label>
                        </div>
                         <div>
                            <label class="block text-sm mb-1 text-gray-300">Último Mantenimiento</label>
                            <input type="date" wire:model="ultimo_mantenimiento"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('ultimo_mantenimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Próximo Mantenimiento</label>
                            <input type="date" wire:model="proximo_mantenimiento"
                                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            @error('proximo_mantenimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Información de Inventario -->
                        <div class="col-span-full">
                            <h4 class="text-lg font-semibold text-gray-200 mb-3 border-b border-gray-700 pb-2 mt-4">
                                Información de Inventario
                            </h4>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-gray-300">Estado de Inventario <span class="text-red-500">*</span></label>
                            <select wire:model="estado_inventario" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                <option value="En stock">En stock</option>
                                <option value="Instalado">Instalado</option>
                                <option value="En mantenimiento">En mantenimiento</option>
                                <option value="Dado de baja">Dado de baja</option>
                            </select>
                            @error('estado_inventario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    
                        
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-gray-300 hover:text-gray-100">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded text-white font-medium">
                            {{ $editingId ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
</div>