<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Proveedores y Talleres</h2>
                            <p class="text-gray-400 mt-1">Gestión de proveedores de servicios y talleres mecánicos</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">{{ session('success') }}</div>
                    @endif

                    <!-- Tabs -->
                    <div class="flex space-x-1 mb-6 bg-zinc-800 rounded-lg p-1" id="proveedores-tabs">
                        <button class="tab-btn active flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors bg-blue-600 text-white" data-tab="proveedores">Proveedores</button>
                        <button class="tab-btn flex-1 px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-400 hover:text-gray-200" data-tab="talleres">Talleres</button>
                    </div>

                    <!-- Proveedores Tab -->
                    <div id="tab-proveedores" class="tab-content">
                        <div class="flex justify-end mb-4">
                            <button onclick="document.getElementById('modal-proveedor').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Nuevo Proveedor
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-700">
                                <thead class="bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Nombre</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Contacto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Teléfono</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Talleres Vinculados</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700">
                                    @forelse($proveedores as $proveedor)
                                    <tr class="hover:bg-zinc-800 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-200">{{ $proveedor->nombre }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $proveedor->contacto ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $proveedor->telefono ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $proveedor->email ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">
                                            @if($proveedor->talleres->count() > 0)
                                                @foreach($proveedor->talleres as $taller)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-900 text-blue-300">{{ $taller->nombre }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-gray-500">Ninguno</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <button onclick="editarProveedor({{ $proveedor->id }}, '{{ $proveedor->nombre }}', '{{ $proveedor->contacto }}', '{{ $proveedor->telefono }}', '{{ $proveedor->email }}')" class="text-blue-400 hover:text-blue-300" title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <form action="{{ route('rodados.proveedores.destroy', $proveedor) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este proveedor?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300" title="Eliminar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No hay proveedores registrados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Talleres Tab -->
                    <div id="tab-talleres" class="tab-content hidden">
                        <div class="flex justify-end mb-4">
                            <button onclick="document.getElementById('modal-taller').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Nuevo Taller
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-700">
                                <thead class="bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Nombre</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Contacto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Teléfono</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">WhatsApp</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Proveedor</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700">
                                    @forelse($talleres as $taller)
                                    <tr class="hover:bg-zinc-800 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-200">{{ $taller->nombre }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $taller->contacto ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $taller->telefono ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $taller->email ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $taller->whatsapp ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-400">{{ $taller->proveedor->nombre ?? 'Independiente' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                @if($taller->whatsapp_link)
                                                <a href="{{ $taller->whatsapp_link }}" target="_blank" class="text-green-400 hover:text-green-300" title="WhatsApp">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                </a>
                                                @endif
                                                @if($taller->mailto_link)
                                                <a href="{{ $taller->mailto_link }}" class="text-blue-400 hover:text-blue-300" title="Enviar correo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                </a>
                                                @endif
                                                <form action="{{ route('rodados.talleres.destroy', $taller) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este taller?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300" title="Eliminar">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No hay talleres registrados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Proveedor -->
    <div id="modal-proveedor" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-lg w-full max-w-md border border-zinc-700">
            <form id="form-proveedor" method="POST" action="{{ route('rodados.proveedores.store') }}">
                @csrf
                <div id="proveedor-method"></div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-100 mb-4" id="modal-proveedor-title">Nuevo Proveedor</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nombre *</label>
                            <input type="text" name="nombre" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Contacto</label>
                            <input type="text" name="contacto" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teléfono</label>
                            <input type="text" name="telefono" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-4 bg-zinc-800 rounded-b-lg">
                    <button type="button" onclick="document.getElementById('modal-proveedor').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Nuevo Taller -->
    <div id="modal-taller" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-lg w-full max-w-md border border-zinc-700">
            <form id="form-taller" method="POST" action="{{ route('rodados.talleres.store') }}">
                @csrf
                <div id="taller-method"></div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-100 mb-4">Nuevo Taller</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nombre *</label>
                            <input type="text" name="nombre" required class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Contacto</label>
                            <input type="text" name="contacto" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Teléfono</label>
                            <input type="text" name="telefono" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                            <input type="email" name="email" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">WhatsApp</label>
                            <input type="text" name="whatsapp" placeholder="Ej: +5491112345678" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Dirección</label>
                            <input type="text" name="direccion" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Proveedor Vinculado</label>
                            <select name="proveedor_id" class="w-full bg-zinc-800 border-zinc-600 rounded-md text-gray-200 text-sm">
                                <option value="">Sin proveedor (independiente)</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-4 bg-zinc-800 rounded-b-lg">
                    <button type="button" onclick="document.getElementById('modal-taller').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active', 'bg-blue-600', 'text-white'); b.classList.add('text-gray-400'); });
                this.classList.add('active', 'bg-blue-600', 'text-white'); this.classList.remove('text-gray-400');
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
            });
        });

        function editarProveedor(id, nombre, contacto, telefono, email) {
            const form = document.getElementById('form-proveedor');
            form.action = '/rodados/proveedores/' + id;
            document.getElementById('proveedor-method').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('modal-proveedor-title').textContent = 'Editar Proveedor';
            form.querySelector('[name="nombre"]').value = nombre;
            form.querySelector('[name="contacto"]').value = contacto;
            form.querySelector('[name="telefono"]').value = telefono;
            form.querySelector('[name="email"]').value = email;
            document.getElementById('modal-proveedor').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>
