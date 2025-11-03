<div>
    <div class="max-w-7xl mx-auto p-6 bg-gray-900 text-gray-100 rounded shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold mb-6">Objetivos SEI</h2>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-800 border border-green-600 text-green-300 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtros --}}
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block mb-1 text-sm">Buscar</label>
                    <input wire:model.live.debounce.300ms="search" type="text" 
                           placeholder="Provincia, localidad, nombre, código..." 
                           class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                </div>

                <div>
                    <label class="block mb-1 text-sm">Provincia</label>
                    <select wire:model.live="pcia" 
                            class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                        <option value="">Todas las provincias</option>
                        @foreach($provincias as $provincia)
                            <option value="{{ $provincia }}">{{ $provincia }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm">Localidad</label>
                    <select wire:model.live="localidad" 
                            class="bg-gray-800 border border-gray-700 rounded px-3 py-2 w-full text-gray-100">
                        <option value="">Todas las localidades</option>
                        @foreach($localidades as $localidadItem)
                            <option value="{{ $localidadItem }}">{{ $localidadItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button wire:click="resetFilters" 
                            class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded h-[42px] w-full transition-colors">
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
                        <th class="px-4 py-2 text-left">Código</th>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Cliente</th>
                        <th class="px-4 py-2 text-left">Fecha Alta</th>
                        <th class="px-4 py-2 text-left">Teléfono</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Provincia</th>
                        <th class="px-4 py-2 text-left">Localidad</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($objetivos as $obj)
                        <tr class="border-b border-gray-700 hover:bg-gray-750 transition-colors">
                            <td class="px-4 py-2 font-mono text-sm">{{ $obj->codobj }}</td>
                            <td class="px-4 py-2">{{ $obj->nombre }}</td>
                            <td class="px-4 py-2 font-mono text-sm">{{ $obj->codcli }}</td>
                            <td class="px-4 py-2">
                                @if($obj->fecha_alta)
                                    {{ \Carbon\Carbon::parse($obj->fecha_alta)->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($obj->telefono)
                                    {{ $obj->telefono }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($obj->email)
                                    <a href="mailto:{{ $obj->email }}" class="text-blue-400 hover:text-blue-300 underline">
                                        {{ $obj->email }}
                                    </a>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($obj->pcia == '06')
                                    NEUQUEN
                                @else
                                    {{ $obj->pcia }}
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $obj->localidad }}</td>
                            <td class="px-4 py-2">
                                <div class="flex space-x-2">
                                    <button wire:click="edit({{ $obj->id }})" 
                                            class="text-blue-400 hover:text-blue-300 transition-colors"
                                            title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $obj->id }})" 
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este objetivo?')"
                                            class="text-red-400 hover:text-red-300 transition-colors"
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
                            <td colspan="9" class="px-4 py-2 text-center text-gray-400">
                                @if($search || $pcia || $localidad)
                                    No hay objetivos que coincidan con los filtros aplicados.
                                @else
                                    No hay objetivos registrados.
                                @endif
                            </td>
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