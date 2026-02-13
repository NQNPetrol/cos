<x-administrative-layout>
    <div class="py-6">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mensajes de estado -->
            @if(session('success'))
                <div class="mb-5 p-4 bg-green-900/30 border border-green-800/50 text-green-300 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top duration-300">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 p-4 bg-red-900/30 border border-red-800/50 text-red-300 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top duration-300">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- Header Principal -->
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-blue-600/20 to-blue-400/10 rounded-xl border border-blue-500/20">
                            <svg class="w-7 h-7 text-blue-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16,6H10.5v4H1v5H3a3,3,0,0,0,6,0h6a3,3,0,0,0,6,0h2V12a2,2,0,0,0-2-2H19L16,6M12,7.5h3.5l2,2.5H12V7.5m-6,6A1.5,1.5,0,1,1,4.5,15,1.5,1.5,0,0,1,6,13.5m12,0A1.5,1.5,0,1,1,16.5,15,1.5,1.5,0,0,1,18,13.5Z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-100 tracking-tight">Gestión de Vehículos</h1>
                            <p class="text-sm text-gray-400 mt-0.5">Administra vehículos, turnos y mantenimientos</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                            <span class="text-xs text-gray-400">Vehículos</span>
                            <span class="text-sm font-semibold text-gray-200">{{ $rodados->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                            <span class="text-xs text-gray-400">Turnos Pendientes</span>
                            <span class="text-sm font-semibold text-gray-200">{{ collect($todosLosServicios)->where('estado', 'pendiente')->count() }}</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-zinc-800/80 rounded-xl border border-zinc-700/50">
                            <div class="w-2 h-2 rounded-full bg-green-400"></div>
                            <span class="text-xs text-gray-400">Completados</span>
                            <span class="text-sm font-semibold text-gray-200">{{ collect($todosLosServicios)->whereIn('estado', ['completado', 'atendido'])->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex items-center gap-2 mb-6">
                <button onclick="switchTab('vehiculos')" id="tab-vehiculos"
                    class="tab-button group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-blue-600 text-white shadow-lg shadow-blue-600/20">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                        </svg>
                        Vehículos
                        <span class="text-xs opacity-75 bg-white/20 px-1.5 py-0.5 rounded-md">{{ $rodados->count() }}</span>
                    </span>
                </button>
                <button onclick="switchTab('servicios')" id="tab-servicios"
                    class="tab-button group relative px-5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 bg-zinc-800 text-gray-400 hover:text-gray-200 hover:bg-zinc-700 border border-zinc-700/50">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17l-5.08 3.05a.75.75 0 01-1.08-.8l.97-5.67-4.12-4.01a.75.75 0 01.42-1.28l5.69-.83 2.54-5.16a.75.75 0 011.36 0l2.54 5.16 5.69.83a.75.75 0 01.42 1.28l-4.12 4.01.97 5.67a.75.75 0 01-1.08.8l-5.08-3.05z"/>
                        </svg>
                        Services y Turnos
                        <span class="text-xs opacity-60 bg-zinc-700 px-1.5 py-0.5 rounded-md">{{ count($todosLosServicios) }}</span>
                    </span>
                </button>
            </div>

            <!-- Tab Content -->
            <div id="tab-content-vehiculos" class="tab-content">
                @include('rodados.partials.vehiculos-tab')
            </div>

            <div id="tab-content-servicios" class="tab-content hidden">
                @include('rodados.partials.servicios-tab')
            </div>
        </div>
    </div>

    <!-- Scripts para tabs -->
    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
                button.classList.add('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            });
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('bg-zinc-800', 'text-gray-400', 'border', 'border-zinc-700/50');
            activeButton.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-600/20');
        }
    </script>

    <style>
        .animate-in { animation: fadeSlideIn 0.3s ease-out; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -5px rgba(0,0,0,0.3); }

        /* Invisible scrollbars for modal containers */
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .modal-scroll::-webkit-scrollbar-thumb { background: rgba(63, 63, 70, 0.5); border-radius: 3px; }
        .modal-scroll::-webkit-scrollbar-thumb:hover { background: rgba(82, 82, 91, 0.7); }
        .modal-scroll { scrollbar-width: thin; scrollbar-color: rgba(63, 63, 70, 0.5) transparent; }

        /* Modal backdrop animation */
        .modal-backdrop { animation: modalFadeIn 0.2s ease-out; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content { animation: modalSlideIn 0.25s ease-out; }
        @keyframes modalSlideIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }

        /* Responsive: remove sidebar offset on small screens */
        @media (max-width: 768px) {
            .modal-backdrop { padding-left: 1rem !important; padding-top: 1rem !important; }
        }
    </style>
</x-administrative-layout>
