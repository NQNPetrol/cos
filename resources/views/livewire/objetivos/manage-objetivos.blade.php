<div>
    <div class="max-w-7xl mx-auto p-6 bg-gray-900 text-gray-100 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Administrar Objetivos</h2>

        @if (session()->has('success'))
            <div class="bg-green-800 border border-green-600 text-green-300 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Buscador --}}
        <div class="mb-6 flex items-center gap-4">
            <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar objetivos..." class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-100">
            <button wire:click="resetForm" class="bg-gray-700 hover:bg-gray-600 text-gray-100 px-4 py-2 rounded">Limpiar</button>
        </div>

        {{-- Formulario --}}
        <form wire:submit.prevent="{{ $editingId ? 'update' : 'save' }}" class="bg-gray-800 border border-gray-700 p-4 rounded mb-6">
            <h3 class="text-lg font-semibold mb-4">{{ $editingId ? 'Editar Objetivo' : 'Nuevo Objetivo' }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm">Nombre</label>
                    <input type="text" wire:model.defer="nombre" class="bg-gray-900 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                    @error('nombre') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm">Cliente</label>
                    <select wire:model="cliente_id" wire:change="selectClient($event.target.value)"  class="bg-gray-900 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm">Contrato</label>
                    <select wire:model="contrato_id" class="bg-gray-900 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                        <option value="">Seleccione un contrato</option>
                        @foreach ($contratos as $contrato)
                            <option value="{{ $contrato->id }}">{{ $contrato->nombre_proyecto }}</option>
                        @endforeach
                    </select>
                    @error('contrato_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm">Latitud</label>
                    <input type="text" wire:model.defer="latitud" class="bg-gray-900 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                    @error('latitud') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm">Longitud</label>
                    <input type="text" wire:model.defer="longitud" class="bg-gray-900 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                    @error('longitud') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm">Localidad</label>
                    <input type="text" wire:model.defer="localidad" class="bg-gray-900 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                    @error('localidad') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    {{ $editingId ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>
        </form>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 border border-gray-700 rounded">
                <thead class="bg-gray-700 text-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Ubicación</th>
                        <th class="px-4 py-2 text-left">Cliente</th>
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
                            <td class="px-4 py-2">{{ $obj->contrato->nombre_proyecto ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $obj->localidad }}</td>
                            <td class="px-4 py-2 flex gap-2">
                                <button wire:click="edit({{ $obj->id }})" class="bg-yellow-600 hover:bg-yellow-500 text-white px-2 py-1 rounded text-sm">Editar</button>
                                <button wire:click="delete({{ $obj->id }})" class="bg-red-700 hover:bg-red-600 text-white px-2 py-1 rounded text-sm" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
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

</div>
