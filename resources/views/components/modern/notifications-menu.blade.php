<div id="notificationsMenu" class="modern-dropdown hidden" style="width: 360px;">
    <div style="padding: 12px 16px; border-bottom: 1px solid var(--fb-border); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-weight: 600; font-size: 16px; color: var(--fb-text-primary);">Notificaciones</h3>
        <button id="viewAllNotifications" style="background: transparent; border: none; color: var(--fb-accent-blue); font-size: 14px; cursor: pointer; padding: 4px 8px; border-radius: 4px;" onmouseover="this.style.backgroundColor='var(--fb-bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            Ver todas
        </button>
    </div>
    
    <!-- Tabs -->
    <div style="display: flex; border-bottom: 1px solid var(--fb-border);">
        <button id="notificationsTabUnread" class="notification-tab active" data-tab="unread" style="flex: 1; padding: 12px; background: transparent; border: none; border-bottom: 2px solid var(--fb-accent-blue); color: var(--fb-text-primary); font-weight: 500; cursor: pointer;">
            Sin leer
        </button>
        <button id="notificationsTabRead" class="notification-tab" data-tab="read" style="flex: 1; padding: 12px; background: transparent; border: none; border-bottom: 2px solid transparent; color: var(--fb-text-secondary); font-weight: 500; cursor: pointer;">
            Leídas
        </button>
    </div>
    
    <!-- Notifications List -->
    <div id="notificationsList" style="max-height: calc(100vh - 250px); overflow-y: auto;">
        <div id="notificationsLoading" style="padding: 24px; text-align: center; color: var(--fb-text-secondary);">
            <div style="display: inline-block; width: 20px; height: 20px; border: 2px solid var(--fb-border); border-top-color: var(--fb-accent-blue); border-radius: 50%; animation: spin 0.6s linear infinite;"></div>
            <p style="margin-top: 12px; font-size: 14px;">Cargando notificaciones...</p>
        </div>
        
        <div id="notificationsEmpty" class="hidden" style="padding: 48px; text-align: center; color: var(--fb-text-secondary);">
            <svg style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p style="font-weight: 500; margin-bottom: 4px;">No tienes notificaciones</p>
            <p style="font-size: 13px;">Te notificaremos cuando haya algo nuevo</p>
        </div>
    </div>
    
    <!-- Footer -->
    <div id="notificationsFooter" class="hidden" style="padding: 12px 16px; border-top: 1px solid var(--fb-border);">
        <button id="markAllReadBtn" style="width: 100%; padding: 10px; background: var(--fb-bg-tertiary); border: none; border-radius: 6px; color: var(--fb-text-primary); font-weight: 500; cursor: pointer; font-size: 14px;" onmouseover="this.style.backgroundColor='var(--fb-border)'" onmouseout="this.style.backgroundColor='var(--fb-bg-tertiary)'">
            Marcar todas como leídas
        </button>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}

.notification-tab:hover {
    background-color: var(--fb-bg-tertiary) !important;
}

.notification-tab.active {
    color: var(--fb-accent-blue) !important;
    border-bottom-color: var(--fb-accent-blue) !important;
}
</style>

