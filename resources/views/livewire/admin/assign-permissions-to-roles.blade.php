<div class="max-w-5xl mx-auto p-6 bg-gray-900 text-gray-50 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-6">Asignar Permisos a Roles</h2>

    @if ($successMessage)
        <div class="bg-emerald-600 text-white p-3 rounded mb-4">
            {{ $successMessage }}
        </div>
    @endif

    <div class="mb-6">
        <label for="role" class="block mb-2 text-sm font-medium">Seleccionar Rol:</label>
        <select wire:model="currentRoleId" wire:change="loadPermissions($event.target.value)"
                class="bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2 w-full">
            <option value="">-- Selecciona un Rol --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    @if ($currentRoleId)
        <form wire:submit.prevent="updatePermissions">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
                @foreach ($permissions as $permission)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                               class="rounded border-gray-600 bg-gray-800 text-emerald-500 focus:ring focus:ring-emerald-500">
                        <span>{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                Guardar Cambios
            </button>
        </form>
    @endif
</div>
