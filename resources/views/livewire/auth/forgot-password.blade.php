<div class="space-y-6">
    <x-auth-logo />

    {{-- Descripción adicional --}}
    <div class="text-center">
        <p class="text-gray-600 text-sm">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña
        </p>
    </div>

    {{-- Status de sesión --}}
    @if (session('status'))
        <x-auth-status type="success">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('status') }}
            </div>
        </x-auth-status>
    @endif

    {{-- Formulario de Recuperación --}}
    <form wire:submit="sendPasswordResetLink" class="space-y-5">
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

        {{-- Botón de envío --}}
        <div class="pt-2">
            <x-auth-button wire:loading.attr="disabled">
                <span wire:loading.remove>Enviar Enlace de Recuperación</span>
                <span wire:loading class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando enlace...
                </span>
            </x-auth-button>
        </div>
    </form>


    {{-- Información adicional --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-blue-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm text-blue-700">
                    Si no recibes el correo en unos minutos, revisa tu carpeta de spam o correo no deseado.
                </p>
            </div>
        </div>
    </div>

    {{-- Links de navegación --}}
    <div class="pt-4 text-center space-y-2">
        <p class="text-gray-600 text-sm">
            ¿Recordaste tu contraseña?
            <x-auth-link href="{{ route('login') }}" wire:navigate>
                Inicia sesión
            </x-auth-link>
        </p>
        
        @if (Route::has('register'))
            <p class="text-gray-600 text-sm">
                ¿No tienes una cuenta?
                <x-auth-link href="{{ route('register') }}" wire:navigate>
                    Regístrate aquí
                </x-auth-link>
            </p>
        @endif
    </div>
</div>