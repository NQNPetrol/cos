@extends('layouts.cliente')

@section('title', 'Checklist de Vehículos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Checklist de Vehículos</h1>
            <p class="text-gray-400 mt-1">Registra el estado de los vehículos de patrulla</p>
        </div>
        <button onclick="document.getElementById('nuevo-checklist-modal').classList.remove('hidden')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Checklist
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Historial de Checklists -->
    <div class="bg-zinc-800 border border-zinc-700 rounded-lg overflow-hidden">
        <div class="p-4 border-b border-zinc-700">
            <h3 class="text-lg font-semibold text-gray-100">Historial de Checklists</h3>
        </div>
        @if($checklists->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-700">
                <thead class="bg-zinc-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Vehículo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Auxilios</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Starlink</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Cámaras/DVR</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Parabrisas</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Luces</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Balizas</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Antivuelco</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @foreach($checklists as $checklist)
                    <tr class="hover:bg-zinc-700/50 transition">
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $checklist->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-200 font-medium">{{ $checklist->patrulla->patente ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $checklist->ruedas_auxilio < 2 ? 'bg-red-900/40 text-red-400 border border-red-800' : 'bg-green-900/40 text-green-400 border border-green-800' }}">
                                {{ $checklist->ruedas_auxilio }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($checklist->antena_starlink)
                                <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($checklist->camaras_dvr)
                                <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($checklist->parabrisas)
                                <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($checklist->luces)
                                <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($checklist->balizas)
                                <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($checklist->antivuelco)
                                <svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-5 h-5 text-red-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $checklist->user->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <p>No hay checklists registrados aún.</p>
            <p class="text-sm mt-1">Comienza registrando el estado de un vehículo.</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Nuevo Checklist -->
<div id="nuevo-checklist-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center"
    style="padding-top: calc(var(--fb-topbar-height, 60px) + 1rem); padding-bottom: 1rem; padding-left: 1rem; padding-right: 1rem;"
    onclick="if(event.target === this) document.getElementById('nuevo-checklist-modal').classList.add('hidden')">
    <div class="bg-zinc-900 rounded-2xl w-full max-w-lg border border-zinc-700/50 shadow-2xl flex flex-col"
        style="max-height: calc(100vh - var(--fb-topbar-height, 60px) - 2rem);"
        onclick="event.stopPropagation()">
        <form action="{{ route('client.checklist.store') }}" method="POST" class="flex flex-col overflow-hidden rounded-2xl">
            @csrf
            <!-- Header (fixed) -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600/10 rounded-lg">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-100">Nuevo Checklist</h3>
                </div>
                <button type="button" onclick="document.getElementById('nuevo-checklist-modal').classList.add('hidden')" class="p-2 text-gray-500 hover:text-gray-300 hover:bg-zinc-800 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Body (scrollable) -->
            <div class="px-6 py-5 overflow-y-auto flex-1 space-y-4 checklist-scroll">
                <!-- Vehículo -->
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Vehículo *</label>
                    <select name="patrulla_id" required class="w-full bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                        <option value="">Seleccione un vehículo</option>
                        @foreach($patrullas as $patrulla)
                            <option value="{{ $patrulla->id }}">{{ $patrulla->patente }} {{ $patrulla->marca ? '- '.$patrulla->marca : '' }} {{ $patrulla->modelo ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Fecha *</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">
                </div>

                <!-- Ruedas de auxilio (obligatorio) -->
                <div class="p-4 bg-zinc-800/80 rounded-xl border border-amber-800/30">
                    <label class="block text-xs font-medium text-amber-400 mb-1.5 uppercase tracking-wider">
                        Ruedas de Auxilio * <span class="text-xs text-gray-500 normal-case">(obligatorio - deben ser 2)</span>
                    </label>
                    <input type="number" name="ruedas_auxilio" id="ruedas_auxilio" value="2" min="0" max="10" required
                        class="w-full bg-zinc-700 border border-zinc-600 rounded-xl text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all"
                        onchange="checkRuedas(this)">
                    <div id="ruedas-warning" class="hidden mt-2 text-sm text-red-400">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        Atención: El vehículo debería tener 2 ruedas de auxilio.
                    </div>
                </div>

                <!-- Checklist Items -->
                <div class="space-y-2">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Estado del vehículo</p>

                    <label class="flex items-center gap-3 p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700/50 cursor-pointer transition-all">
                        <input type="checkbox" name="antena_starlink" value="1" class="rounded bg-zinc-700 border-zinc-500 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-200">Antena Starlink funcionando</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700/50 cursor-pointer transition-all">
                        <input type="checkbox" name="camaras_dvr" value="1" class="rounded bg-zinc-700 border-zinc-500 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-200">4 Cámaras y DVR funcionando</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700/50 cursor-pointer transition-all">
                        <input type="checkbox" name="parabrisas" value="1" class="rounded bg-zinc-700 border-zinc-500 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-200">Parabrisas en forma</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700/50 cursor-pointer transition-all">
                        <input type="checkbox" name="luces" value="1" class="rounded bg-zinc-700 border-zinc-500 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-200">Luces en forma</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700/50 cursor-pointer transition-all">
                        <input type="checkbox" name="balizas" value="1" class="rounded bg-zinc-700 border-zinc-500 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-200">Balizas funcionando</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-zinc-800 rounded-xl hover:bg-zinc-700/50 cursor-pointer transition-all">
                        <input type="checkbox" name="antivuelco" value="1" class="rounded bg-zinc-700 border-zinc-500 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-200">Antivuelco en forma</span>
                    </label>
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wider">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl text-gray-200 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" placeholder="Notas adicionales..."></textarea>
                </div>
            </div>

            <!-- Footer (fixed) -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-800 bg-zinc-900/50 shrink-0">
                <button type="button" onclick="document.getElementById('nuevo-checklist-modal').classList.add('hidden')"
                    class="px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-gray-200 bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 rounded-xl transition-all">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Guardar Checklist
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .checklist-scroll::-webkit-scrollbar { width: 6px; }
    .checklist-scroll::-webkit-scrollbar-track { background: transparent; }
    .checklist-scroll::-webkit-scrollbar-thumb { background: rgba(39, 39, 42, 0.8); border-radius: 3px; }
    .checklist-scroll::-webkit-scrollbar-thumb:hover { background: rgba(63, 63, 70, 0.9); }
    .checklist-scroll { scrollbar-width: thin; scrollbar-color: rgba(39, 39, 42, 0.8) transparent; }
</style>

<script>
function checkRuedas(input) {
    const warning = document.getElementById('ruedas-warning');
    if (parseInt(input.value) !== 2) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
}
</script>
@endsection
