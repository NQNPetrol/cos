<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 text-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <!-- Header Principal -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-100">Gestión de Pilotos Flytbase</h2>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Componente de Asignaciones -->
                    <div class="mb-8">
                        <livewire:asignar-pilotos-clientes />
                    </div>

                    <!-- Componente de Gestión de Pilotos -->
                    <div>
                        <livewire:manage-pilotos-flytbase />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>