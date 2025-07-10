<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="px-6 py-4 bg-blue-900 border-b border-blue-700">
                <h2 class="text-xl font-semibold text-white">Nuevo Seguimiento</h2>
                <p class="text-blue-200 text-sm">Complete todos los campos</p>
            </div>
            
            <div class="px-6 py-6">
                <form action="{{ route('seguimientos.store') }}" method="POST">
                    @csrf
                    
                    <!-- Campo Evento -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Evento <span class="text-red-500">*</span>
                        </label>

                        @if($eventos->isEmpty())
                            <div class="bg-gray-700 border border-gray-600 rounded-md p-3 text-yellow-400">
                                No hay eventos disponibles
                            </div>
                        @else
                            <select name="id_evento" required
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md...">
                                <option value="">Seleccione evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">
                                        Evento #{{ $evento->id }} - {{ $evento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_evento')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>

                    <!-- Resto de campos del formulario -->
                    <!-- ... -->

                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('seguimientos.index') }}" 
                           class="px-4 py-2 border border-gray-600 rounded-md...">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md...">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>