<div>
    <div class="max-w-7xl mx-auto p-6 bg-gray-900 text-gray-100 rounded shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold mb-6">Administrar Objetivos</h2>
            <buttton wire:click="openModal"
                     class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Objetivo
            </button>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-800 border border-green-600 text-green-300 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtros --}}
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4"">
                    <div>
                        <label class="block mb-1 text-sm">Buscar</label>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nombre, localidad..." 
                            class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Cliente</label>
                        <select wire:model.live="cliente_id" class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                            <option value="">Todos</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Contrato</label>
                        <select wire:model.live="contrato_id" class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                            <option value="">Todos</option>
                            @foreach ($contratos as $contrato)
                                <option value="{{ $contrato->id }}">{{ $contrato->nombre_proyecto }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="resetFilters" 
                                class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded h-[42px] w-full">
                            Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-800 border border-gray-700 rounded">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Ubicación</th>
                            <th class="px-4 py-2 text-left">Cliente</th>
                            <th class="px-4 py-2 text-left">Empresa Asociada</th>
                            <th class="px-4 py-2 text-left">Contrato</th>
                            <th class="px-4 py-2 text-left">Localidad</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($objetivos as $obj)
                            <tr class="border-b border-gray-700">
                                <td class="px-4 py-2"> {{ $obj->nombre }}</td>
                                <td class="pl-8 py-2 text-center gap-2">
                                    <a href="https://www.google.com/maps?q={{ $obj->latitud }},{{ $obj->longitud }}"
                                    target="_blank"
                                    class="text-green-400 hover:text-green-300"
                                    title="Ver en Google Maps">
                                        <!-- Ícono de ubicación / mapa -->
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 20l-4.95-6.05a7 7 0 010-9.9zM10 11a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $obj->cliente->nombre ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-center">{{ $obj->empresaAsociada->nombre ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $obj->contrato->nombre_proyecto ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $obj->localidad }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex space-x-2">
                                        <button wire:click="edit({{ $obj->id }})" 
                                                class="text-blue-400 hover:text-blue-300"
                                                title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button onclick="confirm('¿Eliminar este objetivo?') ? @this.delete({{ $obj->id }}) : false"
                                                class="text-red-400 hover:text-red-300"
                                                title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-400">No hay objetivos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $objetivos->links('pagination::tailwind') }}
            </div>
        </div>
        <!-- Modal para formulario -->
        @if($showModal)
            <div wire:click.self="closeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-gray-900 rounded-lg p-6 w-full max-w-2xl" @click.stop>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-100">
                            {{ $editingId ? 'Editar Objetivo' : 'Nuevo Objetivo' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="{{ $editingId ? 'update' : 'save' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="form.nombre" 
                                       class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                @error('form.nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Seleccione un Cliente <span class="text-red-500">*</span></label>
                                <select wire:model="form.cliente_id" wire:change="cargarEmpresas($event.target.value)"
                                       class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('form.cliente_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Empresa Asociada al cliente <span class="text-red-500">*</span></label>
                                <select wire:model="form.empresa_asociada_id"
                                        wire:change="cargarContratos($event.target.value)"
                                        class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                        @if(!$form['cliente_id']) disabled @endif>
                                    <option value="">Seleccione una empresa asociada</option>
                                    @foreach ($empresasFiltradas as $empresa)
                                        <option value="{{ $empresa->id }}"
                                                @if($empresa->id == $form['empresa_asociada_id']) selected @endif>
                                            {{ $empresa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('form.empresa_asociada_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Contrato <span class="text-red-500">*</span></label>
                                <select wire:model="form.contrato_id"
                                       class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200"
                                       @if(!$form['empresa_asociada_id']) disabled @endif>
                                    <option value="">Seleccione un contrato</option>
                                    @foreach ($contratosFiltrados as $contrato)
                                        <option value="{{ $contrato->id }}"
                                            @if($contrato->id == $form['contrato_id']) selected @endif>
                                        {{ $contrato->nombre_proyecto }}</option>
                                    @endforeach
                                </select>
                                @error('form.contrato_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Latitud <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="form.latitud" 
                                       class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                @error('form.latitud') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Longitud <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="form.longitud" 
                                       class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                @error('form.longitud') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-300">Localidad</label>
                                <input type="text" wire:model.defer="form.localidad" 
                                       class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                                @error('form.localidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

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
    </div>
</div>
