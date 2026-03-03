@extends('layouts.cliente')

@section('title', 'Recorridos')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container { background: #1f2937; font-family: inherit; }
    .dark-map .leaflet-tile { filter: brightness(0.65) invert(1) contrast(3.5) hue-rotate(200deg) saturate(0.25) brightness(0.65); }
    .leaflet-control-zoom a { background-color: #374151 !important; color: #e5e7eb !important; border-color: #4b5563 !important; }
    .leaflet-control-zoom a:hover { background-color: #4b5563 !important; }
    .leaflet-popup-content-wrapper { background: #1f2937; color: #f3f4f6; border: 1px solid #3b82f6; border-radius: 12px; }
    .leaflet-popup-content { margin: 12px 16px; font-size: 13px; }
    .leaflet-popup-tip { background: #1f2937; }
    .custom-marker-start { background: #10b981; border: 3px solid #064e3b; border-radius: 50%; width: 16px; height: 16px; }
    .custom-marker-end { background: #ef4444; border: 3px solid #7f1d1d; border-radius: 50%; width: 16px; height: 16px; }
    .kml-dropzone { border: 2px dashed #4b5563; transition: all 0.2s; }
    .kml-dropzone.dragover { border-color: #3b82f6; background: rgba(59,130,246,0.1); }
</style>

<div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-600/20 to-emerald-400/10 rounded-xl border border-emerald-500/20">
                    <svg fill="currentColor" viewBox="0 0 217.205 217.205" class="w-7 h-7 text-emerald-400">
                        <path d="M167.631,101.102H49.574c-16.216,0-29.408-13.199-29.408-29.422c0-16.211,13.192-29.399,29.408-29.399h73.789 c4.143,0,7.5-3.358,7.5-7.5c0-4.142-3.357-7.5-7.5-7.5H49.574c-24.486,0-44.408,19.917-44.408,44.399 c0,24.494,19.922,44.422,44.408,44.422h118.057c16.216,0,29.408,13.199,29.408,29.423c0,16.211-13.192,29.399-29.408,29.399H93.205 c-4.142,0-7.5,3.358-7.5,7.5s3.358,7.5,7.5,7.5h74.426c24.486,0,44.408-19.917,44.408-44.399 C212.039,121.03,192.117,101.102,167.631,101.102z"/>
                        <path d="M48.516,130.001c-17.407,0-31.568,14.162-31.568,31.568c0,26.865,25.192,52.367,26.265,53.439 c1.407,1.407,3.314,2.197,5.304,2.197c1.989,0,3.897-0.79,5.304-2.197c1.072-1.073,26.263-26.574,26.263-53.439 C80.082,144.163,65.922,130.001,48.516,130.001z M48.516,198.357c-6.477-7.995-16.568-22.713-16.568-36.788 c0-9.136,7.433-16.568,16.568-16.568c9.135,0,16.566,7.433,16.566,16.568C65.082,175.644,54.991,190.362,48.516,198.357z"/>
                        <path d="M168.053,87.202c1.919,0,3.838-0.732,5.302-2.195c1.073-1.072,26.278-26.573,26.278-53.44 C199.633,14.161,185.466,0,168.053,0c-17.407,0-31.568,14.161-31.568,31.566c0,26.866,25.192,52.367,26.266,53.439 C164.214,86.47,166.133,87.202,168.053,87.202z M168.053,15c9.143,0,16.58,7.432,16.58,16.566c0,14.076-10.1,28.796-16.579,36.79 c-6.476-7.994-16.569-22.713-16.569-36.79C151.484,22.432,158.917,15,168.053,15z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Recorridos</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Administración de rutas y recorridos planificados</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <span class="text-xs text-gray-400">Total</span>
                    <span class="text-sm font-semibold text-gray-200">{{ $recorridos->count() }}</span>
                </div>
                @can('crear.recorridos-cliente')
                <button onclick="abrirModalCrear()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-blue-600/20">
                    <i class="bi bi-plus-lg mr-2"></i>Nuevo Recorrido
                </button>
                @endcan
            </div>
        </div>
    </div>

    @if(!$tienePersonal)
    <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
        <div class="flex items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill text-amber-400 text-lg"></i>
            <p class="text-sm text-amber-200">Debe tener un registro de personal asignado para crear y gestionar recorridos. Contacte al administrador.</p>
        </div>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div class="flex items-center gap-2">
            <button onclick="toggleFiltrosRecorridos()" id="btn-filtros-recorridos"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-300 hover:bg-zinc-700 hover:border-zinc-600 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filtros
            </button>
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="busqueda-recorrido" placeholder="Buscar recorrido..." oninput="aplicarFiltrosRecorridos()"
                    class="pl-9 pr-3 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all w-56">
            </div>
            <span class="text-xs text-gray-500" id="recorridos-count">{{ $recorridos->count() }} registros</span>
        </div>
    </div>

    <!-- Filtros Panel (collapsible) -->
    <div id="filtros-recorridos-panel" class="hidden mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Cliente</label>
                <select id="filtro-rec-empresa" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosRecorridos()">
                    <option value="">Todos los clientes</option>
                    @foreach($empresasDisponibles as $emp)
                        <option value="{{ $emp->nombre }}">{{ $emp->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Con ruta KML</label>
                <select id="filtro-rec-kml" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosRecorridos()">
                    <option value="">Todos</option>
                    <option value="si">Con ruta cargada</option>
                    <option value="no">Sin ruta</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vel. Máx definida</label>
                <select id="filtro-rec-velmax" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosRecorridos()">
                    <option value="">Todos</option>
                    <option value="si">Con vel. máx</option>
                    <option value="no">Sin vel. máx</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Ordenar por</label>
                <select id="filtro-rec-orden" class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" onchange="aplicarFiltrosRecorridos()">
                    <option value="reciente">Más reciente</option>
                    <option value="nombre">Nombre (A-Z)</option>
                    <option value="longitud">Mayor longitud</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-3">
            <button onclick="limpiarFiltrosRecorridos()" class="text-xs text-gray-500 hover:text-gray-300 transition">Limpiar filtros</button>
        </div>
    </div>

    <!-- Tabla de Recorridos -->
    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nombre</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Cliente</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Objetivos</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Longitud</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vel. Máx</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Dur. Prom.</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700/30">
                    @forelse($recorridos as $recorrido)
                    <tr class="hover:bg-zinc-700/30 transition-colors duration-150 cursor-pointer recorrido-row"
                        onclick="verRecorrido({{ $recorrido->id }})"
                        data-nombre="{{ strtolower($recorrido->nombre) }}"
                        data-empresa="{{ $recorrido->empresaAsociada->nombre ?? '' }}"
                        data-objetivos="{{ strtolower($recorrido->objetivos ?? '') }}"
                        data-kml="{{ $recorrido->longitud_mts ? 'si' : 'no' }}"
                        data-velmax="{{ $recorrido->velocidadmax_permitida ? 'si' : 'no' }}"
                        data-longitud="{{ $recorrido->longitud_mts ?? 0 }}"
                        data-fecha="{{ $recorrido->created_at ? $recorrido->created_at->timestamp : 0 }}">
                        <td class="px-5 py-4">
                            <div class="font-medium text-gray-200">{{ $recorrido->nombre }}</div>
                            @if($recorrido->descripcion)
                            <div class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">{{ $recorrido->descripcion }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                {{ $recorrido->empresaAsociada->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-300 max-w-xs truncate">{{ $recorrido->objetivos ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-300">
                            @if($recorrido->longitud_mts)
                                {{ number_format($recorrido->longitud_mts / 1000, 2) }} km
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-300">
                            @if($recorrido->velocidadmax_permitida)
                                {{ $recorrido->velocidadmax_permitida }} km/h
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-300">
                            @if($recorrido->duracion_promedio)
                                {{ $recorrido->duracion_promedio }} min
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="verRecorrido({{ $recorrido->id }})" class="p-2 text-gray-400 hover:text-blue-400 transition-colors" title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @can('editar.recorridos-cliente')
                                <button onclick="editarRecorrido({{ $recorrido->id }})" class="p-2 text-gray-400 hover:text-amber-400 transition-colors" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @endcan
                                @can('eliminar.recorridos-cliente')
                                <button onclick="confirmarEliminar({{ $recorrido->id }}, '{{ addslashes($recorrido->nombre) }}')" class="p-2 text-gray-400 hover:text-red-400 transition-colors" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="bi bi-map text-4xl mb-3 block"></i>
                                <p class="font-medium">No hay recorridos registrados</p>
                                <p class="text-sm mt-1">Cree un nuevo recorrido para comenzar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Crear/Editar Recorrido -->
<div id="modal-recorrido" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center overflow-y-auto" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1rem);">
    <div class="w-full max-w-2xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden my-4">
        <div class="px-6 py-4 border-b border-zinc-700/50 bg-zinc-800/50">
            <h3 class="text-lg font-semibold text-gray-100" id="modal-recorrido-title">Nuevo Recorrido</h3>
        </div>
        <form id="form-recorrido" class="p-6 space-y-4" enctype="multipart/form-data">
            <input type="hidden" id="recorrido-id" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre *</label>
                    <input type="text" id="rec-nombre" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Nombre del recorrido">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Cliente (Empresa Asociada) *</label>
                    <select id="rec-empresa" required class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        <option value="">Seleccionar...</option>
                        @foreach($empresasDisponibles as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Descripción</label>
                <textarea id="rec-descripcion" rows="2" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Descripción del recorrido"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Objetivos</label>
                <input type="text" id="rec-objetivos" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Objetivos cubiertos por la ruta">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Velocidad Máx. Permitida (km/h)</label>
                    <input type="number" id="rec-velmax" min="1" max="300" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Ej: 60">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Duración Promedio (min)</label>
                    <input type="number" id="rec-duracion" min="1" class="w-full rounded-xl bg-zinc-800 border border-zinc-700 text-gray-200 px-3.5 py-2.5 text-sm placeholder-gray-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Ej: 45">
                </div>
            </div>
            <!-- KML Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Archivo KML de la Ruta</label>
                <div id="kml-dropzone" class="kml-dropzone rounded-xl p-6 text-center cursor-pointer" onclick="document.getElementById('kml-file-input').click()">
                    <input type="file" id="kml-file-input" accept=".kml,.KML,.kmz,.KMZ" class="hidden" onchange="handleKmlFile(this)">
                    <i class="bi bi-cloud-arrow-up text-3xl text-gray-500 mb-2 block"></i>
                    <p class="text-sm text-gray-400" id="kml-filename">Arrastrar archivo .kml o .kmz o hacer clic para seleccionar</p>
                    <p class="text-xs text-gray-600 mt-1">Máximo 5MB</p>
                </div>
                <div id="kml-info" class="hidden mt-3 p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-400 font-medium">Ruta cargada</span>
                        <button type="button" onclick="clearKml()" class="text-xs text-red-400 hover:text-red-300"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="mt-1 text-gray-300" id="kml-stats"></div>
                </div>
            </div>
            <!-- Map Preview -->
            <div id="map-preview-container" class="hidden">
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Vista previa de la ruta</label>
                <div id="map-preview" class="dark-map h-64 rounded-xl border border-zinc-700/50 overflow-hidden"></div>
            </div>
        </form>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-end gap-3">
            <button onclick="cerrarModal('modal-recorrido')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
            <button onclick="guardarRecorrido()" class="px-4 py-2.5 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20" id="btn-guardar-recorrido">Guardar</button>
        </div>
    </div>
</div>

<!-- Modal: Ver Detalles -->
<div id="modal-ver-recorrido" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center overflow-y-auto" style="padding-left: calc(var(--fb-sidebar-width, 240px) + 1rem); padding-top: calc(var(--fb-topbar-height, 60px) + 1rem);">
    <div class="w-full max-w-3xl bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden my-4">
        <div class="px-6 py-4 border-b border-zinc-700/50 bg-zinc-800/50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-100" id="ver-recorrido-nombre">Detalles del Recorrido</h3>
            <button onclick="cerrarModal('modal-ver-recorrido')" class="text-gray-400 hover:text-gray-200 transition-colors"><i class="bi bi-x-lg text-lg"></i></button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-xs text-gray-400">Longitud</div>
                    <div class="text-sm font-semibold text-gray-200 mt-1" id="ver-longitud">-</div>
                </div>
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-xs text-gray-400">Vel. Máx</div>
                    <div class="text-sm font-semibold text-gray-200 mt-1" id="ver-velmax">-</div>
                </div>
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-xs text-gray-400">Duración Prom.</div>
                    <div class="text-sm font-semibold text-gray-200 mt-1" id="ver-duracion">-</div>
                </div>
                <div class="p-3 bg-zinc-800/50 rounded-xl border border-zinc-700/30">
                    <div class="text-xs text-gray-400">Cliente</div>
                    <div class="text-sm font-semibold text-indigo-400 mt-1" id="ver-empresa">-</div>
                </div>
            </div>
            <div class="mb-4" id="ver-descripcion-container">
                <div class="text-xs text-gray-400 mb-1">Descripción</div>
                <div class="text-sm text-gray-300" id="ver-descripcion">-</div>
            </div>
            <div class="mb-4" id="ver-objetivos-container">
                <div class="text-xs text-gray-400 mb-1">Objetivos</div>
                <div class="text-sm text-gray-300" id="ver-objetivos">-</div>
            </div>
            <div id="ver-map-container" class="dark-map h-80 rounded-xl border border-zinc-700/50 overflow-hidden"></div>
        </div>
    </div>
</div>

<!-- Modal: Confirmar Eliminación -->
<div id="modal-eliminar" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center" style="padding-left: calc(var(--fb-sidebar-width, 240px));">
    <div class="w-full max-w-sm bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-exclamation-triangle text-red-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-100 mb-2">Eliminar Recorrido</h3>
            <p class="text-sm text-gray-400">¿Está seguro de eliminar el recorrido "<span id="eliminar-nombre" class="text-gray-200 font-medium"></span>"?</p>
            <input type="hidden" id="eliminar-id">
        </div>
        <div class="px-6 py-4 border-t border-zinc-700/50 flex justify-center gap-3">
            <button onclick="cerrarModal('modal-eliminar')" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 transition-colors">Cancelar</button>
            <button onclick="eliminarRecorrido()" class="px-4 py-2.5 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all">Eliminar</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-success" class="fixed z-[100] max-w-sm w-full hidden" style="top: calc(var(--fb-topbar-height, 60px) + 1rem); right: 1.5rem;">
    <div class="bg-zinc-900 border border-green-500/30 rounded-2xl shadow-2xl shadow-green-900/20 p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm text-gray-200" id="toast-message">Operación exitosa</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let previewMap = null, viewMap = null, kmlData = null, kmlFile = null;

function showToast(msg) {
    const t = document.getElementById('toast-success');
    document.getElementById('toast-message').textContent = msg;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 4000);
}

function cerrarModal(id) {
    document.getElementById(id).classList.add('hidden');
    if (id === 'modal-ver-recorrido' && viewMap) { viewMap.remove(); viewMap = null; }
    if (id === 'modal-recorrido' && previewMap) { previewMap.remove(); previewMap = null; }
}

// Close on backdrop
document.querySelectorAll('#modal-recorrido, #modal-ver-recorrido, #modal-eliminar').forEach(m => {
    m.addEventListener('click', function(e) { if (e.target === this) cerrarModal(this.id); });
});

// Drag and drop
const dropzone = document.getElementById('kml-dropzone');
if (dropzone) {
    ['dragover','dragenter'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(e => dropzone.addEventListener(e, ev => { ev.preventDefault(); dropzone.classList.remove('dragover'); }));
    dropzone.addEventListener('drop', ev => {
        if (ev.dataTransfer.files.length) {
            const input = document.getElementById('kml-file-input');
            input.files = ev.dataTransfer.files;
            handleKmlFile(input);
        }
    });
}

function handleKmlFile(input) {
    if (!input.files.length) return;
    kmlFile = input.files[0];
    document.getElementById('kml-filename').textContent = kmlFile.name;

    const formData = new FormData();
    formData.append('kml_file', kmlFile);

    fetch('{{ route("client.recorridos.import-kml") }}', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data && data.data.waypoints && data.data.waypoints.length > 0) {
            kmlData = data.data;
            document.getElementById('kml-info').classList.remove('hidden');
            document.getElementById('kml-stats').textContent = `${kmlData.waypoints.length} waypoints | ${(kmlData.longitud_mts/1000).toFixed(2)} km`;
            showPreviewMap(kmlData.waypoints);
        } else {
            const msg = data.message || 'No se pudieron extraer waypoints del archivo. Verifique que sea un KML válido con coordenadas (LineString o Point).';
            alert(msg);
        }
    })
    .catch(() => alert('Error al procesar el archivo KML'));
}

function clearKml() {
    kmlData = null; kmlFile = null;
    document.getElementById('kml-file-input').value = '';
    document.getElementById('kml-filename').textContent = 'Arrastar archivo .kml o hacer clic para seleccionar';
    document.getElementById('kml-info').classList.add('hidden');
    document.getElementById('map-preview-container').classList.add('hidden');
    if (previewMap) { previewMap.remove(); previewMap = null; }
}

function showPreviewMap(waypoints) {
    if (!waypoints || !waypoints.length) return;
    document.getElementById('map-preview-container').classList.remove('hidden');
    if (previewMap) previewMap.remove();

    setTimeout(() => {
        previewMap = L.map('map-preview', { zoomControl: true }).setView([waypoints[0].lat, waypoints[0].lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(previewMap);
        const latlngs = waypoints.map(w => [w.lat, w.lng]);
        const polyline = L.polyline(latlngs, { color: '#3b82f6', weight: 4, opacity: 0.8 }).addTo(previewMap);
        previewMap.fitBounds(polyline.getBounds(), { padding: [30, 30] });
        L.marker(latlngs[0], { icon: L.divIcon({ className: 'custom-marker-start', iconSize: [16,16], iconAnchor: [8,8] }) }).addTo(previewMap);
        L.marker(latlngs[latlngs.length-1], { icon: L.divIcon({ className: 'custom-marker-end', iconSize: [16,16], iconAnchor: [8,8] }) }).addTo(previewMap);
    }, 100);
}

function resetForm() {
    document.getElementById('recorrido-id').value = '';
    document.getElementById('rec-nombre').value = '';
    document.getElementById('rec-empresa').value = '';
    document.getElementById('rec-descripcion').value = '';
    document.getElementById('rec-objetivos').value = '';
    document.getElementById('rec-velmax').value = '';
    document.getElementById('rec-duracion').value = '';
    clearKml();
}

function abrirModalCrear() {
    resetForm();
    document.getElementById('modal-recorrido-title').textContent = 'Nuevo Recorrido';
    document.getElementById('modal-recorrido').classList.remove('hidden');
}

function editarRecorrido(id) {
    fetch(`{{ url('client/recorridos') }}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
    .then(r => r.json())
    .then(data => {
        const rec = data.recorrido;
        document.getElementById('recorrido-id').value = rec.id;
        document.getElementById('rec-nombre').value = rec.nombre;
        document.getElementById('rec-empresa').value = rec.empresa_asociada_id;
        document.getElementById('rec-descripcion').value = rec.descripcion || '';
        document.getElementById('rec-objetivos').value = rec.objetivos || '';
        document.getElementById('rec-velmax').value = rec.velocidadmax_permitida || '';
        document.getElementById('rec-duracion').value = rec.duracion_promedio || '';
        document.getElementById('modal-recorrido-title').textContent = 'Editar Recorrido';
        // Show existing waypoints
        const wp = rec.waypoints;
        if (wp) {
            const points = wp.points || wp;
            if (Array.isArray(points) && points.length) {
                kmlData = { waypoints: points, longitud_mts: rec.longitud_mts };
                document.getElementById('kml-info').classList.remove('hidden');
                document.getElementById('kml-stats').textContent = `${points.length} waypoints | ${(rec.longitud_mts/1000).toFixed(2)} km`;
                showPreviewMap(points);
            }
        }
        document.getElementById('modal-recorrido').classList.remove('hidden');
    });
}

function guardarRecorrido() {
    const id = document.getElementById('recorrido-id').value;
    const formData = new FormData();
    formData.append('nombre', document.getElementById('rec-nombre').value);
    formData.append('empresa_asociada_id', document.getElementById('rec-empresa').value);
    formData.append('descripcion', document.getElementById('rec-descripcion').value);
    formData.append('objetivos', document.getElementById('rec-objetivos').value);
    formData.append('velocidadmax_permitida', document.getElementById('rec-velmax').value);
    formData.append('duracion_promedio', document.getElementById('rec-duracion').value);
    if (kmlFile) formData.append('kml_file', kmlFile);

    const url = id ? `{{ url('client/recorridos') }}/${id}` : '{{ route("client.recorridos.store") }}';
    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            cerrarModal('modal-recorrido');
            location.reload();
        } else {
            alert(data.error || 'Error al guardar');
        }
    })
    .catch(() => alert('Error de conexión'));
}

function verRecorrido(id) {
    fetch(`{{ url('client/recorridos') }}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
    .then(r => r.json())
    .then(data => {
        const rec = data.recorrido;
        document.getElementById('ver-recorrido-nombre').textContent = rec.nombre;
        document.getElementById('ver-longitud').textContent = rec.longitud_mts ? (rec.longitud_mts/1000).toFixed(2) + ' km' : '-';
        document.getElementById('ver-velmax').textContent = rec.velocidadmax_permitida ? rec.velocidadmax_permitida + ' km/h' : '-';
        document.getElementById('ver-duracion').textContent = rec.duracion_promedio ? rec.duracion_promedio + ' min' : '-';
        document.getElementById('ver-empresa').textContent = rec.empresa_asociada ? rec.empresa_asociada.nombre : '-';
        document.getElementById('ver-descripcion').textContent = rec.descripcion || 'Sin descripción';
        document.getElementById('ver-objetivos').textContent = rec.objetivos || 'Sin objetivos definidos';

        document.getElementById('modal-ver-recorrido').classList.remove('hidden');

        // Init map
        const wp = rec.waypoints;
        const points = wp ? (wp.points || wp) : [];
        if (Array.isArray(points) && points.length) {
            setTimeout(() => {
                if (viewMap) viewMap.remove();
                viewMap = L.map('ver-map-container', { zoomControl: true }).setView([points[0].lat, points[0].lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(viewMap);
                const latlngs = points.map(w => [w.lat, w.lng]);
                const polyline = L.polyline(latlngs, { color: '#3b82f6', weight: 4, opacity: 0.8 }).addTo(viewMap);
                viewMap.fitBounds(polyline.getBounds(), { padding: [40, 40] });
                L.marker(latlngs[0], { icon: L.divIcon({ className: 'custom-marker-start', iconSize: [16,16], iconAnchor: [8,8] }) })
                    .bindPopup('<strong class="text-emerald-400">Inicio</strong>').addTo(viewMap);
                L.marker(latlngs[latlngs.length-1], { icon: L.divIcon({ className: 'custom-marker-end', iconSize: [16,16], iconAnchor: [8,8] }) })
                    .bindPopup('<strong class="text-red-400">Fin</strong>').addTo(viewMap);
                // Info control
                const info = L.control({ position: 'bottomleft' });
                info.onAdd = function() {
                    const div = L.DomUtil.create('div');
                    div.style.cssText = 'background:rgba(24,24,27,0.95);border:1px solid #3f3f46;border-radius:8px;padding:8px 12px;font-size:12px;color:#d4d4d8;';
                    div.innerHTML = `<div style="font-weight:600;color:#60a5fa;margin-bottom:4px;">${rec.nombre}</div>` +
                        `<div>Longitud: <span style="color:#f4f4f5;font-weight:500;">${rec.longitud_mts ? (rec.longitud_mts/1000).toFixed(2) + ' km' : '-'}</span></div>` +
                        `<div>Waypoints: <span style="color:#f4f4f5;font-weight:500;">${points.length}</span></div>`;
                    return div;
                };
                info.addTo(viewMap);
            }, 150);
        }
    });
}

// ===== Filtros dinámicos =====
function toggleFiltrosRecorridos() {
    const panel = document.getElementById('filtros-recorridos-panel');
    panel.classList.toggle('hidden');
    const btn = document.getElementById('btn-filtros-recorridos');
    if (!panel.classList.contains('hidden')) {
        btn.classList.add('bg-zinc-700', 'border-zinc-600');
        btn.classList.remove('bg-zinc-800', 'border-zinc-700');
    } else {
        btn.classList.remove('bg-zinc-700', 'border-zinc-600');
        btn.classList.add('bg-zinc-800', 'border-zinc-700');
    }
}

function limpiarFiltrosRecorridos() {
    document.getElementById('filtro-rec-empresa').value = '';
    document.getElementById('filtro-rec-kml').value = '';
    document.getElementById('filtro-rec-velmax').value = '';
    document.getElementById('filtro-rec-orden').value = 'reciente';
    document.getElementById('busqueda-recorrido').value = '';
    aplicarFiltrosRecorridos();
}

function aplicarFiltrosRecorridos() {
    const busqueda = document.getElementById('busqueda-recorrido').value.toLowerCase().trim();
    const empresa = document.getElementById('filtro-rec-empresa').value;
    const kml = document.getElementById('filtro-rec-kml').value;
    const velmax = document.getElementById('filtro-rec-velmax').value;
    const orden = document.getElementById('filtro-rec-orden').value;

    const filas = Array.from(document.querySelectorAll('.recorrido-row'));
    let visibles = 0;

    filas.forEach(fila => {
        const matchBusqueda = busqueda === '' ||
            fila.dataset.nombre.includes(busqueda) ||
            fila.dataset.objetivos.includes(busqueda) ||
            fila.dataset.empresa.toLowerCase().includes(busqueda);
        const matchEmpresa = empresa === '' || fila.dataset.empresa === empresa;
        const matchKml = kml === '' || fila.dataset.kml === kml;
        const matchVelmax = velmax === '' || fila.dataset.velmax === velmax;

        const mostrar = matchBusqueda && matchEmpresa && matchKml && matchVelmax;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    // Ordenar
    const tbody = filas[0]?.parentElement;
    if (tbody && filas.length > 0) {
        const sorted = filas.slice().sort((a, b) => {
            if (orden === 'nombre') return a.dataset.nombre.localeCompare(b.dataset.nombre);
            if (orden === 'longitud') return parseFloat(b.dataset.longitud) - parseFloat(a.dataset.longitud);
            return parseFloat(b.dataset.fecha) - parseFloat(a.dataset.fecha); // reciente
        });
        sorted.forEach(row => tbody.appendChild(row));
    }

    const el = document.getElementById('recorridos-count');
    if (el) el.textContent = visibles + ' registro' + (visibles !== 1 ? 's' : '');
}

function confirmarEliminar(id, nombre) {
    document.getElementById('eliminar-id').value = id;
    document.getElementById('eliminar-nombre').textContent = nombre;
    document.getElementById('modal-eliminar').classList.remove('hidden');
}

function eliminarRecorrido() {
    const id = document.getElementById('eliminar-id').value;
    fetch(`{{ url('client/recorridos') }}/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            cerrarModal('modal-eliminar');
            location.reload();
        } else {
            alert(data.error || 'Error al eliminar');
        }
    });
}
</script>
@endpush
