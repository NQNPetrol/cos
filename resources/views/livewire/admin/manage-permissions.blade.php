<div class="max-w-4xl mx-auto p-6 bg-gray-900 text-gray-50 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-200">Administrar Permisos</h2>

    @if (session()->has('success'))
        <div class="bg-emerald-600 text-white p-4 rounded-lg mb-6 shadow-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Formulario para crear permiso -->
    <form wire:submit.prevent="createPermission" class="mb-8 bg-gray-800 rounded-lg p-6 shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-200">Crear Nuevo Permiso</h3>
        
        <div class="flex items-center gap-3">
            <input type="text" wire:model="name" placeholder="Ingrese el nombre del permiso" 
                   class="flex-1 bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 
                          focus:ring-2 focus:ring-emerald-500 focus:border-transparent 
                          placeholder-gray-400 transition duration-200">
            
            <button type="submit" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg 
                           font-medium transition duration-200 transform hover:scale-105 
                           flex items-center gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Agregar
            </button>
        </div>
        
        @error('name') 
            <span class="text-red-400 text-sm mt-2 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </span> 
        @enderror
    </form>

    <!-- Lista de permisos existentes -->
    <div class="bg-gray-800 rounded-lg p-6 shadow">
        <h3 class="text-lg font-semibold mb-4 text-gray-200 flex items-center">
            <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            Permisos Existentes
        </h3>

        @if($permissions->count() > 0)
            <div class="space-y-3">
                @foreach ($permissions as $permission)
                    <div class="flex justify-between items-center bg-gray-700 p-4 rounded-lg 
                                hover:bg-gray-600 transition duration-200 group">
                        <span class="font-mono text-sm text-gray-200 bg-gray-600 px-3 py-1 rounded">
                            {{ $permission->name }}
                        </span>
                        
                        <button wire:click="deletePermission({{ $permission->id }})" 
                                onclick="return confirm('¿Está seguro de eliminar este permiso?')"
                                class="text-red-400 hover:text-red-300 p-2 rounded-lg 
                                       hover:bg-red-900/30 transition duration-200 group-hover:opacity-100"
                                title="Eliminar permiso">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>No hay permisos creados</p>
            </div>
        @endif
    </div>

    <!-- Loading indicator -->
    <div wire:loading class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-400 mx-auto"></div>
            <p class="text-gray-300 mt-4">Procesando...</p>
        </div>
    </div>
</div>