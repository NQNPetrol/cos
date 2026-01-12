<div class="modern-settings-container">
    <div class="modern-settings-header">
        <h1 class="modern-settings-title">{{ __('Configuración del Sistema') }}</h1>
        <p class="modern-settings-subtitle">{{ __('Información del sistema y soporte técnico') }}</p>
    </div>

    <div class="modern-settings-layout">
        {{-- Sidebar Navigation --}}
        <div class="modern-settings-nav">
            <a href="{{ route('settings.user-profile') }}" class="modern-settings-nav-item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ __('Perfil') }}</span>
            </a>
            <a href="{{ route('settings.system') }}" class="modern-settings-nav-item active">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('Sistema') }}</span>
            </a>
        </div>

        {{-- Main Content --}}
        <div class="modern-settings-content">
            {{-- System Information Section --}}
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Información del Sistema') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Detalles técnicos del sistema') }}</p>
                </div>

                <div class="modern-info-card">
                    <div class="modern-info-grid">
                        <div class="modern-info-item">
                            <label>{{ __('Versión del Sistema') }}</label>
                            <p>COS v2.0</p>
                        </div>
                        <div class="modern-info-item">
                            <label>{{ __('Entorno') }}</label>
                            <p>{{ ucfirst(app()->environment()) }}</p>
                        </div>
                        <div class="modern-info-item">
                            <label>{{ __('Versión PHP') }}</label>
                            <p>{{ PHP_VERSION }}</p>
                        </div>
                        <div class="modern-info-item">
                            <label>{{ __('Versión Laravel') }}</label>
                            <p>{{ app()->version() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Support Section --}}
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Soporte Técnico') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('¿Necesitas ayuda? Contacta con nuestro equipo') }}</p>
                </div>

                <div class="modern-support-card">
                    <div class="modern-support-content">
                        <div class="modern-support-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="modern-support-info">
                            <h3 class="modern-support-title">{{ __('Centro de Ayuda') }}</h3>
                            <p class="modern-support-desc">
                                {{ __('Si tienes alguna pregunta, problema técnico o sugerencia, no dudes en contactarnos. Nuestro equipo está disponible para ayudarte.') }}
                            </p>
                            
                            <a href="mailto:{{ $supportEmail }}" class="modern-btn modern-btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('Enviar correo de soporte') }}
                            </a>

                            <p class="modern-support-email">
                                <strong>{{ __('Email:') }}</strong> {{ $supportEmail }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Links Section --}}
            <div class="modern-settings-section">
                <div class="modern-settings-section-header">
                    <h2 class="modern-settings-section-title">{{ __('Enlaces Rápidos') }}</h2>
                    <p class="modern-settings-section-desc">{{ __('Accesos directos a funciones del sistema') }}</p>
                </div>

                <div class="modern-quick-links">
                    <a href="{{ route('activity-log.index') }}" class="modern-quick-link">
                        <div class="modern-quick-link-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="modern-quick-link-text">
                            <span class="modern-quick-link-title">{{ __('Activity Log') }}</span>
                            <span class="modern-quick-link-desc">{{ __('Ver registro de actividad') }}</span>
                        </div>
                    </a>

                    <a href="{{ route('settings.user-profile') }}" class="modern-quick-link">
                        <div class="modern-quick-link-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="modern-quick-link-text">
                            <span class="modern-quick-link-title">{{ __('Perfil') }}</span>
                            <span class="modern-quick-link-desc">{{ __('Gestionar perfil') }}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.settings._settings-styles')
</div>
