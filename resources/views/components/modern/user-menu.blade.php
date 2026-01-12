@props(['isClient' => false])

<div id="userMenu" class="modern-dropdown hidden" style="width: 300px;">
    <!-- Header -->
    <div style="padding: 16px; border-bottom: 1px solid var(--fb-border);">
        <div style="display: flex; align-items: center; gap: 12px;">
            @if(Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_path)
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover;">
            @else
                <div style="width: 64px; height: 64px; border-radius: 50%; background-color: var(--fb-bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--fb-text-primary); font-weight: bold; font-size: 24px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 600; font-size: 15px; color: var(--fb-text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->name }}
                </div>
                <div style="font-size: 13px; color: var(--fb-text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menu Items -->
    <div style="padding: 8px 0;">
        <a href="{{ $isClient ? route('client.settings.user-profile') : route('settings.user-profile') }}" class="modern-sidebar-item" style="text-decoration: none;">
            <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Ver información de usuario</span>
        </a>
        
        <a href="{{ $isClient ? route('client.activity-log') : route('activity-log.index', ['user' => Auth::id()]) }}" class="modern-sidebar-item" style="text-decoration: none;">
            <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Activity Log</span>
        </a>
        
        <a href="{{ $isClient ? route('client.settings.system') : route('settings.system') }}" class="modern-sidebar-item" style="text-decoration: none;">
            <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Configuración</span>
        </a>
        
        <div style="height: 1px; background-color: var(--fb-border); margin: 8px 0;"></div>
        
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="modern-sidebar-item" style="width: 100%; text-align: left; background: transparent; border: none; cursor: pointer; color: var(--fb-text-primary);" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--fb-text-primary)'">
                <svg class="modern-sidebar-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Log out</span>
            </button>
        </form>
    </div>
</div>

