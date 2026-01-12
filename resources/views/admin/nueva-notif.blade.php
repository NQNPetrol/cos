<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-600 text-white p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <div class="bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-white mb-2">Crear Nueva Notificación</h2>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('notifications.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Tipo de Notificación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Notificación</label>
                            <select name="type" class="w-full bg-zinc-700 border border-zinc-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="global">Notificación Global (Todos los usuarios)</option>
                                <option value="user">Notificación Específica para Usuario</option>
                                <option value="client">Notificación para Cliente</option>
                            </select>
                        </div>

                        <!-- Campos condicionales -->
                        <div id="userField" class="hidden">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Seleccionar Usuario</label>
                            <select name="user_id" class="w-full bg-zinc-700 border border-zinc-600 text-white rounded-lg px-4 py-2">
                                <option value="">Seleccione un usuario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="clientField" class="hidden">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Seleccionar Cliente</label>
                            <select name="client_id" class="w-full bg-zinc-700 border border-zinc-600 text-white rounded-lg px-4 py-2">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Título -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                            <input type="text" name="title" required 
                                   class="w-full bg-zinc-700 border border-zinc-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ingrese el título de la notificación">
                        </div>

                        <!-- Mensaje -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Mensaje</label>
                            <textarea name="message" rows="4" required
                                      class="w-full bg-zinc-700 border border-zinc-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                      placeholder="Escriba el mensaje de la notificación"></textarea>
                        </div>

                        <!-- Prioridad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Prioridad</label>
                            <select name="priority" class="w-full bg-zinc-700 border border-zinc-600 text-white rounded-lg px-4 py-2">
                                <option value="BAJA">BAJA</option>
                                <option value="NORMAL" selected>MEDIA</option>
                                <option value="ALTA">ALTA</option>
                            </select>
                        </div>

                        <!-- Checkbox activa -->
                        <div>
                            <label class="flex items-center">
                                <!-- Campo hidden que siempre se envía -->
                                <input type="hidden" name="is_active" value="0">
                                <!-- Checkbox que sobrescribe el valor cuando está marcado -->
                                <input type="checkbox" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-zinc-600 bg-zinc-700 text-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-300">Notificación activa</span>
                            </label>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="{{ route('notifications.admin') }}" 
                               class="bg-zinc-600 hover:bg-zinc-500 text-white px-6 py-2 rounded-lg transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg transition-colors">
                                Crear Notificación
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('select[name="type"]');
            const userField = document.getElementById('userField');
            const clientField = document.getElementById('clientField');

            function toggleFields() {
                const type = typeSelect.value;
                userField.classList.toggle('hidden', type !== 'user');
                clientField.classList.toggle('hidden', type !== 'client');
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call
        });
    </script>
</x-app-layout>