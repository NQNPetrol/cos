<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 bg-gray-900 text-gray-50 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Editar Cliente</h2>

        @if (session('success'))
            <div class="bg-emerald-600 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="bg-gray-800 rounded-lg p-4">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                    @error('nombre') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">CUIT</label>
                    <input type="text" name="cuit" value="{{ old('cuit', $cliente->cuit) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                    @error('cuit') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">Domicilio</label>
                    <input type="text" name="domicilio" value="{{ old('domicilio', $cliente->domicilio) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                    @error('domicilio') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm mb-1">Provincia</label>
                    <input type="text" name="provincia" value="{{ old('provincia', $cliente->provincia) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm mb-1">Categoría</label>
                    <input type="text" name="categoria" value="{{ old('categoria', $cliente->categoria) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm mb-1">Convenio</label>
                    <input type="text" name="convenio" value="{{ old('convenio', $cliente->convenio) }}"
                           class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded px-3 py-2">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">
                    Guardar Cambios
                </button>
                <a href="{{ route('crear.cliente') }}" class="ml-3 text-gray-300 hover:underline">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
