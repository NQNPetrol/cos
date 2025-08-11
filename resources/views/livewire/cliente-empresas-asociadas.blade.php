<div class="bg-gray-900 text-gray-100 p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">
            Empresas Asociadas a {{ $cliente->nombre }}
            <a href="{{ route('clientes.index') }}" 
               class="text-blue-400 text-sm ml-2 hover:underline">
               ← Volver al listado de clientes
            </a>
        </h2>
        <button wire:click="openModal"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white font-medium">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Asociar Empresa
        </button>
    </div>

    <!-- Información del cliente -->
    <div class="bg-gray-800 p-4 rounded mb-6">
        <h3 class="text-lg font-medium mb-2">Información del Cliente</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><strong>CUIT:</strong> {{ $cliente->cuit ?? 'N/A' }}</div>
            <div><strong>Domicilio:</strong> {{ $cliente->domicilio ?? 'N/A' }}</div>
            <div><strong>Ciudad:</strong> {{ $cliente->ciudad ?? 'N/A' }}</div>
            <div><strong>Provincia:</strong> {{ $cliente->provincia ?? 'N/A' }}</div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-600 text-white p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-600 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Filtros -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm mb-1">Buscar empresa</label>
            <input type="text"
                   wire:model.live="search"
                   placeholder="Nombre de empresa..."
                   class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
        </div>
        <div class="flex items-end">
            <button wire:click="clearFilters" 
                    class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded text-white">
                Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Tabla contenido -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-4 py-2 text-left">Nombre de la Empresa</th>
                    <th class="px-4 py-2 text-left">Fecha de Asociación</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($empresasAsociadas as $empresa)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $empresa->nombre }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm">{{ $empresa->pivot->created_at?->format('d/m/Y') ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <button wire:click="desasociarEmpresa({{ $empresa->id }})"
                                    onclick="return confirm('¿Está seguro de que desea desasociar esta empresa del cliente? La empresa no será eliminada, solo se quitará la asociación.')"
                                    class="text-red-400 hover:text-red-300"
                                    title="Desasociar empresa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14L21 3m0 0l-6.5 6.5M21 3l-3.5 3.5M13 7l6-6m-6 6v6.5m0 0V21l3.5-3.5M13 13.5L16.5 10" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center px-4 py-8 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p>No hay empresas asociadas a este cliente</p>
                                <p class="text-sm">Utiliza el botón "Asociar Empresa" para agregar empresas existentes</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $empresasAsociadas->links() }}

    <!-- Modal para asociar empresa -->
    @if($showModal)
        <div wire:click.self="closeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-md" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-100">Asociar Empresa</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="asociarEmpresa">
                    <div class="mb-4">
                        <label class="block text-sm mb-1 text-gray-300">Seleccionar Empresa <span class="text-red-500">*</span></label>
                        <select wire:model="empresaSeleccionada" class="w-full bg-gray-800 border-gray-700 rounded px-3 py-2 text-gray-200">
                            <option value="">Selecciona una empresa...</option>
                            @foreach($empresasDisponibles as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                        @error('empresaSeleccionada') 
                            <span class="text-red-500 text-xs">{{ $message }}</span> 
                        @enderror
                    </div>

                    @if($empresasDisponibles->isEmpty())
                        <div class="text-yellow-400 text-sm mb-4">
                            No hay empresas disponibles para asociar. Todas las empresas ya están asociadas a este cliente o no existen empresas en el sistema.
                        </div>
                    @endif

                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-gray-300 hover:text-gray-100">
                            Cancelar
                        </button>
                        <button type="submit"
                                @if($empresasDisponibles->isEmpty()) disabled @endif
                                class="bg-green-600 hover:bg-green-700 disabled:bg-gray-600 px-6 py-2 rounded text-white font-medium">
                            Asociar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>