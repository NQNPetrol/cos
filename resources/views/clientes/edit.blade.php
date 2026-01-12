<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 bg-zinc-900 text-gray-50 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Editar Cliente</h2>

        @if (session('success'))
            <div class="bg-blue-600 text-white p-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario para eliminar logo -->
        @if($cliente->logo)
            <div class="mb-8 p-6 bg-zinc-800 rounded-lg border border-zinc-700">
                <p class="text-sm text-gray-400 mb-3 font-medium">Logo actual:</p>
                <div class="flex items-center gap-6">
                    <img src="{{ $cliente->logo_url }}" class="h-24 object-contain bg-white rounded-lg p-3 shadow">
                    <form action="{{ route('cliente.delete-logo', $cliente->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('¿Está seguro de que desea eliminar el logo?')"
                                class="text-red-400 hover:text-red-300 hover:bg-red-900/30 text-sm px-4 py-2 border border-red-400 rounded-lg transition-colors duration-200">
                            Eliminar logo
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Formulario principal  -->
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="bg-zinc-800 rounded-lg p-6 border border-zinc-700" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('nombre') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">CUIT</label>
                    <input type="text" name="cuit" value="{{ old('cuit', $cliente->cuit) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('cuit') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">Domicilio</label>
                    <input type="text" name="domicilio" value="{{ old('domicilio', $cliente->domicilio) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('domicilio') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">Provincia</label>
                    <input type="text" name="provincia" value="{{ old('provincia', $cliente->provincia) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">Categoría</label>
                    <input type="text" name="categoria" value="{{ old('categoria', $cliente->categoria) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm mb-2 font-medium text-gray-300">Convenio</label>
                    <input type="text" name="convenio" value="{{ old('convenio', $cliente->convenio) }}"
                           class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                
                <!-- Campo de logo -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-2 font-medium text-gray-300">Logo (PNG)</label>
                    
                   
                    <div class="relative">
                        <input type="file" name="logo" accept=".png" 
                               class="w-full bg-zinc-900 border border-zinc-700 text-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-700 file:text-gray-300 hover:file:bg-zinc-600 cursor-pointer">
                    </div>
                    
                    @error('logo') 
                        <span class="text-red-400 text-sm mt-2 block">{{ $message }}</span> 
                    @enderror
                    <p class="text-xs text-gray-400 mt-2">Formatos aceptados: PNG. Tamaño máximo: 2MB</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-zinc-700">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                    Guardar Cambios
                </button>
                <a href="{{ route('crear.cliente') }}" class="ml-4 text-gray-300 hover:text-white hover:underline transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>