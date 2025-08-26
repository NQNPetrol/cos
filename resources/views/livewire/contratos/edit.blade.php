<div class="bg-gray-900 text-gray-50 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Editar Contrato "{{ $contrato->nombre_proyecto }}"</h2>

    <form wire:submit.prevent="update">
        <!-- Cliente -->
        <div class="mb-4">
            <label class="block mb-1">Cliente <span class="text-red-500">*</span></label>
            <select wire:model="cliente_id" 
                    wire:change="cargarEmpresas($event.target.value)"
                    class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                <option value="">Seleccione un cliente</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $cliente->id == $contrato->cliente_id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Empresa Asociada -->
        <div class="mb-4" wire:init="cargarEmpresas({{ $cliente_id }})">
            <label class="block mb-1">Empresa Asociada al cliente <span class="text-red-500">*</span></label>
            <select wire:model="empresa_asociada_id"
                    class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2"
                    @if(!$cliente_id) disabled @endif>
                <option value="">Seleccione una empresa asociada</option>
                @foreach ($empresasFiltradas as $empresa)
                    <option value="{{ $empresa->id }}" {{ $empresa->id == $contrato->empresa_asociada_id ? 'selected' : '' }}>
                        {{ $empresa->nombre }}
                    </option>
                @endforeach
            </select>
            @error('empresa_asociada_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Nombre Proyecto -->
        <div class="mb-4">
            <label class="block mb-1">Nombre del Proyecto <span class="text-red-500">*</span></label>
            <input type="text" wire:model="nombre_proyecto"
                   class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
            @error('nombre_proyecto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Localidad y Provincia -->
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block mb-1">Localidad</label>
                <input type="text" wire:model="localidad"
                       class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                @error('localidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1">Provincia</label>
                <input type="text" wire:model="provincia"
                       class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                @error('provincia') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Observaciones -->
        <div class="mb-4">
            <label class="block mb-1">Observaciones</label>
            <textarea wire:model="observaciones" rows="3"
                      class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2"></textarea>
            @error('observaciones') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Fecha Inicio -->
        <div class="mb-4">
            <label class="block mb-1">Fecha Inicio</label>
            <input type="date" wire:model="fecha_inicio"
                   class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
            @error('fecha_inicio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Botones -->
        <div class="flex items-center justify-end">
            <a href="{{ route('contratos.index') }}" 
               class="mr-4 text-gray-300 hover:text-white">
               Cancelar
            </a>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                Actualizar Contrato
            </button>
        </div>
    </form>
</div>
