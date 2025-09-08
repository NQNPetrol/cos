<div class="w-full max-w-sm space-y-6">
    {{-- Logo y título --}}
    <div class="flex flex-col items-center space-y-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500">
            {{-- Logo SVG del COS --}}
            <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        <div class="text-center">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Centro de Operaciones</h1>
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">de Seguridad</h2>
            <p class="mt-2 text-sm text-gray-300">Ingresa tus credenciales para acceder al sistema</p>
        </div>
    </div>

    {{-- Status de sesión --}}
    @if (session('status'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <p class="text-sm text-green-400">{{ session('status') }}</p>
        </div>
    @endif

    {{-- Formulario de Login --}}
    <form wire:submit="login" class="space-y-4">
        {{-- Campo de Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-200 mb-1">
                Correo electrónico
            </label>
            <input 
                id="email"
                type="email" 
                wire:model="email"
                class="block w-full rounded-md border-0 py-2 px-3 text-white bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                placeholder="usuario@empresa.com"
                required 
                autofocus 
                autocomplete="email" />
            
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Campo de Contraseña con toggle --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-200 mb-1">
                Contraseña
            </label>
            <div class="relative">
                <input 
                    id="password"
                    type="password" 
                    wire:model="password"
                    class="block w-full rounded-md border-0 py-2 px-3 text-white bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Ingresa tu contraseña"
                    required 
                    autocomplete="current-password" />
                
                {{-- Botón toggle para mostrar/ocultar contraseña --}}
                <button type="button" 
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        title="Mostrar contraseña">
                    {{-- Icono ojo abierto --}}
                    <svg id="eyeOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{-- Icono ojo cerrado --}}
                    <svg id="eyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </button>
            </div>
            
            @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Recordarme y Olvidé contraseña --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" 
                       wire:model="remember"
                       class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-indigo-600 focus:ring-indigo-600">
                <span class="ml-2 block text-sm text-gray-300">Recordarme</span>
            </label>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   class="text-sm font-medium text-gray-300 hover:text-blue-800 dark:text-indigo-200 dark:hover:text-indigo-500"
                   wire:navigate>
                    ¿Te olvidaste tu contraseña?
                </a>
            @endif
        </div>

        {{-- Botón de envío --}}
        <button type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <span wire:loading.remove>Iniciar Sesión</span>
            <span wire:loading class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Iniciando...
            </span>
        </button>
    </form>

    {{-- Link de registro --}}
    @if (Route::has('register'))
        <p class="text-center text-sm text-gray-800">
            ¿No tenes una cuenta?
            <a href="{{ route('register') }}" 
               class="text-sm font-medium text-gray-300 hover:text-blue-800 dark:text-indigo-200 dark:hover:text-indigo-500"
               wire:navigate>
                Regístrate aquí
            </a>
        </p>
    @endif
</div>

{{-- Script para el toggle de contraseña --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (togglePassword && passwordInput && eyeOpen && eyeClosed) {
        togglePassword.addEventListener('click', function() {
            // Toggle el tipo de input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle los iconos y tooltip
            if (type === 'password') {
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
                togglePassword.setAttribute('title', 'Mostrar contraseña');
            } else {
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
                togglePassword.setAttribute('title', 'Ocultar contraseña');
            }
        });
    }
});
</script>