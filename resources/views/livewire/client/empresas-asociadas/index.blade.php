<div>
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Clientes</h1>
                <p class="text-gray-400 text-sm">Empresas asociadas</p>
            </div>
        </div>
        <button wire:click="abrirModalCrear" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition-all shadow-lg shadow-blue-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Empresa
        </button>
    </div>

    {{-- ACTION BAR --}}
    <div class="flex items-center gap-2 mb-5">
        <div class="relative">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live="search" placeholder="Nombre de empresa..."
                class="pl-9 pr-3 py-2 bg-zinc-800 border border-zinc-700 rounded-xl text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all w-64">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-zinc-800/50 backdrop-blur rounded-xl border border-zinc-700/50 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-700/50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nombre</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700/30">
                    @forelse ($empresas as $empresa)
                        <tr class="group hover:bg-zinc-700/30 transition-colors duration-150">
                            <td class="px-5 py-4 text-sm font-medium text-gray-200">{{ $empresa->nombre }}</td>
                            <td class="px-5 py-4">
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="abrirModalEditar({{ $empresa->id }})" class="text-blue-400 hover:text-blue-300 p-1.5 transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-5 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <p class="font-medium">No se encontraron empresas asociadas</p>
                                    <p class="text-sm mt-1">Agrega una nueva empresa para comenzar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-zinc-700/50">
            {{ $empresas->links() }}
        </div>
    </div>

    {{-- TOASTS --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 bg-zinc-900 border border-green-500/30 text-green-400 px-5 py-3 rounded-2xl shadow-2xl z-[9999] flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-4 right-4 bg-zinc-900 border border-red-500/30 text-red-400 px-5 py-3 rounded-2xl shadow-2xl z-[9999] flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- MODAL CREAR --}}
    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-hidden flex flex-col">
                <div class="bg-zinc-800/50 px-6 py-4 border-b border-zinc-700/50 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Nueva Empresa Asociada
                        </h2>
                        <button wire:click="cerrarModalCrear" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-700/50 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto flex-1">
                    <form wire:submit.prevent="crearEmpresa" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre de la Empresa *</label>
                            <input type="text" wire:model="nombre" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Empresa ABC S.A.">
                            @error('nombre')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-zinc-700/50">
                            <button type="button" wire:click="cerrarModalCrear" class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all">Cancelar</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Crear Empresa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL EDITAR --}}
    @if($mostrarModalEditar && $empresaEditar)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-zinc-900 border border-zinc-700/50 rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-hidden flex flex-col">
                <div class="bg-zinc-800/50 px-6 py-4 border-b border-zinc-700/50 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Editar Empresa
                        </h2>
                        <button wire:click="cerrarModalEditar" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-zinc-700/50 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto flex-1">
                    <form wire:submit.prevent="actualizarEmpresa" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre de la Empresa *</label>
                            <input type="text" wire:model="nombre" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-2.5 text-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 placeholder-gray-500" placeholder="Ej: Empresa ABC S.A.">
                            @error('nombre')<span class="text-red-400 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-zinc-700/50">
                            <button type="button" wire:click="cerrarModalEditar" class="bg-zinc-700 hover:bg-zinc-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all">Cancelar</button>
                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-lg shadow-amber-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        select option { background-color: #18181b; color: #e4e4e7; }
    </style>
</div>
