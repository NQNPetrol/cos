<div class="space-y-6">
    <x-auth-logo />

    {{-- Status de sesión --}}
    @if (session('status'))
        <x-auth-status type="success">
            {{ session('status') }}
        </x-auth-status>
    @endif

    {{-- Formulario de Login --}}
    <form wire:submit="login" class="space-y-5">
        {{-- Campo de Email --}}
        <div>
            <x-auth-input 
                id="email"
                type="email" 
                wire:model="email"
                label="Correo Electrónico"
                placeholder="usuario@empresa.com"
                required 
                autofocus 
                autocomplete="email" />
            
            @error('email')
                <p class="text-red-600 text-sm flex items-center gap-1 mt-1">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Campo de Contraseña --}}
        <div>
            <x-auth-input 
                id="password"
                type="password" 
                wire:model="password"
                label="Contraseña"
                placeholder="Ingresa tu contraseña"
                required
                viewable
                autocomplete="current-password" />
            
            @error('password')
                <p class="text-red-600 text-sm flex items-center gap-1 mt-1">
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
                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 block text-sm text-gray-700">Recordarme</span>
            </label>
            
            @if (Route::has('password.request'))
                <x-auth-link href="{{ route('password.request') }}" wire:navigate class="text-sm">
                    ¿Olvidaste tu contraseña?
                </x-auth-link>
            @endif
        </div>

        {{-- Botón de envío --}}
        <div class="pt-2">
            <x-auth-button wire:loading.attr="disabled">
                <span wire:loading.remove>Iniciar Sesión</span>
                <span wire:loading class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Iniciando sesión...
                </span>
            </x-auth-button>
        </div>
    </form>


    {{-- Link de registro --}}
    @if (Route::has('register'))
        <div class="pt-4 text-center">
            <p class="text-gray-600 text-sm">
                ¿No tienes una cuenta?
                <x-auth-link href="{{ route('register') }}" wire:navigate>
                    Regístrate aquí
                </x-auth-link>
            </p>
        </div>
    @endif
</div>