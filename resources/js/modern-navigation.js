/**
 * Modern Navigation System
 * Handles dynamic sidebar content switching, level-based navigation, and deep navigation
 */

class ModernNavigation {
    constructor() {
        this.currentDashboard = 'home';
        this.currentLevel = 'main';
        this.navigationHistory = [];
        this.scrollPositions = {};
        this.isClient = document.body.classList.contains('client-layout') || window.location.pathname.includes('/client');
        this.currentNotificationTab = 'unread';
        this.allNotifications = [];
        
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        // Set initial dashboard from URL or sessionStorage
        this.currentDashboard = sessionStorage.getItem('activeDashboard') || this.detectDashboardFromURL();
        this.currentLevel = sessionStorage.getItem('activeLevel') || 'main';

        // Setup top bar button clicks
        this.setupTopBarButtons();
        
        // Setup sidebar clicks
        this.setupSidebarClicks();
        
        // Setup dropdown menus
        this.setupDropdownMenus();
        
        // Setup shortcuts navigation
        this.setupShortcutsNavigation();
        
        // Load initial sidebar content
        this.loadSidebarContent(this.currentDashboard, this.currentLevel);
        
        // Set active route and top bar button
        this.setActiveRoute(window.location.pathname);
        this.setActiveTopBarButton(this.currentDashboard);
    }

    detectDashboardFromURL() {
        const path = window.location.pathname;
        if (path.includes('/client/')) {
            if (path.includes('/eventos')) return 'eventos';
            if (path.includes('/patrullas')) return 'patrullas';
            if (path.includes('/alertas') || path.includes('/misiones') || path.includes('/flight-logs')) return 'drones';
            if (path.includes('/gallery')) return 'galeria';
            if (path.includes('/tickets')) return 'tickets';
            return 'home';
        } else {
            if (path.includes('/clientes') || path.includes('/personal') || path.includes('/empresas-asociadas') || path.includes('/contratos')) return 'administracion';
            if (path.includes('/eventos') || path.includes('/objetivos') || path.includes('/patrullas') || path.includes('/cameras') || path.includes('/anpr') || path.includes('/pilotos') || path.includes('/misiones-flytbase') || path.includes('/alertas') || path.includes('/flight-logs') || path.includes('/sites') || path.includes('/drones-flytbase') || path.includes('/docks-flytbase') || path.includes('/seguimientos')) return 'operaciones';
            if (path.includes('/permisos') || path.includes('/roles') || path.includes('/usuarios') || path.includes('/tickets') || path.includes('/inventario') || path.includes('/gallery') || path.includes('/notifications')) return 'sistema';
            return 'home';
        }
    }


