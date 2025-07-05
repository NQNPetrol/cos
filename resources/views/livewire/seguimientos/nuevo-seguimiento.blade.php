<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Nuevo Seguimiento</h2>

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Título</label>
            <input type="text" wire:model="titulo" class="w-full px-3 py-2 border rounded">
            @error('titulo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Descripción</label>
            <textarea wire:model="descripcion" class="w-full px-3 py-2 border rounded" rows="4"></textarea>
            @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Fecha</label>
            <input type="date" wire:model="fecha" class="w-full px-3 py-2 border rounded">
            @error('fecha') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Guardar Seguimiento
        </button>
    </form>
</div>
