<x-administrative-layout>
    <div class="py-6">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Toast Notifications -->
            @if(session('success'))
                <div id="toast-success" class="fixed z-[100] max-w-sm w-full toast-enter" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
                    <div class="bg-zinc-900 border border-green-500/30 rounded-2xl shadow-2xl shadow-green-900/20 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-green-400">Operacion exitosa</p>
                            <p class="text-sm text-gray-300 mt-0.5">{{ session('success') }}</p>
                        </div>
                        <button onclick="dismissToast('toast-success')" class="flex-shrink-0 p-1 text-gray-500 hover:text-gray-300 rounded-lg hover:bg-zinc-800 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-1 mx-4 h-0.5 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full toast-progress"></div>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div id="toast-error" class="fixed z-[100] max-w-sm w-full toast-enter" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
                    <div class="bg-zinc-900 border border-red-500/30 rounded-2xl shadow-2xl shadow-red-900/20 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-red-400">Error</p>
                            <p class="text-sm text-gray-300 mt-0.5">{{ session('error') }}</p>
                        </div>
                        <button onclick="dismissToast('toast-error')" class="flex-shrink-0 p-1 text-gray-500 hover:text-gray-300 rounded-lg hover:bg-zinc-800 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="mt-1 mx-4 h-0.5 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 rounded-full toast-progress"></div>
                    </div>
                </div>
            @endif

            <!-- Header Principal -->
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                            <svg class="w-7 h-7 text-blue-400" fill="currentColor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M92.05,36H10.29a1.51,1.51,0,0,1-1.45-1.11,1.49,1.49,0,0,1,.68-1.68L50.4,8.85a1.5,1.5,0,0,1,1.53,0L92.82,33.16a1.5,1.5,0,0,1,.68,1.68A1.52,1.52,0,0,1,92.05,36ZM15.74,33H86.59L51.17,11.89Z"/><path d="M17.49,91.53A1.5,1.5,0,0,1,16,90V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,17.49,91.53Z"/><path d="M85.15,91.53a1.5,1.5,0,0,1-1.5-1.5V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,85.15,91.53Z"/><path d="M85.15,42.85H17.49a1.5,1.5,0,1,1,0-3H85.15a1.5,1.5,0,0,1,0,3Z"/><path d="M51.22,91.79H26.81a1.5,1.5,0,0,1-1.5-1.5v-21a1.5,1.5,0,0,1,1.5-1.5H51.22a1.5,1.5,0,0,1,1.5,1.5v21A1.5,1.5,0,0,1,51.22,91.79Zm-22.91-3H49.72v-18H28.31Z"/><path d="M75.69,91.79H51.27a1.5,1.5,0,0,1-1.5-1.5v-21a1.5,1.5,0,0,1,1.5-1.5H75.69a1.5,1.5,0,0,1,1.5,1.5v21A1.5,1.5,0,0,1,75.69,91.79Zm-22.92-3H74.19v-18H52.77Z"/><path d="M63.52,70.81H39.1a1.5,1.5,0,0,1-1.5-1.5v-21a1.51,1.51,0,0,1,1.5-1.5H63.52a1.5,1.5,0,0,1,1.5,1.5v21A1.5,1.5,0,0,1,63.52,70.81Zm-22.92-3H62v-18H40.6Z"/><path d="M44.36,76.25H33.75a1.5,1.5,0,0,1,0-3H44.36a1.5,1.5,0,0,1,0,3Z"/><path d="M44.36,81.25H33.75a1.5,1.5,0,0,1,0-3H44.36a1.5,1.5,0,0,1,0,3Z"/><path d="M68.79,76.14H58.18a1.5,1.5,0,1,1,0-3H68.79a1.5,1.5,0,0,1,0,3Z"/><path d="M68.79,81.14H58.18a1.5,1.5,0,1,1,0-3H68.79a1.5,1.5,0,0,1,0,3Z"/><path d="M56.54,55.46H45.93a1.5,1.5,0,0,1,0-3H56.54a1.5,1.5,0,0,1,0,3Z"/><path d="M56.54,60.46H45.93a1.5,1.5,0,0,1,0-3H56.54a1.5,1.5,0,0,1,0,3Z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Proveedores y Talleres</h1>
                            <p class="text-sm text-gray-400 mt-0.5">Gestión de proveedores de servicios y talleres mecánicos</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                            <span class="text-xs text-gray-400">Proveedores</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $proveedores->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-xs text-gray-400">Talleres</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $talleres->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex items-center gap-2 mb-6">
                <button onclick="switchProvTab('proveedores')" id="prov-tab-proveedores"
                    class="prov-tab-button group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M92.05,36H10.29a1.51,1.51,0,0,1-1.45-1.11,1.49,1.49,0,0,1,.68-1.68L50.4,8.85a1.5,1.5,0,0,1,1.53,0L92.82,33.16a1.5,1.5,0,0,1,.68,1.68A1.52,1.52,0,0,1,92.05,36ZM15.74,33H86.59L51.17,11.89Z"/><path d="M17.49,91.53A1.5,1.5,0,0,1,16,90V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,17.49,91.53Z"/><path d="M85.15,91.53a1.5,1.5,0,0,1-1.5-1.5V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,85.15,91.53Z"/><path d="M85.15,42.85H17.49a1.5,1.5,0,1,1,0-3H85.15a1.5,1.5,0,0,1,0,3Z"/><path d="M51.22,91.79H26.81v-21H51.22v21Zm-22.91-3H49.72v-18H28.31Z"/><path d="M75.69,91.79H51.27v-21H75.69v21Zm-22.92-3H74.19v-18H52.77Z"/><path d="M63.52,70.81H39.1v-21H63.52v21Zm-22.92-3H62v-18H40.6Z"/></svg>
                        Proveedores
                        <span class="text-xs opacity-75 bg-white/20 px-1.5 py-0.5 rounded-md">{{ $proveedores->count() }}</span>
                    </span>
                </button>
                <button onclick="switchProvTab('talleres')" id="prov-tab-talleres"
                    class="prov-tab-button group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-zinc-800 text-gray-400 hover:text-gray-200 hover:bg-zinc-700 border border-zinc-700/50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 256 253" xmlns="http://www.w3.org/2000/svg"><path d="M2,69c0,13.678,9.625,25.302,22,29.576V233H2v18h252v-18h-22V98.554c12.89-3.945,21.699-15.396,22-29.554v-8H2V69z M65.29,68.346c0,6.477,6.755,31.47,31.727,31.47c21.689,0,31.202-19.615,31.202-31.47c0,11.052,7.41,31.447,31.464,31.447c21.733,0,31.363-20.999,31.363-31.447c0,14.425,9.726,26.416,22.954,30.154V233H42V98.594C55.402,94.966,65.29,82.895,65.29,68.346z M254,54H2l32-32V2h189v20h-0.168L254,54z"/></svg>
                        Talleres
                        <span class="text-xs opacity-60 bg-zinc-700 px-1.5 py-0.5 rounded-md">{{ $talleres->count() }}</span>
                    </span>
                </button>
            </div>

            <!-- ==================== PROVEEDORES TAB ==================== -->
            <div id="prov-tab-content-proveedores" class="prov-tab-content">
                <!-- Action Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="busqueda-proveedor" placeholder="Buscar proveedor..."
                            class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 w-64 transition-all"
                            oninput="buscarProveedor(this.value)">
                    </div>
                    <button onclick="nuevoProveedor()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo Proveedor
                    </button>
                </div>

                <!-- Tabla Proveedores -->
                <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-zinc-700/50">
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Proveedor</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Contacto</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Talleres Vinculados</th>
                                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @forelse($proveedores as $proveedor)
                                <tr class="proveedor-row group hover:bg-zinc-700/30 transition-colors duration-150"
                                    data-search="{{ strtolower($proveedor->nombre . ' ' . ($proveedor->contacto ?? '') . ' ' . ($proveedor->email ?? '')) }}">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600/20 to-indigo-600/20 border border-blue-500/20 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 100 100"><path d="M92.05,36H10.29a1.51,1.51,0,0,1-1.45-1.11,1.49,1.49,0,0,1,.68-1.68L50.4,8.85a1.5,1.5,0,0,1,1.53,0L92.82,33.16a1.5,1.5,0,0,1,.68,1.68A1.52,1.52,0,0,1,92.05,36ZM15.74,33H86.59L51.17,11.89Z"/><path d="M17.49,91.53A1.5,1.5,0,0,1,16,90V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,17.49,91.53Z"/><path d="M85.15,91.53a1.5,1.5,0,0,1-1.5-1.5V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,85.15,91.53Z"/><path d="M85.15,42.85H17.49a1.5,1.5,0,1,1,0-3H85.15a1.5,1.5,0,0,1,0,3Z"/></svg>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-100">{{ $proveedor->nombre }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-400">{{ $proveedor->contacto ?? '--' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-400">{{ $proveedor->telefono ?? '--' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-400">{{ $proveedor->email ?? '--' }}</td>
                                    <td class="px-5 py-4">
                                        @if($proveedor->talleres->count() > 0)
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($proveedor->talleres as $taller)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                        <div class="w-1 h-1 rounded-full bg-blue-400"></div>
                                                        {{ $taller->nombre }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-600 italic">Ninguno</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <button onclick="editarProveedor({{ $proveedor->id }}, '{{ addslashes($proveedor->nombre) }}', '{{ addslashes($proveedor->contacto ?? '') }}', '{{ addslashes($proveedor->telefono ?? '') }}', '{{ addslashes($proveedor->email ?? '') }}')"
                                                class="p-2 rounded-lg text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 transition-all duration-150" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                            </button>
                                            <form action="{{ route('rodados.proveedores.destroy', $proveedor) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este proveedor?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 100 100"><path d="M92.05,36H10.29a1.51,1.51,0,0,1-1.45-1.11,1.49,1.49,0,0,1,.68-1.68L50.4,8.85a1.5,1.5,0,0,1,1.53,0L92.82,33.16a1.5,1.5,0,0,1,.68,1.68A1.52,1.52,0,0,1,92.05,36Z"/><path d="M17.49,91.53A1.5,1.5,0,0,1,16,90V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,17.49,91.53Z"/><path d="M85.15,91.53a1.5,1.5,0,0,1-1.5-1.5V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,85.15,91.53Z"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-medium">No hay proveedores registrados</p>
                                            <p class="text-gray-600 text-sm mt-1">Comienza agregando un nuevo proveedor</p>
                                            <button onclick="nuevoProveedor()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm text-white transition">+ Agregar Proveedor</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($proveedores->count() > 0)
                    <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
                        <span class="text-xs text-gray-500" id="proveedores-count-text">Mostrando {{ $proveedores->count() }} proveedores</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ==================== TALLERES TAB ==================== -->
            <div id="prov-tab-content-talleres" class="prov-tab-content hidden">
                <!-- Action Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="busqueda-taller" placeholder="Buscar taller..."
                            class="pl-10 pr-4 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 w-64 transition-all"
                            oninput="buscarTaller(this.value)">
                    </div>
                    <button onclick="nuevoTaller()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-xl text-sm font-medium text-white shadow-lg shadow-amber-600/20 hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo Taller
                    </button>
                </div>

                <!-- Tabla Talleres -->
                <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-zinc-700/50">
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Taller</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Contacto</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">WhatsApp</th>
                                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Proveedor</th>
                                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/30">
                                @forelse($talleres as $taller)
                                <tr class="taller-row group hover:bg-zinc-700/30 transition-colors duration-150"
                                    data-search="{{ strtolower($taller->nombre . ' ' . ($taller->contacto ?? '') . ' ' . ($taller->email ?? '') . ' ' . ($taller->proveedor->nombre ?? '')) }}">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-amber-600/20 to-orange-600/20 border border-amber-500/20 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 256 253"><path d="M2,69c0,13.678,9.625,25.302,22,29.576V233H2v18h252v-18h-22V98.554c12.89-3.945,21.699-15.396,22-29.554v-8H2V69z M65.29,68.346c0,6.477,6.755,31.47,31.727,31.47c21.689,0,31.202-19.615,31.202-31.47c0,11.052,7.41,31.447,31.464,31.447c21.733,0,31.363-20.999,31.363-31.447c0,14.425,9.726,26.416,22.954,30.154V233H42V98.594C55.402,94.966,65.29,82.895,65.29,68.346z M254,54H2l32-32V2h189v20h-0.168L254,54z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-100">{{ $taller->nombre }}</div>
                                                @if($taller->telefono)
                                                    <div class="text-xs text-gray-500 mt-0.5">{{ $taller->telefono }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-400">{{ $taller->contacto ?? '--' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-400">{{ $taller->email ?? '--' }}</td>
                                    <td class="px-5 py-4">
                                        @if($taller->whatsapp)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                                {{ $taller->whatsapp }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-600">--</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($taller->proveedor)
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                                <span class="text-sm text-gray-300">{{ $taller->proveedor->nombre }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500 italic">Independiente</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <button onclick="editarTaller({{ $taller->id }}, '{{ addslashes($taller->nombre) }}', '{{ addslashes($taller->contacto ?? '') }}', '{{ addslashes($taller->telefono ?? '') }}', '{{ addslashes($taller->email ?? '') }}', '{{ addslashes($taller->whatsapp ?? '') }}', '{{ addslashes($taller->direccion ?? '') }}', '{{ $taller->proveedor_id ?? '' }}')"
                                                class="p-2 rounded-lg text-gray-400 hover:text-amber-400 hover:bg-amber-500/10 transition-all duration-150" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                            </button>
                                            @if($taller->whatsapp_link)
                                            <a href="{{ $taller->whatsapp_link }}" target="_blank"
                                                class="p-2 rounded-lg text-gray-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all duration-150" title="WhatsApp">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            </a>
                                            @endif
                                            @if($taller->mailto_link)
                                            <a href="{{ $taller->mailto_link }}"
                                                class="p-2 rounded-lg text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 transition-all duration-150" title="Enviar correo">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                            </a>
                                            @endif
                                            <form action="{{ route('rodados.talleres.destroy', $taller) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este taller?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-150" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-full bg-zinc-700/30 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 256 253"><path d="M2,69c0,13.678,9.625,25.302,22,29.576V233H2v18h252v-18h-22V98.554c12.89-3.945,21.699-15.396,22-29.554v-8H2V69z M254,54H2l32-32V2h189v20h-0.168L254,54z"/></svg>
                                            </div>
                                            <p class="text-gray-400 font-medium">No hay talleres registrados</p>
                                            <p class="text-gray-600 text-sm mt-1">Comienza agregando un nuevo taller</p>
                                            <button onclick="nuevoTaller()" class="mt-4 px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-lg text-sm text-white transition">+ Agregar Taller</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($talleres->count() > 0)
                    <div class="px-5 py-3 bg-zinc-900/30 border-t border-zinc-700/30 flex items-center justify-between">
                        <span class="text-xs text-gray-500" id="talleres-count-text">Mostrando {{ $talleres->count() }} talleres</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Proveedor -->
    <div id="modal-proveedor" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) document.getElementById('modal-proveedor').classList.add('hidden')">
        <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden modal-content" onclick="event.stopPropagation()">
            <form id="form-proveedor" method="POST" action="{{ route('rodados.proveedores.store') }}">
                @csrf
                <div id="proveedor-method"></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-600/10 rounded-lg">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 100 100"><path d="M92.05,36H10.29a1.51,1.51,0,0,1-1.45-1.11,1.49,1.49,0,0,1,.68-1.68L50.4,8.85a1.5,1.5,0,0,1,1.53,0L92.82,33.16a1.5,1.5,0,0,1,.68,1.68A1.52,1.52,0,0,1,92.05,36ZM15.74,33H86.59L51.17,11.89Z"/><path d="M17.49,91.53A1.5,1.5,0,0,1,16,90V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,17.49,91.53Z"/><path d="M85.15,91.53a1.5,1.5,0,0,1-1.5-1.5V34.45a1.5,1.5,0,0,1,3,0V90A1.5,1.5,0,0,1,85.15,91.53Z"/><path d="M85.15,42.85H17.49a1.5,1.5,0,1,1,0-3H85.15a1.5,1.5,0,0,1,0,3Z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-100" id="modal-proveedor-title">Nuevo Proveedor</h3>
                    </div>
                    <button type="button" onclick="document.getElementById('modal-proveedor').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5 max-h-[60vh] overflow-y-auto space-y-5 modal-scroll">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Nombre *</label>
                        <input type="text" name="nombre" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Contacto</label>
                        <input type="text" name="contacto" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Teléfono</label>
                            <input type="text" name="telefono" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Email</label>
                            <input type="email" name="email" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50">
                    <button type="button" onclick="document.getElementById('modal-proveedor').classList.add('hidden')" class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Nuevo Taller -->
    <div id="modal-taller" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center modal-backdrop" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1.5rem); padding-right: 1rem; padding-bottom: 1rem;" onclick="if(event.target === this) document.getElementById('modal-taller').classList.add('hidden')">
        <div class="w-full max-w-md bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden flex flex-col modal-content" style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 4rem);" onclick="event.stopPropagation()">
            <form id="form-taller" method="POST" action="{{ route('rodados.talleres.store') }}" class="flex flex-col overflow-hidden">
                @csrf
                <div id="taller-method"></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-600/10 rounded-lg">
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 256 253"><path d="M2,69c0,13.678,9.625,25.302,22,29.576V233H2v18h252v-18h-22V98.554c12.89-3.945,21.699-15.396,22-29.554v-8H2V69z M65.29,68.346c0,6.477,6.755,31.47,31.727,31.47c21.689,0,31.202-19.615,31.202-31.47c0,11.052,7.41,31.447,31.464,31.447c21.733,0,31.363-20.999,31.363-31.447c0,14.425,9.726,26.416,22.954,30.154V233H42V98.594C55.402,94.966,65.29,82.895,65.29,68.346z M254,54H2l32-32V2h189v20h-0.168L254,54z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-100" id="modal-taller-title">Nuevo Taller</h3>
                    </div>
                    <button type="button" onclick="document.getElementById('modal-taller').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5 overflow-y-auto flex-1 space-y-5 modal-scroll">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Nombre *</label>
                        <input type="text" name="nombre" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Contacto</label>
                            <input type="text" name="contacto" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Teléfono</label>
                            <input type="text" name="telefono" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Email</label>
                            <input type="email" name="email" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">WhatsApp</label>
                            <input type="text" name="whatsapp" placeholder="+5491112345678" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Dirección</label>
                        <input type="text" name="direccion" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Proveedor Vinculado</label>
                        <select name="proveedor_id" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                            <option value="">Sin proveedor (independiente)</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
                    <button type="button" onclick="document.getElementById('modal-taller').classList.add('hidden')" class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-lg shadow-amber-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchProvTab(tabName) {
            document.querySelectorAll('.prov-tab-content').forEach(c => c.classList.add('hidden'));
            document.querySelectorAll('.prov-tab-button').forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
                b.classList.add('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            });
            document.getElementById('prov-tab-content-' + tabName).classList.remove('hidden');
            const activeBtn = document.getElementById('prov-tab-' + tabName);
            activeBtn.classList.remove('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
        }

        function buscarProveedor(query) {
            const q = query.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.proveedor-row').forEach(row => {
                const match = !q || (row.dataset.search || '').includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const total = document.querySelectorAll('.proveedor-row').length;
            const el = document.getElementById('proveedores-count-text');
            if (el) el.textContent = visible === total ? `Mostrando ${total} proveedores` : `Mostrando ${visible} de ${total} proveedores`;
        }

        function buscarTaller(query) {
            const q = query.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.taller-row').forEach(row => {
                const match = !q || (row.dataset.search || '').includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const total = document.querySelectorAll('.taller-row').length;
            const el = document.getElementById('talleres-count-text');
            if (el) el.textContent = visible === total ? `Mostrando ${total} talleres` : `Mostrando ${visible} de ${total} talleres`;
        }

        function nuevoTaller() {
            const form = document.getElementById('form-taller');
            form.action = '{{ route('rodados.talleres.store') }}';
            document.getElementById('taller-method').innerHTML = '';
            document.getElementById('modal-taller-title').textContent = 'Nuevo Taller';
            form.reset();
            document.getElementById('modal-taller').classList.remove('hidden');
        }

        function nuevoProveedor() {
            const form = document.getElementById('form-proveedor');
            form.action = '{{ route('rodados.proveedores.store') }}';
            document.getElementById('proveedor-method').innerHTML = '';
            document.getElementById('modal-proveedor-title').textContent = 'Nuevo Proveedor';
            form.reset();
            document.getElementById('modal-proveedor').classList.remove('hidden');
        }

        function editarTaller(id, nombre, contacto, telefono, email, whatsapp, direccion, proveedorId) {
            const form = document.getElementById('form-taller');
            form.action = '/rodados/talleres/' + id;
            document.getElementById('taller-method').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('modal-taller-title').textContent = 'Editar Taller';
            form.querySelector('[name="nombre"]').value = nombre;
            form.querySelector('[name="contacto"]').value = contacto;
            form.querySelector('[name="telefono"]').value = telefono;
            form.querySelector('[name="email"]').value = email;
            form.querySelector('[name="whatsapp"]').value = whatsapp;
            form.querySelector('[name="direccion"]').value = direccion;
            form.querySelector('[name="proveedor_id"]').value = proveedorId;
            document.getElementById('modal-taller').classList.remove('hidden');
        }

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

        // Toast notifications
        function dismissToast(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('toast-enter');
                el.classList.add('toast-exit');
                setTimeout(() => el.remove(), 300);
            }
        }

        // Auto-dismiss toasts after 4 seconds
        document.querySelectorAll('[id^="toast-"]').forEach(toast => {
            setTimeout(() => dismissToast(toast.id), 4000);
        });
    </script>

    <style>
        .animate-in { animation: fadeSlideIn 0.3s ease-out; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .modal-backdrop { animation: modalFadeIn 0.2s ease-out; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { animation: modalSlideIn 0.25s ease-out; }
        @keyframes modalSlideIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .modal-scroll::-webkit-scrollbar-thumb { background: rgba(63, 63, 70, 0.5); border-radius: 3px; }
        .modal-scroll { scrollbar-width: thin; scrollbar-color: rgba(63, 63, 70, 0.5) transparent; }
        @media (max-width: 768px) { .modal-backdrop { padding-left: 1rem !important; padding-top: 1rem !important; } }

        /* Toast notifications */
        .toast-enter { animation: toastSlideIn 0.4s cubic-bezier(0.21, 1.02, 0.73, 1) forwards; }
        .toast-exit { animation: toastSlideOut 0.3s ease-in forwards; }
        @keyframes toastSlideIn { from { opacity: 0; transform: translateX(100%) scale(0.95); } to { opacity: 1; transform: translateX(0) scale(1); } }
        @keyframes toastSlideOut { from { opacity: 1; transform: translateX(0) scale(1); } to { opacity: 0; transform: translateX(100%) scale(0.95); } }
        .toast-progress { animation: toastProgress 4s linear forwards; }
        @keyframes toastProgress { from { width: 100%; } to { width: 0%; } }
    </style>
</x-administrative-layout>