    setupTopBarButtons() {
        const buttons = document.querySelectorAll('.modern-top-nav-button[data-dashboard]');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const dashboard = button.dataset.dashboard;
                const route = button.dataset.route;
                
                // Update active state
                this.setActiveTopBarButton(dashboard);
                
                // Handle Operaciones special case (Level 1)
                if (dashboard === 'operaciones') {
                    this.navigateToLevel('operaciones-level1', 'operaciones');
                } else {
                    // For other dashboards, navigate to main level
                    this.navigateToLevel('main', dashboard);
                    if (route && route !== '#') {
                        window.location.href = route;
                    }
                }
            });
        });
    }

    setupSidebarClicks() {
        const sidebar = document.getElementById('sidebarContent');
        if (!sidebar) return;

        sidebar.addEventListener('click', (e) => {
            const item = e.target.closest('.modern-sidebar-item, .modern-sidebar-back-button');
            if (!item) return;

            // Handle back button
            if (item.classList.contains('modern-sidebar-back-button')) {
                const backTo = item.dataset.backTo;
                this.goBack(backTo);
                return;
            }

            // Handle level 2 navigation (for Administración and Sistema)
            if (item.dataset.level2) {
                const level2 = item.dataset.level2;
                const dashboard = this.currentDashboard;
                this.navigateToLevel(`${dashboard}-${level2}`, dashboard);
                return;
            }

            // Handle Operaciones Level 1 to Level 2 navigation
            if (this.currentDashboard === 'operaciones' && this.currentLevel === 'operaciones-level1') {
                if (item.dataset.level2) {
                    const level2 = item.dataset.level2;
                    this.navigateToLevel(`operaciones-${level2}`, 'operaciones');
                    return;
                }
            }

            // Handle regular route navigation
            // If item has href attribute, allow normal anchor navigation
            if (item.tagName === 'A' && item.href) {
                // Allow normal anchor navigation - don't prevent default
                this.setActiveRoute(item.href);
                // Let the browser handle the navigation naturally
                return;
            }
            
            // For items with data-route but no href, use JavaScript navigation
            const route = item.dataset.route;
            if (route) {
                this.setActiveRoute(route);
                window.location.href = route;
            }
        });
    }

    setupDropdownMenus() {
        // Shortcuts menu
        const shortcutsBtn = document.getElementById('shortcutsMenuBtn');
        const shortcutsMenu = document.getElementById('shortcutsMenu');
        if (shortcutsBtn && shortcutsMenu) {
            shortcutsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown(shortcutsMenu);
            });
        }

        // Notifications menu
        const notificationsBtn = document.getElementById('notificationsBtn');
        const notificationsMenu = document.getElementById('notificationsMenu');
        if (notificationsBtn && notificationsMenu) {
            notificationsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown(notificationsMenu);
                this.loadNotifications();
            });
        }

        // Setup notification tabs
        this.setupNotificationTabs();

        // User menu
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');
        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleDropdown(userMenu);
            });
        }

        // Close dropdowns on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.modern-dropdown') && !e.target.closest('.modern-top-nav-button')) {
                document.querySelectorAll('.modern-dropdown').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    }

    setupNotificationTabs() {
        const unreadTab = document.getElementById('notificationsTabUnread');
        const readTab = document.getElementById('notificationsTabRead');
        
        if (unreadTab) {
            unreadTab.addEventListener('click', () => {
                this.switchNotificationTab('unread');
            });
        }
        
        if (readTab) {
            readTab.addEventListener('click', () => {
                this.switchNotificationTab('read');
            });
        }
        
        // Setup options button
        const optionsBtn = document.getElementById('notificationsOptionsBtn');
        const optionsMenu = document.getElementById('notificationsOptionsMenu');
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        
        if (optionsBtn && optionsMenu) {
            optionsBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                optionsMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!optionsBtn.contains(e.target) && !optionsMenu.contains(e.target)) {
                    optionsMenu.classList.add('hidden');
                }
            });
        }
        
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', () => {
                this.markAllNotificationsAsRead();
                if (optionsMenu) optionsMenu.classList.add('hidden');
            });
        }
    }

    switchNotificationTab(tab) {
        const unreadTab = document.getElementById('notificationsTabUnread');
        const readTab = document.getElementById('notificationsTabRead');
        
        // Update active state
        if (tab === 'unread') {
            unreadTab?.classList.add('active');
            readTab?.classList.remove('active');
        } else {
            readTab?.classList.add('active');
            unreadTab?.classList.remove('active');
        }
        
        // Reload notifications with filter
        this.currentNotificationTab = tab;
        this.loadNotifications();
    }

    setupShortcutsNavigation() {
        const shortcuts = document.querySelectorAll('[data-shortcut]');
        shortcuts.forEach(shortcut => {
            shortcut.addEventListener('click', (e) => {
                e.preventDefault();
                const route = shortcut.dataset.route;
                const navigation = shortcut.dataset.navigation ? JSON.parse(shortcut.dataset.navigation) : null;
                
                if (navigation) {
                    this.deepNavigate(navigation, route);
                } else if (route) {
                    window.location.href = route;
                }
            });
        });
    }

    toggleDropdown(menu) {
        const isHidden = menu.classList.contains('hidden');
        
        // Close all other dropdowns
        document.querySelectorAll('.modern-dropdown').forEach(m => {
            m.classList.add('hidden');
        });
        
        // Toggle current menu
        if (isHidden) {
            menu.classList.remove('hidden');
            // Position dropdown relative to its trigger button
            this.positionDropdown(menu);
        } else {
            menu.classList.add('hidden');
        }
    }

    positionDropdown(menu) {
        // Find the trigger button for this menu
        let triggerButton = null;
        
        if (menu.id === 'shortcutsMenu') {
            triggerButton = document.getElementById('shortcutsMenuBtn');
        } else if (menu.id === 'notificationsMenu') {
            triggerButton = document.getElementById('notificationsBtn');
        } else if (menu.id === 'userMenu') {
            triggerButton = document.getElementById('userMenuBtn');
        }
        
        if (!triggerButton) return;
        
        // Get button position
        const buttonRect = triggerButton.getBoundingClientRect();
        const menuRect = menu.getBoundingClientRect();
        
        // Position menu below button, aligned to right
        menu.style.position = 'fixed';
        menu.style.top = `${buttonRect.bottom + 8}px`;
        menu.style.right = `${window.innerWidth - buttonRect.right}px`;
        menu.style.left = 'auto';
        
        // Ensure menu doesn't go off-screen
        const viewportHeight = window.innerHeight;
        const menuHeight = menuRect.height || 400; // Fallback height
        const spaceBelow = viewportHeight - buttonRect.bottom;
        
        if (spaceBelow < menuHeight && buttonRect.top > menuHeight) {
            // Position above button if not enough space below
            menu.style.top = `${buttonRect.top - menuHeight - 8}px`;
        }
        
        // Ensure menu doesn't go off right edge
        if (buttonRect.right < menuRect.width) {
            menu.style.right = '8px';
            menu.style.left = 'auto';
        }
    }

    navigateToLevel(level, dashboard) {
        // Save scroll position
        this.saveScrollPosition();
        
        // Update state
        this.currentLevel = level;
        this.currentDashboard = dashboard;
        
        // Save to sessionStorage
        sessionStorage.setItem('activeDashboard', dashboard);
        sessionStorage.setItem('activeLevel', level);
        
        // Add to history
        this.navigationHistory.push({ level, dashboard });
        
        // Load sidebar content
        this.loadSidebarContent(dashboard, level);
        
        // Update top bar active state
        this.setActiveTopBarButton(dashboard);
    }

    goBack(targetLevel) {
        // Save current scroll position
        this.saveScrollPosition();
        
        // Determine target level and dashboard
        let level, dashboard;
        
        if (targetLevel === 'operaciones-level1') {
            level = 'operaciones-level1';
            dashboard = 'operaciones';
        } else if (targetLevel === 'administracion') {
            level = 'main';
            dashboard = 'administracion';
        } else if (targetLevel === 'sistema') {
            level = 'main';
            dashboard = 'sistema';
        } else {
            level = 'main';
            dashboard = this.currentDashboard;
        }
        
        // Restore previous scroll position
        const scrollKey = `${dashboard}-${level}`;
        const savedScroll = this.scrollPositions[scrollKey] || 0;
        
        // Navigate back
        this.navigateToLevel(level, dashboard);
        
        // Restore scroll position after a brief delay
        setTimeout(() => {
            const sidebar = document.getElementById('sidebarContent');
            if (sidebar) {
                sidebar.scrollTop = savedScroll;
            }
        }, 100);
    }

    loadSidebarContent(dashboard, level) {
        const sidebarContent = document.getElementById('sidebarContent');
        const templates = document.getElementById('sidebarTemplates');
        
        if (!sidebarContent || !templates) return;
        
        // Determine template ID
        let templateId;
        
        if (level === 'main') {
            templateId = `sidebar-${dashboard}`;
        } else if (level.startsWith('operaciones-')) {
            templateId = `sidebar-${level}`;
        } else if (level.includes('-')) {
            templateId = `sidebar-${level}`;
        } else {
            templateId = `sidebar-${dashboard}-${level}`;
        }
        
        const template = templates.querySelector(`#${templateId}`);
        
        if (template) {
            // Clone and insert template content
            const content = template.content.cloneNode(true);
            sidebarContent.innerHTML = '';
            sidebarContent.appendChild(content);
            
            // Add slide-in animation
            sidebarContent.classList.add('slide-in');
            setTimeout(() => {
                sidebarContent.classList.remove('slide-in');
            }, 300);
        } else {
            sidebarContent.innerHTML = '<div class="modern-sidebar-item" style="justify-content: center; color: var(--fb-text-secondary);">No hay opciones disponibles</div>';
        }
    }

    setActiveTopBarButton(dashboard) {
        const buttons = document.querySelectorAll('.modern-top-nav-button[data-dashboard]');
        buttons.forEach(btn => {
            if (btn.dataset.dashboard === dashboard) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    setActiveRoute(route) {
        const path = typeof route === 'string' ? route : route.pathname || window.location.pathname;
        const sidebarItems = document.querySelectorAll('.modern-sidebar-item[data-route]');
        
        sidebarItems.forEach(item => {
            const itemRoute = item.dataset.route;
            if (itemRoute && (path.includes(itemRoute) || itemRoute.includes(path))) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    saveScrollPosition() {
        const sidebar = document.getElementById('sidebarContent');
        if (sidebar) {
            const key = `${this.currentDashboard}-${this.currentLevel}`;
            this.scrollPositions[key] = sidebar.scrollTop;
        }
    }

    deepNavigate(navigation, route) {
        // Step 1: Change top bar active state
        if (navigation.topBar) {
            this.setActiveTopBarButton(navigation.topBar);
            this.currentDashboard = navigation.topBar;
        }
        
        // Step 2: Navigate through sidebar levels
        if (navigation.topBar === 'operaciones') {
            // Navigate to Level 1 first
            this.navigateToLevel('operaciones-level1', 'operaciones');
            
            // Then navigate to Level 2 if specified
            if (navigation.level1) {
                setTimeout(() => {
                    this.navigateToLevel(`operaciones-${navigation.level1}`, 'operaciones');
                    
                    // Highlight and navigate to route
                    setTimeout(() => {
                        this.setActiveRoute(route);
                        if (route) {
                            window.location.href = route;
                        }
                    }, 300);
                }, 300);
            } else {
                if (route) {
                    window.location.href = route;
                }
            }
        } else if (navigation.level1) {
            // For Administración and Sistema with submenus
            this.navigateToLevel(`${navigation.topBar}-${navigation.level1}`, navigation.topBar);
            
            setTimeout(() => {
                this.setActiveRoute(route);
                if (route) {
                    window.location.href = route;
                }
            }, 300);
        } else {
            // Direct navigation
            this.navigateToLevel('main', navigation.topBar);
            if (route) {
                window.location.href = route;
            }
        }
    }

    navigateToRoute(routeData) {
        if (routeData.navigation) {
            this.deepNavigate(routeData.navigation, routeData.path);
        } else {
            window.location.href = routeData.path;
        }
    }

    async loadNotifications() {
        const list = document.getElementById('notificationsList');
        const loading = document.getElementById('notificationsLoading');
        const empty = document.getElementById('notificationsEmpty');
        
        if (!list) return;
        
        try {
            // Show loading
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            
            // Fetch notifications
            const response = await fetch('/notificaciones?page=1');
            if (!response.ok) throw new Error('Failed to load notifications');
            
            const data = await response.json();
            
            // Store all notifications
            this.allNotifications = data.data || [];
            
            // Filter by current tab
            const filteredNotifications = this.currentNotificationTab === 'unread' 
                ? this.allNotifications.filter(n => !n.is_read)
                : this.allNotifications.filter(n => n.is_read);
            
            // Update badge
            const badge = document.getElementById('notificationBadge');
            const unreadCount = this.allNotifications.filter(n => !n.is_read).length;
            if (badge && unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badge.classList.remove('hidden');
            } else if (badge) {
                badge.classList.add('hidden');
            }
            
            // Render notifications
            this.renderNotifications(filteredNotifications, list);
            
            // Hide loading
            if (loading) loading.classList.add('hidden');
            
            // Show empty state if no notifications
            if (filteredNotifications.length === 0) {
                if (empty) empty.classList.remove('hidden');
            } else {
                if (empty) empty.classList.add('hidden');
            }
            
        } catch (error) {
            console.error('Error loading notifications:', error);
            if (loading) loading.classList.add('hidden');
            if (empty) empty.classList.remove('hidden');
        }
    }

    renderNotifications(notifications, container) {
        if (!container) return;
        
        container.innerHTML = '';
        
        notifications.forEach(notification => {
            const item = document.createElement('div');
            item.className = 'modern-notification-item';
            
            // Get icon based on notification type
            const icon = this.getNotificationIcon(notification);
            
            item.innerHTML = `
                <div class="modern-notification-icon-container">
                    ${icon}
                </div>
                <div class="modern-notification-content">
                    <div class="modern-notification-title">
                        ${notification.title || 'Notificación'}
                    </div>
                    <div class="modern-notification-message">
                        ${notification.message || ''}
                    </div>
                    <div class="modern-notification-time">
                        ${notification.created_at_human || notification.created_at || ''}
                    </div>
                </div>
            `;
            
            // Add click handler to mark as read
            item.addEventListener('click', () => {
                if (notification.id && !notification.read_at) {
                    this.markNotificationAsRead(notification.id);
                }
            });
            
            container.appendChild(item);
        });
    }
    
    getNotificationIcon(notification) {
        const type = notification.type || '';
        const title = (notification.title || '').toLowerCase();
        
        // Determine icon based on type or title
        if (type.includes('ticket') || title.includes('ticket')) {
            return `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>`;
        } else if (type.includes('evento') || title.includes('evento')) {
            return `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>`;
        } else if (type.includes('patrulla') || title.includes('patrulla')) {
            return `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>`;
        } else if (type.includes('dron') || title.includes('dron') || title.includes('mision')) {
            return `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>`;
        } else if (type.includes('sistema') || title.includes('sistema')) {
            return `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>`;
        } else {
            // Default notification icon
            return `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>`;
        }
    }
    
    async markNotificationAsRead(notificationId) {
        try {
            const response = await fetch(`/notificaciones/${notificationId}/leer`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                // Reload notifications
                this.loadNotifications();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllNotificationsAsRead() {
        try {
            const response = await fetch('/notificaciones/leer-todas', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                
                // Si estamos en tab "sin leer", cambiar a "leídas"
                if (this.currentNotificationTab === 'unread') {
                    this.switchNotificationTab('read');
                } else {
                    // Recargar notificaciones
                    this.loadNotifications();
                }
                
                // Actualizar contador de notificaciones sin leer
                this.updateUnreadCount();
            } else {
                console.error('Error al marcar todas como leídas:', response.statusText);
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }

    updateUnreadCount() {
        // Actualizar contador de notificaciones sin leer en el icono de la barra superior
        const unreadCountElement = document.getElementById('unreadNotificationsCount');
        const badge = document.getElementById('notificationBadge');
        
        if (unreadCountElement) {
            unreadCountElement.textContent = '0';
            unreadCountElement.style.display = 'none';
        }
        
        if (badge) {
            badge.textContent = '0';
            badge.classList.add('hidden');
        }
    }
}

// Initialize navigation when DOM is ready
window.modernNavigation = new ModernNavigation();

