<div>
    {{-- ═══════════════════════════════════════════════ --}}
    {{--  HEADER                                         --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                <svg class="w-7 h-7 text-blue-400" viewBox="0 0 229.98 229.98" fill="currentColor">
                    <path d="M223.211,127.002c-0.717,0-1.451,0.102-2.185,0.304l-8.301,2.286l-8.618-20.995c-2.441-5.948-9.659-10.787-16.089-10.787h-27.84V81.719c0-5.972-1.846-15.328-4.114-20.855l-1.391-3.388h1.054c4.819,0,8.74-3.921,8.74-8.74v-4.894c0-4.128-2.847-7.125-6.769-7.125c-0.717,0-1.451,0.103-2.185,0.304l-8.3,2.286l-8.619-20.995c-2.441-5.948-9.659-10.787-16.089-10.787H41.846c-6.429,0-13.646,4.839-16.089,10.787l-8.607,20.967l-8.195-2.257c-0.733-0.202-1.469-0.305-2.185-0.305C2.847,36.717,0,39.713,0,43.842v4.894c0,4.819,3.921,8.74,8.74,8.74h0.939l-1.391,3.388c-2.269,5.525-4.114,14.88-4.114,20.855v41.71c0,4.819,3.921,8.74,8.74,8.74h11.417c4.819,0,8.74-3.921,8.74-8.74v-10.416h56.384l-6.794,16.55l-8.196-2.258c-0.733-0.202-1.468-0.304-2.185-0.304c-3.922,0-6.769,2.997-6.769,7.125v4.894c0,4.819,3.921,8.74,8.74,8.74h0.939l-1.392,3.389c-2.268,5.525-4.114,14.88-4.114,20.855v41.71c0,4.819,3.921,8.74,8.74,8.74h11.416c4.819,0,8.74-3.921,8.74-8.74v-10.416h98.212v10.416c0,4.819,3.921,8.74,8.74,8.74h11.415c4.819,0,8.74-3.921,8.74-8.74v-41.71c0-5.975-1.846-15.33-4.114-20.855l-1.391-3.389h1.055c4.819,0,8.74-3.921,8.74-8.74v-4.894C229.98,129.998,227.133,127.002,223.211,127.002z M143.357,81.011v11.886c0,1.923-1.573,3.496-3.496,3.496h-24.767c-1.923,0-3.496-1.573-3.496-3.496V81.011c0-1.923,1.573-3.496,3.496-3.496h24.767C141.784,77.515,143.357,79.088,143.357,81.011z M52.521,92.897c0,1.923-1.573,3.496-3.496,3.496H24.259c-1.923,0-3.496-1.573-3.496-3.496V81.011c0-1.923,1.573-3.496,3.496-3.496h24.767c1.923,0,3.496,1.573,3.496,3.496V92.897z M27.755,59.197c-3.846,0-5.797-2.911-4.337-6.469l13.036-31.757c1.461-3.558,5.802-6.469,9.647-6.469h72.149c3.846,0,8.188,2.911,9.647,6.469l13.038,31.757c1.46,3.558-0.491,6.469-4.337,6.469H27.755z M88.929,143.013l13.037-31.757c1.46-3.558,5.802-6.469,9.647-6.469h72.149c3.846,0,8.188,2.911,9.648,6.469l13.036,31.757c1.461,3.558-0.491,6.469-4.337,6.469H93.266C89.42,149.482,87.469,146.571,88.929,143.013z M118.033,183.182c0,1.923-1.573,3.496-3.496,3.496H89.77c-1.923,0-3.496-1.573-3.496-3.496v-11.886c0-1.923,1.573-3.496,3.496-3.496h24.768c1.923,0,3.496,1.573,3.496,3.496V183.182z M208.867,183.182c0,1.923-1.573,3.496-3.496,3.496h-24.766c-1.923,0-3.496-1.573-3.496-3.496v-11.886c0-1.923,1.573-3.496,3.496-3.496h24.766c1.923,0,3.496,1.573,3.496,3.496V183.182z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Flotas Vehiculares</h1>
                <p class="text-gray-400 text-sm">Gestiona los vehículos de tu flota</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->hasAnyRole(['clientadmin', 'clientsupervisor']))
                <button wire:click="abrirModalCrear"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition-all shadow-lg shadow-blue-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Crear Patrulla
                </button>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  COLLAPSIBLE: Gestionar Sistemas & Documentos   --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if(auth()->user()->hasAnyRole(['clientadmin', 'clientsupervisor']))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
        {{-- Sistemas Panel --}}
        <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 shadow-xl overflow-hidden">
            <button wire:click="togglePanelSistemas" type="button"
                    class="flex items-center justify-between w-full px-5 py-3 hover:bg-zinc-700/30 transition-colors">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="text-sm font-medium text-gray-300">Gestionar Sistemas</span>
                    <span class="text-xs bg-zinc-700 text-gray-400 px-2 py-0.5 rounded-full">{{ $sistemas->count() }}</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 {{ $mostrarPanelSistemas ? '' : '-rotate-90' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @if($mostrarPanelSistemas)
            <div class="px-5 pb-4 border-t border-zinc-700/50">
                {{-- Form --}}
                <div class="mt-3 flex flex-wrap gap-2 items-end">
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-[11px] text-gray-400 mb-1">Nombre *</label>
                        <input type="text" wire:model="sistemaNombre" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" placeholder="Nombre del sistema">
                    </div>
                    <div class="flex-1 min-w-[120px]">
                        <label class="block text-[11px] text-gray-400 mb-1">Link</label>
                        <input type="text" wire:model="sistemaLink" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" placeholder="URL">
                    </div>
                    <div class="flex-1 min-w-[120px]">
                        <label class="block text-[11px] text-gray-400 mb-1">Correo</label>
                        <input type="email" wire:model="sistemaCorreo" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" placeholder="correo@...">
                    </div>
                    <div class="flex-1 min-w-[100px]">
                        <label class="block text-[11px] text-gray-400 mb-1">Teléfono</label>
                        <input type="text" wire:model="sistemaTelefono" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" placeholder="Tel.">
                    </div>
                    <div class="flex gap-1">
                        <button wire:click="guardarSistema" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition-all" title="{{ $sistemaEditId ? 'Actualizar' : 'Crear' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        @if($sistemaEditId)
                        <button wire:click="cancelarEdicionSistema" class="bg-zinc-700 hover:bg-zinc-600 text-white px-3 py-1.5 rounded-lg text-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
                @error('sistemaNombre')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                {{-- List --}}
                <div class="mt-3 space-y-1 max-h-40 overflow-y-auto custom-scrollbar">
                    @foreach($sistemas as $sis)
                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-zinc-700/30 transition-colors group text-sm">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <span class="text-gray-200 font-medium truncate">{{ $sis->nombre }}</span>
                            @if($sis->link)<span class="text-gray-500 text-xs truncate hidden sm:inline">{{ $sis->link }}</span>@endif
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="editarSistema({{ $sis->id }})" class="text-blue-400 hover:text-blue-300 p-1" title="Editar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="eliminarSistema({{ $sis->id }})" wire:confirm="¿Eliminar este sistema?" class="text-red-400 hover:text-red-300 p-1" title="Eliminar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Documentos Panel --}}
        <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 shadow-xl overflow-hidden">
            <button wire:click="togglePanelDocumentos" type="button"
                    class="flex items-center justify-between w-full px-5 py-3 hover:bg-zinc-700/30 transition-colors">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-sm font-medium text-gray-300">Gestionar Tipos de Documento</span>
                    <span class="text-xs bg-zinc-700 text-gray-400 px-2 py-0.5 rounded-full">{{ $documentos->count() }}</span>
                </div>
                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 {{ $mostrarPanelDocumentos ? '' : '-rotate-90' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @if($mostrarPanelDocumentos)
            <div class="px-5 pb-4 border-t border-zinc-700/50">
                {{-- Form --}}
                <div class="mt-3 flex flex-wrap gap-2 items-end">
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-[11px] text-gray-400 mb-1">Nombre *</label>
                        <input type="text" wire:model="documentoNombre" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" placeholder="Tipo de documento">
                    </div>
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-[11px] text-gray-400 mb-1">Descripción</label>
                        <input type="text" wire:model="documentoDescripcion" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-1.5 text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30" placeholder="Descripción opcional">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" wire:model="documentoActivo" class="rounded bg-zinc-700 border-zinc-600 text-blue-500 focus:ring-blue-500/30">
                            <span class="text-xs text-gray-400">Activo</span>
                        </label>
                    </div>
                    <div class="flex gap-1">
                        <button wire:click="guardarDocumento" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition-all" title="{{ $documentoEditId ? 'Actualizar' : 'Crear' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        @if($documentoEditId)
                        <button wire:click="cancelarEdicionDocumento" class="bg-zinc-700 hover:bg-zinc-600 text-white px-3 py-1.5 rounded-lg text-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
                @error('documentoNombre')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                {{-- List --}}
                <div class="mt-3 space-y-1 max-h-40 overflow-y-auto custom-scrollbar">
                    @foreach($documentos as $doc)
                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-zinc-700/30 transition-colors group text-sm">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <span class="text-gray-200 font-medium truncate">{{ $doc->nombre }}</span>
                            @if($doc->descripcion)<span class="text-gray-500 text-xs truncate hidden sm:inline">{{ $doc->descripcion }}</span>@endif
                            @if(!$doc->activo)<span class="text-xs bg-red-500/10 text-red-400 border border-red-500/20 px-1.5 py-0.5 rounded-full">Inactivo</span>@endif
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="editarDocumento({{ $doc->id }})" class="text-blue-400 hover:text-blue-300 p-1" title="Editar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="eliminarDocumento({{ $doc->id }})" wire:confirm="¿Eliminar este tipo de documento?" class="text-red-400 hover:text-red-300 p-1" title="Eliminar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  ACTION BAR (filter toggle + search)            --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div class="flex items-center gap-2">
            <button wire:click="toggleFiltros"
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
                <input type="text" wire:model.live="search" placeholder="Buscar patrulla..."
                    class="pl-9 pr-3 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all w-56">
            </div>
            <span class="text-xs text-gray-500">{{ $totalPatrullas }} patrullas</span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  COLLAPSIBLE FILTERS PANEL                      --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($mostrarFiltros)
    <div class="mb-5 p-4 bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Estado</label>
                <select wire:model.live="estadoFilter"
                        class="w-full rounded-lg bg-zinc-900 border-zinc-700 text-gray-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                    <option value="">Todos</option>
                    <option value="operativa">Operativa</option>
                    <option value="disponible">Disponible</option>
                    <option value="en mantenimiento">En Mantenimiento</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-3">
            @if($search || $estadoFilter)
            <button wire:click="clearFilters" class="text-xs text-gray-500 hover:text-gray-300 transition">Limpiar filtros</button>
            @endif
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  TABLE                                          --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Dominio</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Marca</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Modelo</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Año</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Objetivo</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Observación</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700/30">
                    @forelse ($patrullas as $patrulla)
                        <tr class="group hover:bg-zinc-700/30 transition-colors duration-150">
                            <td class="px-5 py-4 text-sm font-medium text-gray-200 whitespace-nowrap">{{ $patrulla->patente }}</td>
                            <td class="px-5 py-4 text-sm text-gray-300">{{ $patrulla->marca }}</td>
                            <td class="px-5 py-4 text-sm text-gray-300">{{ $patrulla->modelo }}</td>
                            <td class="px-5 py-4 text-sm text-gray-400">{{ $patrulla->año ?? '—' }}</td>
                            {{-- ESTADO --}}
                            <td class="px-5 py-4">
                                @if ($editingEstadoId === $patrulla->id)
                                    <div class="flex items-center gap-1">
                                        <select wire:model="nuevoEstado" class="bg-zinc-800 border border-zinc-600 rounded-lg px-2 py-1 text-xs text-gray-200 focus:border-blue-500">
                                            <option value="operativa">Operativa</option>
                                            <option value="disponible">Disponible</option>
                                            <option value="en mantenimiento">En mant.</option>
                                        </select>
                                        <button wire:click="guardarEstado({{ $patrulla->id }})" class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded-lg transition-all"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                        <button wire:click="cancelarEdicion" class="bg-zinc-700 hover:bg-zinc-600 text-white p-1 rounded-lg transition-all"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        @php
                                            $badgeClass = match($patrulla->estado) {
                                                'operativa' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'disponible' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $badgeClass }}">
                                            {{ ucfirst($patrulla->estado) }}
                                        </span>
                                        <button wire:click="iniciarEdicionEstado({{ $patrulla->id }}, '{{ $patrulla->estado }}')" class="text-gray-500 hover:text-gray-300 transition-colors" title="Editar">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </td>
                            {{-- OBJETIVO --}}
                            <td class="px-5 py-4">
                                @if ($editingObjetivoId === $patrulla->id)
                                    <div class="flex items-center gap-1">
                                        <input type="text" wire:model="nuevoObjetivo" class="bg-zinc-800 border border-zinc-600 rounded-lg px-2 py-1 text-xs text-gray-200 w-24 focus:border-blue-500" placeholder="Objetivo">
                                        <button wire:click="guardarObjetivo({{ $patrulla->id }})" class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded-lg transition-all"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                        <button wire:click="cancelarEdicionObjetivo" class="bg-zinc-700 hover:bg-zinc-600 text-white p-1 rounded-lg transition-all"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <span class="max-w-[100px] truncate text-gray-300 text-xs" title="{{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? '' }}">{{ $patrulla->ultimoRegistroFlota->objetivo_servicio ?? '—' }}</span>
                                        <button wire:click="iniciarEdicionObjetivo({{ $patrulla->id }}, '{{ addslashes($patrulla->ultimoRegistroFlota->objetivo_servicio ?? '') }}')" class="text-gray-500 hover:text-gray-300 transition-colors flex-shrink-0" title="Editar">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </td>
                            {{-- OBSERVACION --}}
                            <td class="px-5 py-4">
                                @if ($editingObservacionId === $patrulla->id)
                                    <div class="flex items-center gap-1">
                                        <input type="text" wire:model="nuevaObservacion" class="bg-zinc-800 border border-zinc-600 rounded-lg px-2 py-1 text-xs text-gray-200 w-24 focus:border-blue-500" placeholder="Obs.">
                                        <button wire:click="guardarObservacion({{ $patrulla->id }})" class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded-lg transition-all"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                        <button wire:click="cancelarEdicionObservacion" class="bg-zinc-700 hover:bg-zinc-600 text-white p-1 rounded-lg transition-all"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <span class="max-w-[100px] truncate text-gray-300 text-xs" title="{{ $patrulla->ultimoRegistroFlota->observacion ?? '' }}">{{ $patrulla->ultimoRegistroFlota->observacion ?? '—' }}</span>
                                        <button wire:click="iniciarEdicionObservacion({{ $patrulla->id }}, '{{ addslashes($patrulla->ultimoRegistroFlota->observacion ?? '') }}')" class="text-gray-500 hover:text-gray-300 transition-colors flex-shrink-0" title="Editar">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </td>
                            {{-- ACCIONES --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if(auth()->user()->hasAnyRole(['clientadmin', 'clientsupervisor']))
                                        <button wire:click="abrirModalEditar({{ $patrulla->id }})" class="text-blue-400 hover:text-blue-300 p-1.5 transition-colors" title="Editar">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    @endif
                                    <button wire:click="abrirModal({{ $patrulla->id }})" class="text-blue-400 hover:text-blue-300 p-1.5 transition-colors" title="Documentación y Sistemas">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </button>
                                    <a href="{{ route('client.patrullas.location') }}" class="text-blue-400 hover:text-blue-300 p-1.5 transition-colors" title="Ubicación">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    <p class="font-medium">No se encontraron patrullas</p>
                                    <p class="text-sm mt-1">Intente con otros filtros o cree una nueva</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-zinc-700/50">
            {{ $patrullas->links() }}
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  TOAST                                          --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-zinc-900 border border-green-500/30 text-green-400 px-5 py-3 rounded-2xl shadow-2xl z-[9999] flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
             x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-4 right-4 bg-zinc-900 border border-red-500/30 text-red-400 px-5 py-3 rounded-2xl shadow-2xl z-[9999] flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  MODAL: View Patrulla Details                   --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($mostrarModal && $patrullaSeleccionada)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-hidden flex flex-col">
                <div class="bg-zinc-800/50 px-6 py-4 border-b border-zinc-700/50 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                {{ $patrullaSeleccionada->patente }}
                            </h2>
                            <p class="text-sm text-gray-400 mt-0.5">{{ $patrullaSeleccionada->marca }} {{ $patrullaSeleccionada->modelo }} {{ $patrullaSeleccionada->año ? '- '.$patrullaSeleccionada->año : '' }}</p>
                        </div>
                        <button wire:click="cerrarModal" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-700/50 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar space-y-6">
                    <div>
                        @livewire('flotas-vehiculares.sistema-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                    </div>
                    <div>
                        @livewire('flotas-vehiculares.documentacion-patrulla-listado', ['patrullaId' => $patrullaSeleccionada->id])
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  MODAL: Create Patrulla                         --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-md max-h-[85vh] overflow-hidden flex flex-col">
                <div class="bg-zinc-800/50 px-6 py-4 border-b border-zinc-700/50 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Crear Nueva Patrulla
                        </h2>
                        <button wire:click="cerrarModalCrear" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-700/50 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                    <form wire:submit.prevent="crearPatrulla" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Patente/Dominio *</label>
                            <input type="text" wire:model="patente" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 uppercase placeholder-gray-500" placeholder="Ej: ABC123">
                            @error('patente')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Marca *</label>
                                <input type="text" wire:model="marca" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Toyota">
                                @error('marca')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Modelo *</label>
                                <input type="text" wire:model="modelo" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Hilux">
                                @error('modelo')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Color</label>
                                <input type="text" wire:model="color" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Blanco">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Año</label>
                                <input type="number" wire:model="año" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="2023" min="1900" max="2100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Estado *</label>
                            <select wire:model="estado" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                                <option value="disponible">Disponible</option>
                                <option value="operativa">Operativa</option>
                                <option value="en mantenimiento">En Mantenimiento</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Observaciones</label>
                            <textarea wire:model="observaciones" rows="2" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Observaciones..."></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-zinc-700/50">
                            <button type="button" wire:click="cerrarModalCrear" class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Crear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  MODAL: Edit Patrulla                           --}}
    {{-- ═══════════════════════════════════════════════ --}}
    @if($mostrarModalEditar && $patrullaEditar)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-md max-h-[85vh] overflow-hidden flex flex-col">
                <div class="bg-zinc-800/50 px-6 py-4 border-b border-zinc-700/50 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Editar Patrulla
                            </h2>
                            <p class="text-sm text-gray-400 mt-0.5">{{ $patrullaEditar->patente }}</p>
                        </div>
                        <button wire:click="cerrarModalEditar" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-700/50 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                    <form wire:submit.prevent="actualizarPatrulla" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Patente/Dominio *</label>
                            <input type="text" wire:model="patente" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 uppercase placeholder-gray-500" placeholder="Ej: ABC123">
                            @error('patente')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Marca *</label>
                                <input type="text" wire:model="marca" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Toyota">
                                @error('marca')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Modelo *</label>
                                <input type="text" wire:model="modelo" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Hilux">
                                @error('modelo')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Color</label>
                                <input type="text" wire:model="color" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Blanco">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Año</label>
                                <input type="number" wire:model="año" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="2023" min="1900" max="2100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Estado *</label>
                            <select wire:model="estado" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30">
                                <option value="disponible">Disponible</option>
                                <option value="operativa">Operativa</option>
                                <option value="en mantenimiento">En Mantenimiento</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Observaciones</label>
                            <textarea wire:model="observaciones" rows="2" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Observaciones..."></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-zinc-700/50">
                            <button type="button" wire:click="cerrarModalEditar" class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all">Cancelar</button>
                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-lg shadow-amber-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════ --}}
    {{--  STYLES                                         --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #52525b; }
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #3f3f46 transparent; }

        select option { background-color: #18181b; color: #e4e4e7; }
    </style>
</div>
