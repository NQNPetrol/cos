<div class="max-w-4xl mx-auto p-6 bg-gray-900 text-gray-50 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Gestión de Roles</h2>

    @if (session()->has('success'))
        <div class="bg-emerald-600 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-600 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario para crear rol -->
    <form wire:submit.prevent="createRole" class="mb-6 p-4 bg-gray-800 rounded">
        <h3 class="text-lg font-semibold mb-3">Crear Nuevo Rol</h3>
        <div class="flex items-center gap-2">
            <input type="text" wire:model="name" placeholder="Nombre del rol" 
                   class="bg-gray-700 border-gray-600 text-white rounded px-3 py-2 flex-1">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                +
            </button>
        </div>
        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
    </form>

    <!-- Lista de roles existentes -->
    <div class="bg-gray-800 rounded p-4">
        <h3 class="text-lg font-semibold mb-3">Roles Existentes</h3>
        
        @if($roles->isEmpty())
            <p class="text-gray-400">No hay roles creados.</p>
        @else
            <div class="space-y-2">
                @foreach ($roles as $role)
                    <div class="flex justify-between items-center p-3 bg-gray-700 rounded">
                        @if($editingRoleId === $role->id)
                            <div class="flex items-center gap-2 flex-1">
                                <input type="text" wire:model="editName" 
                                       class="bg-gray-600 border-gray-500 text-white rounded px-2 py-1 flex-1">
                                <button wire:click="updateRole({{ $role->id }})" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Guardar
                                </button>
                                <button wire:click="cancelEdit" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                    Cancelar
                                </button>
                            </div>
                        @else
                            <span class="font-medium">{{ $role->name }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('asignar.permisos') }}?role={{ $role->id }}" 
                                   class="text-gray-400 hover:text-gray-300 text-sm">
                                    Permisos
                                </a>
                                <button wire:click="startEdit({{ $role->id }})" 
                                        class="text-blue-400 hover:text-blue-300 text-sm">
                                    Editar
                                </button>
                                <button wire:click="deleteRole({{ $role->id }})" 
                                        onclick="return confirm('¿Estás seguro de eliminar este rol?')"
                                        class="text-red-400 hover:text-red-300 text-sm">
                                    Eliminar
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
