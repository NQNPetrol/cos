<div class="modern-settings-container">
    <div class="modern-settings-header">
        <h1 class="modern-settings-title">{{ __('Configuración de Usuario') }}</h1>
        <p class="modern-settings-subtitle">{{ __('Administra tu información personal y preferencias de cuenta') }}</p>
    </div>

    <div class="modern-settings-layout">
        {{-- Sidebar Navigation --}}
        <div class="modern-settings-nav">
            <a href="{{ route('settings.user-profile') }}" class="modern-settings-nav-item active">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ __('Perfil') }}</span>
            </a>
            <a href="{{ route('settings.system') }}" class="modern-settings-nav-item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('Sistema') }}</span>
            </a>
        </div>

        {{-- Main Content --}}
        <div class="modern-settings-content">
            {{-- Profile Information Section --}}
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Información del perfil') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Actualiza tu nombre y correo electrónico') }}</p>
                </div>

                <form wire:submit="updateProfileInformation" class="modern-settings-form">
                    <div class="modern-form-group">
                        <label for="name" class="modern-form-label">{{ __('Nombre') }}</label>
                        <input type="text" id="name" wire:model="name" class="modern-form-input" required autofocus autocomplete="name">
                        @error('name') <span class="modern-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="modern-form-group">
                        <label for="email" class="modern-form-label">{{ __('Correo electrónico') }}</label>
                        <input type="email" id="email" wire:model="email" class="modern-form-input" required autocomplete="email">
                        @error('email') <span class="modern-form-error">{{ $message }}</span> @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                            <div class="modern-form-warning">
                                <p>{{ __('Tu correo electrónico no ha sido verificado.') }}</p>
                                <button type="button" wire:click.prevent="resendVerificationNotification" class="modern-link">
                                    {{ __('Reenviar correo de verificación') }}
                                </button>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="modern-form-success">{{ __('Se ha enviado un nuevo enlace de verificación.') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="modern-form-actions">
                        <button type="submit" class="modern-btn modern-btn-primary">{{ __('Guardar cambios') }}</button>
                        <span wire:loading wire:target="updateProfileInformation" class="modern-loading">{{ __('Guardando...') }}</span>
                        @if (session('profile-updated'))
                            <span class="modern-form-success">{{ __('Guardado.') }}</span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Profile Photo Section --}}
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Foto de perfil') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Actualiza tu foto de perfil') }}</p>
                </div>

                <div class="modern-photo-upload">
                    <div class="modern-photo-preview">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="modern-avatar-lg">
                        @else
                            <div class="modern-avatar-lg modern-avatar-placeholder">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                        @if ($photo)
                            <div class="modern-photo-overlay">
                                <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="modern-avatar-lg">
                            </div>
                        @endif
                    </div>

                    <div class="modern-photo-actions">
                        <label for="photo" class="modern-btn modern-btn-secondary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ __('Seleccionar imagen') }}
                        </label>
                        <input type="file" wire:model="photo" id="photo" class="hidden" accept="image/*">
                        @error('photo') <span class="modern-form-error">{{ $message }}</span> @enderror

                        @if ($photo)
                            <button type="button" wire:click="updateProfilePhoto" class="modern-btn modern-btn-primary">{{ __('Guardar foto') }}</button>
                            <button type="button" wire:click="$set('photo', null)" class="modern-btn modern-btn-ghost">{{ __('Cancelar') }}</button>
                        @endif

                        @if (auth()->user()->profile_photo_path && !$photo)
                            <button type="button" wire:click="deleteProfilePhoto" class="modern-btn modern-btn-danger">{{ __('Eliminar foto') }}</button>
                        @endif

                        <p class="modern-photo-hint">{{ __('JPG, PNG o GIF. Máximo 2MB.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Update Password Section --}}
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Actualizar contraseña') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Asegúrate de usar una contraseña segura') }}</p>
                </div>

                <form wire:submit="updatePassword" class="modern-settings-form">
                    <div class="modern-form-group">
                        <label for="current_password" class="modern-form-label">{{ __('Contraseña actual') }}</label>
                        <input type="password" id="current_password" wire:model="current_password" class="modern-form-input" required autocomplete="current-password">
                        @error('current_password') <span class="modern-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="modern-form-group">
                        <label for="password" class="modern-form-label">{{ __('Nueva contraseña') }}</label>
                        <input type="password" id="password" wire:model="password" class="modern-form-input" required autocomplete="new-password">
                        @error('password') <span class="modern-form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="modern-form-group">
                        <label for="password_confirmation" class="modern-form-label">{{ __('Confirmar contraseña') }}</label>
                        <input type="password" id="password_confirmation" wire:model="password_confirmation" class="modern-form-input" required autocomplete="new-password">
                    </div>

                    <div class="modern-form-actions">
                        <button type="submit" class="modern-btn modern-btn-primary">{{ __('Actualizar contraseña') }}</button>
                        @if (session('password-updated'))
                            <span class="modern-form-success">{{ __('Contraseña actualizada.') }}</span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Browser Sessions Section --}}
            @if (config('session.driver') === 'database')
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Sesiones del navegador') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Administra tus sesiones activas en otros dispositivos') }}</p>
                </div>

                <div class="modern-sessions-list">
                    @if (count($sessions) > 0)
                        @foreach ($sessions as $session)
                            <div class="modern-session-item">
                                <div class="modern-session-icon">
                                    @if ($session->agent['is_desktop'])
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="modern-session-info">
                                    <div class="modern-session-device">{{ $session->agent['platform'] }} - {{ $session->agent['browser'] }}</div>
                                    <div class="modern-session-details">
                                        {{ $session->ip_address }}
                                        @if ($session->is_current_device)
                                            <span class="modern-session-current">{{ __('Este dispositivo') }}</span>
                                        @else
                                            <span>{{ __('Última actividad') }} {{ $session->last_active }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="modern-sessions-empty">{{ __('No hay otras sesiones activas.') }}</p>
                    @endif
                </div>

                <div class="modern-form-actions">
                    <button type="button" onclick="document.getElementById('logout-sessions-modal').showModal()" class="modern-btn modern-btn-secondary">
                        {{ __('Cerrar otras sesiones') }}
                    </button>
                </div>

                <dialog id="logout-sessions-modal" class="modern-modal">
                    <form wire:submit="logoutOtherBrowserSessions" class="modern-modal-content">
                        <h3 class="modern-modal-title">{{ __('Cerrar otras sesiones') }}</h3>
                        <p class="modern-modal-desc">{{ __('Ingresa tu contraseña para confirmar que deseas cerrar las sesiones en otros dispositivos.') }}</p>
                        
                        <div class="modern-form-group">
                            <label for="confirm_password" class="modern-form-label">{{ __('Contraseña') }}</label>
                            <input type="password" id="confirm_password" wire:model="current_password" class="modern-form-input">
                            @error('current_password') <span class="modern-form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="modern-modal-actions">
                            <button type="button" onclick="document.getElementById('logout-sessions-modal').close()" class="modern-btn modern-btn-ghost">{{ __('Cancelar') }}</button>
                            <button type="submit" class="modern-btn modern-btn-primary">{{ __('Cerrar sesiones') }}</button>
                        </div>
                    </form>
                </dialog>
            </div>
            @endif

            {{-- Delete Account Section --}}
            <div class="modern-settings-section modern-settings-danger">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Eliminar cuenta') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Elimina permanentemente tu cuenta y todos tus datos') }}</p>
                </div>

                <div class="modern-form-actions">
                    <button type="button" onclick="document.getElementById('delete-account-modal').showModal()" class="modern-btn modern-btn-danger">
                        {{ __('Eliminar cuenta') }}
                    </button>
                </div>

                <dialog id="delete-account-modal" class="modern-modal">
                    <form wire:submit="deleteUser" class="modern-modal-content">
                        <h3 class="modern-modal-title modern-modal-danger">{{ __('¿Eliminar tu cuenta?') }}</h3>
                        <p class="modern-modal-desc">{{ __('Esta acción no se puede deshacer. Todos tus datos serán eliminados permanentemente.') }}</p>
                        
                        <div class="modern-form-group">
                            <label for="delete_password" class="modern-form-label">{{ __('Contraseña') }}</label>
                            <input type="password" id="delete_password" wire:model="delete_password" class="modern-form-input">
                            @error('delete_password') <span class="modern-form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="modern-modal-actions">
                            <button type="button" onclick="document.getElementById('delete-account-modal').close()" class="modern-btn modern-btn-ghost">{{ __('Cancelar') }}</button>
                            <button type="submit" class="modern-btn modern-btn-danger">{{ __('Eliminar cuenta') }}</button>
                        </div>
                    </form>
                </dialog>
            </div>
        </div>
    </div>

    @include('livewire.settings._settings-styles')
</div>
