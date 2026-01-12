<div class="max-w-4xl mx-auto p-6 bg-zinc-900 text-gray-50 rounded-lg shadow">
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
    <form wire:submit.prevent="createRole" class="mb-6 p-4 bg-zinc-800 rounded">
        <h3 class="text-lg font-semibold mb-3">Crear Nuevo Rol</h3>
        <div class="flex items-center gap-2">
            <input type="text" wire:model="name" placeholder="Nombre del rol" 
                   class="bg-zinc-700 border-zinc-600 text-white rounded px-3 py-2 flex-1">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                +
            </button>
        </div>
        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
    </form>

    <!-- Lista de roles existentes -->
    <div class="bg-zinc-800 rounded p-4">
        <h3 class="text-lg font-semibold mb-3">Roles Existentes</h3>
        
        @if($roles->isEmpty())
            <p class="text-gray-400">No hay roles creados.</p>
        @else
            <div class="space-y-2">
                @foreach ($roles as $role)
                    <div class="flex justify-between items-center p-3 bg-zinc-700 rounded">
                        @if($editingRoleId === $role->id)
                            <div class="flex items-center gap-2 flex-1">
                                <input type="text" wire:model="editName" 
                                       class="bg-zinc-600 border-zinc-500 text-white rounded px-2 py-1 flex-1">
                                <button wire:click="updateRole({{ $role->id }})" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Guardar
                                </button>
                                <button wire:click="cancelEdit" 
                                        class="bg-zinc-600 hover:bg-zinc-700 text-white px-3 py-1 rounded text-sm">
                                    Cancelar
                                </button>
                            </div>
                        @else
                            <span class="font-medium">{{ $role->name }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('asignar.permisos') }}?role={{ $role->id }}" 
                                   class="text-green-400 hover:text-green-300 p-1"
                                   title="Asignar permisos">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </a>
                                <button wire:click="startEdit({{ $role->id }})" 
                                        class="text-blue-400 hover:text-blue-300 p-1"
                                        title="Editar rol">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="deleteRole({{ $role->id }})" 
                                        onclick="return confirm('¿Estás seguro de eliminar este rol?')"
                                        class="text-red-400 hover:text-red-300 p-1"
                                        title="Eliminar rol">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
