
    <div class="max-w-7xl py-8 sm:px-6 lg:px-8 bg-gray-800 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-50 mb-6">{{ $header }}</h2>
        
        @if (session()->has('success'))
            <div class="mb-4 px-4 py-2 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-6 bg-gray-800">
                <form wire:submit.prevent="save">
                    <!-- Campo Evento -->
                    <div class="mb-6">
                        <label for="id_evento" class="block text-sm font-medium text-gray-300 mb-2">
                            Evento <span class="text-red-500">*</span>
                        </label>

                        @if($eventos->isEmpty())
                            <div class="bg-gray-700 border border-gray-600 rounded-md p-3 text-yellow-400">
                                No hay eventos disponibles para seguimiento. Todos los eventos están cerrados o no hay eventos creados.
                            </div>
                        @else
                        <select wire:model="id_evento" id="id_evento" 
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm 
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white">
                            <option value="">Seleccione un evento</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}">Evento #{{ $evento->id }} - {{ $evento->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_evento') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        @endif
                    </div>

                    <!-- Campo Estado -->
                    <div class="mb-6">
                        <label for="estado" class="block text-sm font-medium text-gray-300 mb-2">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="estado" id="estado" 
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm 
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white">
                            <option value="ABIERTO">ABIERTO</option>
                            <option value="EN REVISION">EN REVISIÓN</option>
                            <option value="CERRADO">CERRADO</option>
                        </select>
                        @error('estado') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Campo Registra (automático) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Registrado por
                        </label>
                        <div class="px-3 py-2 bg-gray-700 rounded-md text-gray-300">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <!-- Campo Fecha Registro (automático) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Fecha de Registro
                        </label>
                        <div class="px-3 py-2 bg-gray-700 rounded-md text-gray-300">
                            {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <!-- Campo Detalles -->
                    <div class="mb-6">
                        <label for="detalles" class="block text-sm font-medium text-gray-300 mb-2">
                            Detalles <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="detalles" id="detalles" rows="5"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm 
                                        focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-white"
                                placeholder="Describa los detalles del seguimiento..."></textarea>
                        @error('detalles') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        @error('save_error') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('seguimientos.index') }}" 
                        class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 bg-gray-700 
                                hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-white bg-blue-600 
                                    hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Guardar Seguimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
