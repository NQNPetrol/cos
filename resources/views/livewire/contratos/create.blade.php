    <div>
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label class="block mb-1">Cliente <span class="text-red-500">*</span></label>
                <select wire:model="cliente_id" wire:change="cargarEmpresas($event.target.value)" class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
                @error('cliente_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1">Empresa Asociada al cliente <span class="text-red-500">*</span></label>
                <select wire:model="empresa_asociada_id"
                        class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2"
                        @if(!$cliente_id) disabled @endif>
                    <option value="">Seleccione una empresa asociada</option>
                    @foreach ($empresasFiltradas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                    @endforeach
                </select>
                @error('empresa_asociada_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Nombre Proyecto -->
            <div class="mb-4">
                <label class="block mb-1">Nombre del Proyecto <span class="text-red-500">*</span></label>
                <input type="text" wire:model="nombre_proyecto"
                       class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
            </div>

            <!-- Localidad y Provincia -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Localidad</label>
                    <input type="text" wire:model="localidad" 
                           class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block mb-1">Provincia</label>
                    <input type="text" wire:model="provincia" 
                           class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
            </div>

            <!-- Observaciones -->
            <div class="mb-4">
                <label class="block mb-1">Observaciones</label>
                <textarea wire:model="observaciones"
                          class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">{{ old('observaciones') }}</textarea>
            </div>

            <!-- Fechas -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Fecha Inicio</label>
                    <input type="date" wire:model="fecha_inicio" 
                           class="w-full bg-gray-800 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>

            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('contratos.index') }}" 
                   class="mr-4 text-gray-300 hover:text-white">
                   Cancelar
                </a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                    Guardar Contrato
                </button>
            </div>
        </form>
    </div>

