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