<div class="space-y-6">
    <x-auth-logo />
  
    <div class="text-center">
        <h2 class="text-xl font-bold text-blue-900">Restablecer Contraseña</h2>
        <p class="text-gray-600 text-sm mt-2">Por favor ingresa tu nueva contraseña</p>
    </div>
  
    {{-- Status de sesión --}}
    @if (session('status'))
        <x-auth-status type="success">
            {{ session('status') }}
        </x-auth-status>
    @endif

    {{-- Formulario de Restablecimiento --}}
    <form wire:submit="resetPassword" class="space-y-5">
        {{-- Campo de Email (oculto o visible dependiendo de tu preferencia) --}}
        <div>
            <x-auth-input 
                id="email"
                type="email" 
                wire:model="email"
                label="Correo Electrónico"
                placeholder="usuario@empresa.com"
                required 
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

        {{-- Campo de Nueva Contraseña --}}
        <div>
            <x-auth-input 
                id="password"
                type="password" 
                wire:model="password"
                label="Nueva Contraseña"
                placeholder="Ingresa tu nueva contraseña"
                required
                viewable
                autocomplete="new-password" />
            
            @error('password')
                <p class="text-red-600 text-sm flex items-center gap-1 mt-1">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Campo de Confirmar Contraseña --}}
        <div>
            <x-auth-input 
                id="password_confirmation"
                type="password" 
                wire:model="password_confirmation"
                label="Confirmar Contraseña"
                placeholder="Repite tu nueva contraseña"
                required
                viewable
                autocomplete="new-password" />
            
            @error('password_confirmation')
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
                <span wire:loading.remove>Restablecer Contraseña</span>
                <span wire:loading class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Restableciendo...
                </span>
            </x-auth-button>
        </div>
    </form>
    {{-- Link de login --}}
    <div class="pt-4 text-center">
        <p class="text-gray-600 text-sm">
            ¿Recordaste tu contraseña?
            <x-auth-link href="{{ route('login') }}" wire:navigate>
                Inicia sesión
            </x-auth-link>
        </p>
    </div>
</div>
