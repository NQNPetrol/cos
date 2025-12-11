<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-100">Gestión de Rodados</h2>
                            <p class="text-gray-400 mt-1">Administra los vehículos, servicios, mantenimientos y pagos</p>
                        </div>
                    </div>

                    <!-- Mensajes -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-800 border border-green-600 text-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-800 border border-red-600 text-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-700 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button onclick="switchTab('vehiculos')" id="tab-vehiculos" 
                                class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-blue-400 border-blue-500">
                                Vehículos
                            </button>
                            <button onclick="switchTab('servicios')" id="tab-servicios" 
                                class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-400 border-transparent hover:text-gray-300 hover:border-gray-300">
                                Servicios
                            </button>
                            <button onclick="switchTab('pagos')" id="tab-pagos" 
                                class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-400 border-transparent hover:text-gray-300 hover:border-gray-300">
                                Pagos
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div id="tab-content-vehiculos" class="tab-content">
                        @include('rodados.partials.vehiculos-tab')
                    </div>

                    <div id="tab-content-servicios" class="tab-content hidden">
                        @include('rodados.partials.servicios-tab')
                    </div>

                    <div id="tab-content-pagos" class="tab-content hidden">
                        @include('rodados.partials.pagos-tab')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para tabs -->
    <script>
        function switchTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remover estilos activos de todos los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('text-blue-400', 'border-blue-500');
                button.classList.add('text-gray-400', 'border-transparent');
            });

            // Mostrar contenido seleccionado
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');

            // Activar botón seleccionado
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('text-gray-400', 'border-transparent');
            activeButton.classList.add('text-blue-400', 'border-blue-500');
        }
    </script>
</x-app-layout>

