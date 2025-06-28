<div class="max-w-2xl mx-auto p-4">

    <h2 class="text-lg font-bold mb-4">Administrar Permisos</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="createPermission" class="mb-6">
        <div class="flex items-center gap-2">
            <input type="text" wire:model="name" placeholder="Nombre del permiso" class="border rounded px-3 py-2 w-full">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Agregar</button>
        </div>
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </form>

    <div class="border rounded p-4">
        <h3 class="font-semibold mb-2">Permisos existentes:</h3>
        <ul>
            @foreach ($permissions as $permission)
                <li class="flex justify-between items-center border-b py-2">
                    <span>{{ $permission->name }}</span>
                    <button wire:click="deletePermission({{ $permission->id }})" class="text-red-600 text-sm">Eliminar</button>
                </li>
            @endforeach
        </ul>
    </div>
</div>
