/**
 * Dashboard Operacional
 * Maneja la funcionalidad completa del dashboard operacional
 */

class OperacionesDashboard {
    constructor() {
        this.map = null;
        this.markers = {
            eventos: [],
            vehiculos: [],
            docks: [],
            camaras: []
        };
        this.filters = {
            cliente_id: null,
            estado_evento: [],
            map_type: 'satellite'
        };
        this.isClient = document.body.classList.contains('client-layout') || 
                       window.location.pathname.includes('/client');
        this.refreshInterval = null;
        this.filterDebounceTimer = null;
        this.isFullscreen = false;
        this.isMapFullscreen = false;
        this.currentInfoWindow = null;
        this.currentEventosPage = 1;
        this.currentEventosPagination = null;
        
        this.init();
    }
    
    init() {
        // Esperar a que Google Maps esté disponible
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            this.setup();
        } else {
            // Si no está disponible, esperar al callback
            window.initOperacionesMap = () => this.setup();
        }
    }
    
    setup() {
        this.initMap();
        // Solo cargar KPIs y eventos si no están en la vista inicial
        const kpisIniciales = document.querySelector('[data-kpis-loaded]');
        const eventosIniciales = document.querySelector('[data-eventos-loaded]');
        
        if (!kpisIniciales) {
            this.loadKPIs();
        }
        
        this.loadMapData();
        
        if (!eventosIniciales) {
            this.loadEventos();
        } else {
            // Los eventos ya están en la vista, solo configurar listeners
            this.setupEventosListeners();
        }
        
        this.setupFilters();
        this.setupControls();
        this.startAutoRefresh();
    }
    
    setupEventosListeners() {
        const container = document.getElementById('eventos-list-content');
        if (!container) return;
        
        container.querySelectorAll('.evento-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (e.target.closest('.evento-footer-btn')) {
                    return;
                }
                
                const lat = parseFloat(card.dataset.lat);
                const lng = parseFloat(card.dataset.lng);
                
                if (lat && lng && !isNaN(lat) && !isNaN(lng) && this.map) {
                    this.map.setCenter({ lat, lng });
                    this.map.setZoom(15);
                }
            });
        });
    }
    
    initMap() {
        const mapElement = document.getElementById('operaciones-map');
        if (!mapElement) return;
        
        // Centro inicial: Buenos Aires
        const initialCenter = { lat: -34.6037, lng: -58.3816 };
        
        this.map = new google.maps.Map(mapElement, {
            center: initialCenter,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.SATELLITE,
            mapTypeControl: false,
            zoomControl: true,
            streetViewControl: false,
            fullscreenControl: false,
            styles: []
        });
        
        // Ajustar mapa cuando cambie de tamaño
        google.maps.event.addListener(this.map, 'resize', () => {
            this.fitMapToMarkers();
        });
    }
    
    async loadKPIs() {
        try {
            const queryParams = new URLSearchParams();
            if (this.filters.cliente_id) {
                queryParams.append('cliente_id', this.filters.cliente_id);
            }
            
            const response = await fetch(`/api/operaciones/kpis?${queryParams}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderKPIs(data.data);
            }
        } catch (error) {
            console.error('Error cargando KPIs:', error);
            this.showErrorMessage('Error al cargar KPIs');
        }
    }
    
    renderKPIs(kpis) {
        // KPI 1: Cámaras
        const camarasEl = document.getElementById('kpi-camaras-total');
        if (camarasEl) camarasEl.textContent = kpis.camaras_total || 0;
        
        // KPI 2: Vehículos
        const vehiculosTotalEl = document.getElementById('kpi-vehiculos-total');
        const vehiculosEquipadosEl = document.getElementById('kpi-vehiculos-equipados');
        if (vehiculosTotalEl) vehiculosTotalEl.textContent = kpis.vehiculos_total || 0;
        if (vehiculosEquipadosEl) vehiculosEquipadosEl.textContent = kpis.vehiculos_equipados || 0;
        
        // KPI 3: Trending
        const trendingPorcentajeEl = document.getElementById('kpi-trending-porcentaje');
        const trendingIconEl = document.getElementById('kpi-trending-icon');
        if (trendingPorcentajeEl) {
            const porcentaje = kpis.eventos_trending?.porcentaje_cambio || 0;
            const tendencia = kpis.eventos_trending?.tendencia || 'aumento';
            trendingPorcentajeEl.textContent = porcentaje.toFixed(1);
            trendingPorcentajeEl.parentElement.style.color = tendencia === 'aumento' ? '#10b981' : '#ef4444';
            
            if (trendingIconEl) {
                trendingIconEl.className = 'trending-icon ' + (tendencia === 'aumento' ? 'up' : 'down');
                trendingIconEl.textContent = tendencia === 'aumento' ? '↑' : '↓';
            }
        }
        
        // KPI 4: Eventos Abiertos
        const eventosAbiertosEl = document.getElementById('kpi-eventos-abiertos');
        if (eventosAbiertosEl) eventosAbiertosEl.textContent = kpis.eventos_abiertos || 0;
        
        // KPI 5: Tiempo Promedio Cierre
        const tiempoDiasEl = document.getElementById('kpi-tiempo-dias');
        const tiempoHorasEl = document.getElementById('kpi-tiempo-horas');
        if (tiempoDiasEl) tiempoDiasEl.textContent = kpis.tiempo_promedio_cierre?.dias || 0;
        if (tiempoHorasEl) tiempoHorasEl.textContent = kpis.tiempo_promedio_cierre?.horas || 0;
        
        // KPI 6: Vuelos
        const vuelosTotalEl = document.getElementById('kpi-vuelos-total');
        const vuelosIncompletosEl = document.getElementById('kpi-vuelos-incompletos');
        if (vuelosTotalEl) vuelosTotalEl.textContent = kpis.vuelos_total || 0;
        if (vuelosIncompletosEl) vuelosIncompletosEl.textContent = kpis.vuelos_incompletos || 0;
        
        // KPI 7: Triggers
        const triggersTotalEl = document.getElementById('kpi-triggers-total');
        const triggersDetailEl = document.getElementById('kpi-triggers-detail');
        if (triggersTotalEl) triggersTotalEl.textContent = kpis.triggers_misiones?.total || 0;
        if (triggersDetailEl && kpis.triggers_misiones?.por_dron) {
            const detail = kpis.triggers_misiones.por_dron
                .slice(0, 3)
                .map(t => `${t.drone_name}: ${t.count}`)
                .join(', ');
            triggersDetailEl.textContent = detail || '';
        }
    }
    
    async loadMapData(filters = {}) {
        try {
            this.showLoadingState();
            
            const queryParams = new URLSearchParams();
            if (filters.cliente_id || this.filters.cliente_id) {
                queryParams.append('cliente_id', filters.cliente_id || this.filters.cliente_id);
            }
            if (filters.estado_evento || this.filters.estado_evento.length > 0) {
                const estados = filters.estado_evento || this.filters.estado_evento;
                estados.forEach(estado => {
                    queryParams.append('estado_evento[]', estado);
                });
            }
            
            const response = await fetch(`/api/operaciones/map-data?${queryParams}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error al cargar datos');
            }
            
            this.renderMapData(data);
            this.hideLoadingState();
            
        } catch (error) {
            console.error('Error cargando datos del mapa:', error);
            this.showErrorMessage('Error al cargar datos del mapa. Por favor, recarga la página.');
            this.hideLoadingState();
        }
    }
    
    renderMapData(data) {
        this.clearMarkers();
        
        // Crear markers para eventos
        if (data.eventos) {
            data.eventos.forEach(evento => {
                this.createEventoMarker(evento);
            });
        }
        
        // Crear markers para vehículos
        if (data.vehiculos) {
            data.vehiculos.forEach(vehiculo => {
                this.createVehiculoMarker(vehiculo);
            });
        }
        
        // Crear markers para docks
        if (data.docks) {
            data.docks.forEach(dock => {
                this.createDockMarker(dock);
            });
        }
        
        // Crear markers para cámaras
        if (data.camaras) {
            data.camaras.forEach(camara => {
                this.createCamaraMarker(camara);
            });
        }
        
        this.fitMapToMarkers();
    }
    
    clearMarkers() {
        // Limpiar todos los markers
        Object.values(this.markers).forEach(markerArray => {
            markerArray.forEach(marker => {
                marker.setMap(null);
            });
        });
        
        this.markers = {
            eventos: [],
            vehiculos: [],
            docks: [],
            camaras: []
        };
        
        if (this.currentInfoWindow) {
            this.currentInfoWindow.close();
            this.currentInfoWindow = null;
        }
    }
    
    createEventoMarker(evento) {
        // Crear SVG personalizado para el marker de eventos
        const svgIcon = this.createEventoMarkerIcon();
        
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(evento.latitud), lng: parseFloat(evento.longitud) },
            map: this.map,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                scaledSize: new google.maps.Size(32, 32),
                anchor: new google.maps.Point(16, 16), // Centro del círculo
                origin: new google.maps.Point(0, 0)
            },
            title: `Evento #${evento.id}`,
            zIndex: google.maps.Marker.MAX_ZINDEX + 1 // Eventos sobre otros markers
        });
        
        // Guardar datos del evento en el marker para referencia
        marker.eventoData = evento;
        
        const infoWindow = new google.maps.InfoWindow({
            content: this.generateEventoTooltip(evento)
        });
        
        marker.addListener('click', () => {
            if (this.currentInfoWindow) {
                this.currentInfoWindow.close();
            }
            infoWindow.open(this.map, marker);
            this.currentInfoWindow = infoWindow;
        });
        
        this.markers.eventos.push(marker);
    }
    
    /**
     * Crear icono SVG personalizado para markers de eventos
     * - Redondo
     * - Fondo azul
     * - Borde blanco
     * - Icono SVG blanco (mismo que sidebar)
     */
    createEventoMarkerIcon() {
        // Icono SVG del sidebar de eventos (documento/clipboard)
        // Path del icono: M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z
        // ViewBox original: 0 0 24 24, necesitamos escalarlo a ~16x16 dentro del círculo de 32x32
        
        return `
            <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <!-- Círculo de fondo azul con borde blanco -->
                <circle cx="16" cy="16" r="14" fill="#1877f2" stroke="#ffffff" stroke-width="2"/>
                <!-- Icono SVG blanco centrado (escalado desde viewBox 24x24 a ~16x16) -->
                <g transform="translate(8, 8) scale(0.67)">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" 
                          fill="none" 
                          stroke="#ffffff" 
                          stroke-width="2.5" 
                          stroke-linecap="round" 
                          stroke-linejoin="round"/>
                </g>
            </svg>
        `;
    }
    
    createVehiculoMarker(vehiculo) {
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(vehiculo.latitude), lng: parseFloat(vehiculo.longitude) },
            map: this.map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#1877f2',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2,
                scale: 16
            },
            title: vehiculo.vehicle_name
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: this.generateVehiculoTooltip(vehiculo)
        });
        
        marker.addListener('click', () => {
            if (this.currentInfoWindow) {
                this.currentInfoWindow.close();
            }
            infoWindow.open(this.map, marker);
            this.currentInfoWindow = infoWindow;
        });
        
        this.markers.vehiculos.push(marker);
    }
    
    createDockMarker(dock) {
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(dock.latitud), lng: parseFloat(dock.longitud) },
            map: this.map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#10b981',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2,
                scale: 16
            },
            title: dock.nombre
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: this.generateDockTooltip(dock)
        });
        
        marker.addListener('click', () => {
            if (this.currentInfoWindow) {
                this.currentInfoWindow.close();
            }
            infoWindow.open(this.map, marker);
            this.currentInfoWindow = infoWindow;
        });
        
        this.markers.docks.push(marker);
    }
    
    createCamaraMarker(camara) {
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(camara.latitud), lng: parseFloat(camara.longitud) },
            map: this.map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#8b5cf6',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2,
                scale: 16
            },
            title: camara.camera_name
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: this.generateCamaraTooltip(camara)
        });
        
        marker.addListener('click', () => {
            if (this.currentInfoWindow) {
                this.currentInfoWindow.close();
            }
            infoWindow.open(this.map, marker);
            this.currentInfoWindow = infoWindow;
        });
        
        this.markers.camaras.push(marker);
    }
    
    generateEventoTooltip(evento) {
        const estadoClass = evento.estado.toLowerCase().replace(' ', '-');
        return `
            <div class="map-tooltip evento-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-bell-fill tooltip-icon"></i>
                    <span class="tooltip-title">Evento #${evento.id}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Cliente:</span>
                        <span class="tooltip-value">${this.escapeHtml(evento.cliente)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Estado:</span>
                        <span class="tooltip-badge estado-${estadoClass}">${this.escapeHtml(evento.estado)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Fecha:</span>
                        <span class="tooltip-value">${evento.fecha_hora_formatted || evento.fecha_hora}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Categoría:</span>
                        <span class="tooltip-value">${this.escapeHtml(evento.categoria)}</span>
                    </div>
                    ${evento.descripcion ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">Descripción:</span>
                        <span class="tooltip-value">${this.escapeHtml(evento.descripcion.substring(0, 100))}</span>
                    </div>
                    ` : ''}
                </div>
                <div class="tooltip-footer">
                    <a href="/eventos/${evento.id}/reporte" class="tooltip-btn" target="_blank">
                        Ver Detalles
                    </a>
                </div>
            </div>
        `;
    }
    
    generateVehiculoTooltip(vehiculo) {
        return `
            <div class="map-tooltip vehiculo-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-taxi-front-fill tooltip-icon"></i>
                    <span class="tooltip-title">${this.escapeHtml(vehiculo.vehicle_name)}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Patente:</span>
                        <span class="tooltip-value">${this.escapeHtml(vehiculo.plate_no)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Velocidad:</span>
                        <span class="tooltip-value">${vehiculo.speed || 0} km/h</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Dirección:</span>
                        <span class="tooltip-value">${vehiculo.direction || 0}°</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Cliente:</span>
                        <span class="tooltip-value">${this.escapeHtml(vehiculo.cliente || 'N/A')}</span>
                    </div>
                </div>
                <div class="tooltip-footer">
                    <a href="/patrullas/location" class="tooltip-btn" target="_blank">
                        Ver Ubicación
                    </a>
                </div>
            </div>
        `;
    }
    
    generateDockTooltip(dock) {
        return `
            <div class="map-tooltip dock-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-airplane-fill tooltip-icon"></i>
                    <span class="tooltip-title">${this.escapeHtml(dock.nombre)}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Site:</span>
                        <span class="tooltip-value">${this.escapeHtml(dock.site)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Estado:</span>
                        <span class="tooltip-value">${dock.active ? 'Activo' : 'Inactivo'}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Coordenadas:</span>
                        <span class="tooltip-value">${dock.latitud.toFixed(6)}, ${dock.longitud.toFixed(6)}</span>
                    </div>
                    ${dock.altitude ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">Altitud:</span>
                        <span class="tooltip-value">${dock.altitude} m</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    }
    
    generateCamaraTooltip(camara) {
        return `
            <div class="map-tooltip camara-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-camera-video-fill tooltip-icon"></i>
                    <span class="tooltip-title">${this.escapeHtml(camara.camera_name)}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Dispositivo:</span>
                        <span class="tooltip-value">${this.escapeHtml(camara.dispositivo_nombre)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Cliente:</span>
                        <span class="tooltip-value">${this.escapeHtml(camara.cliente)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Estado:</span>
                        <span class="tooltip-value">${camara.status === 1 ? 'Activo' : 'Inactivo'}</span>
                    </div>
                    ${camara.direccion_ip ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">IP:</span>
                        <span class="tooltip-value">${camara.direccion_ip}</span>
                    </div>
                    ` : ''}
                </div>
                <div class="tooltip-footer">
                    <a href="/cameras/${camara.camera_index_code}/liveview" class="tooltip-btn" target="_blank">
                        Ver Liveview
                    </a>
                </div>
            </div>
        `;
    }
    
    fitMapToMarkers() {
        if (!this.map) return;
        
        const bounds = new google.maps.LatLngBounds();
        let hasMarkers = false;
        
        Object.values(this.markers).forEach(markerArray => {
            markerArray.forEach(marker => {
                bounds.extend(marker.getPosition());
                hasMarkers = true;
            });
        });
        
        if (hasMarkers) {
            this.map.fitBounds(bounds);
            // Ajustar zoom si es muy cercano
            google.maps.event.addListenerOnce(this.map, 'bounds_changed', () => {
                if (this.map.getZoom() > 15) {
                    this.map.setZoom(15);
                }
            });
        }
    }
    
    setupFilters() {
        // Filtro de tipo de mapa
        const satelliteBtn = document.getElementById('map-type-satellite');
        const roadmapBtn = document.getElementById('map-type-roadmap');
        
        if (satelliteBtn) {
            satelliteBtn.addEventListener('click', () => {
                this.changeMapType('satellite');
                satelliteBtn.classList.add('active');
                if (roadmapBtn) roadmapBtn.classList.remove('active');
            });
        }
        
        if (roadmapBtn) {
            roadmapBtn.addEventListener('click', () => {
                this.changeMapType('roadmap');
                roadmapBtn.classList.add('active');
                if (satelliteBtn) satelliteBtn.classList.remove('active');
            });
        }
        
        // Activar satelital por defecto
        if (satelliteBtn) satelliteBtn.classList.add('active');
        
        // Filtro de estado de evento
        const estadoButtons = document.querySelectorAll('[data-estado]');
        estadoButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const estado = btn.dataset.estado;
                btn.classList.toggle('active');
                
                const estados = Array.from(document.querySelectorAll('[data-estado].active'))
                    .map(b => b.dataset.estado);
                
                this.applyFilters({ estado_evento: estados });
            });
        });
        
        // Filtro de cliente (solo admin)
        if (!this.isClient) {
            const clienteSelect = document.getElementById('filter-cliente');
            if (clienteSelect) {
                clienteSelect.addEventListener('change', (e) => {
                    const clienteId = e.target.value || null;
                    this.applyFilters({ cliente_id: clienteId });
                });
            }
        }
    }
    
    changeMapType(type) {
        if (!this.map) return;
        
        this.filters.map_type = type;
        this.map.setMapTypeId(type === 'satellite' ? 
            google.maps.MapTypeId.SATELLITE : 
            google.maps.MapTypeId.ROADMAP);
    }
    
    applyFilters(filters) {
        clearTimeout(this.filterDebounceTimer);
        
        this.filterDebounceTimer = setTimeout(() => {
            this.filters = { ...this.filters, ...filters };
            this.loadMapData(this.filters);
            // Resetear a página 1 cuando cambian los filtros
            this.currentEventosPage = 1;
            this.loadEventos(this.filters, 1);
            this.loadKPIs();
        }, 300);
    }
    
    async loadEventos(filters = {}, page = 1) {
        try {
            const queryParams = new URLSearchParams();
            if (filters.cliente_id || this.filters.cliente_id) {
                queryParams.append('cliente_id', filters.cliente_id || this.filters.cliente_id);
            }
            if (filters.estado_evento || this.filters.estado_evento.length > 0) {
                const estados = filters.estado_evento || this.filters.estado_evento;
                estados.forEach(estado => {
                    queryParams.append('estado_evento[]', estado);
                });
            }
            queryParams.append('page', page);
            
            const container = document.getElementById('eventos-list-content');
            if (container) {
                container.innerHTML = '<div class="eventos-loading"><div class="loading-spinner"></div><p>Cargando eventos...</p></div>';
            }
            
            const response = await fetch(`/api/operaciones/eventos?${queryParams}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderEventos(data.data, data.pagination);
                this.currentEventosPage = page;
                this.currentEventosPagination = data.pagination;
            } else {
                this.showEventosError('Error al cargar eventos');
            }
        } catch (error) {
            console.error('Error cargando eventos:', error);
            this.showEventosError('Error al cargar eventos. Por favor, recarga la página.');
        }
    }
    
    renderEventos(eventos, pagination = null) {
        const container = document.getElementById('eventos-list-content');
        if (!container) return;
        
        if (!eventos || eventos.length === 0) {
            container.innerHTML = `
                <div class="eventos-empty">
                    <i class="bi bi-inbox" style="font-size: 48px; color: var(--fb-text-secondary); margin-bottom: 16px;"></i>
                    <p style="color: var(--fb-text-secondary);">No hay eventos disponibles</p>
                    <p style="color: var(--fb-text-secondary); font-size: 12px; margin-top: 8px;">
                        ${this.filters.estado_evento.length > 0 ? 'Intenta cambiar los filtros de estado' : 'No se encontraron eventos con los filtros aplicados'}
                    </p>
                </div>
            `;
            this.hidePagination();
            return;
        }
        
        // Ordenar eventos por fecha (más recientes primero) - aunque ya vienen ordenados del backend
        const eventosOrdenados = [...eventos].sort((a, b) => {
            const fechaA = new Date(a.fecha_hora);
            const fechaB = new Date(b.fecha_hora);
            return fechaB - fechaA; // Más reciente primero
        });
        
        container.innerHTML = eventosOrdenados.map(evento => {
            const estadoClass = evento.estado.toLowerCase().replace(/\s+/g, '-');
            const descripcion = evento.descripcion || 'Sin descripción';
            const descripcionTruncada = descripcion.length > 100 ? descripcion.substring(0, 100) + '...' : descripcion;
            
            return `
                <div class="evento-card" data-event-id="${evento.id}" data-lat="${evento.latitud || ''}" data-lng="${evento.longitud || ''}">
                    <div class="evento-header">
                        <span class="evento-cliente-tag">${this.escapeHtml(evento.cliente)}</span>
                        <span class="evento-estado-badge ${estadoClass}">${this.escapeHtml(evento.estado)}</span>
                    </div>
                    <div class="evento-body">
                        <div class="evento-categoria">
                            <i class="bi bi-tag-fill" style="font-size: 12px; margin-right: 4px;"></i>
                            ${this.escapeHtml(evento.categoria)}
                        </div>
                        <div class="evento-descripcion">${this.escapeHtml(descripcionTruncada)}</div>
                        <div class="evento-fecha">
                            <i class="bi bi-clock" style="font-size: 11px; margin-right: 4px;"></i>
                            ${evento.fecha_hora_formatted || this.formatFecha(evento.fecha_hora)}
                        </div>
                        ${evento.latitud && evento.longitud ? `
                        <div class="evento-ubicacion" style="font-size: 11px; color: var(--fb-text-secondary); margin-top: 4px;">
                            <i class="bi bi-geo-alt-fill" style="font-size: 10px; margin-right: 4px;"></i>
                            Con ubicación
                        </div>
                        ` : ''}
                    </div>
                    <div class="evento-footer">
                        <button class="evento-footer-btn" onclick="event.stopPropagation(); window.open('/eventos/${evento.id}/reporte', '_blank')">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            `;
        }).join('');
        
        // Agregar listeners para centrar mapa al hacer click en la tarjeta
        container.querySelectorAll('.evento-card').forEach(card => {
            card.addEventListener('click', (e) => {
                // No centrar si se hizo click en el botón
                if (e.target.closest('.evento-footer-btn')) {
                    return;
                }
                
                const lat = parseFloat(card.dataset.lat);
                const lng = parseFloat(card.dataset.lng);
                
                if (lat && lng && !isNaN(lat) && !isNaN(lng) && this.map) {
                    this.map.setCenter({ lat, lng });
                    this.map.setZoom(15);
                    
                    // Resaltar el marker correspondiente si existe
                    const eventoId = parseInt(card.dataset.eventId);
                    const marker = this.markers.eventos.find(m => {
                        const markerData = m.eventoData;
                        return markerData && markerData.id === eventoId;
                    });
                    
                    if (marker) {
                        // Abrir el info window del marker
                        google.maps.event.trigger(marker, 'click');
                    }
                }
            });
        });
        
        // Mostrar/ocultar paginación
        if (pagination && pagination.last_page > 1) {
            this.showPagination(pagination);
        } else {
            this.hidePagination();
        }
    }
    
    showPagination(pagination) {
        const paginationEl = document.getElementById('eventos-pagination');
        const prevBtn = document.getElementById('prev-page-btn');
        const nextBtn = document.getElementById('next-page-btn');
        const infoEl = document.getElementById('pagination-info');
        
        if (!paginationEl || !prevBtn || !nextBtn || !infoEl) return;
        
        paginationEl.style.display = 'flex';
        
        prevBtn.disabled = pagination.current_page <= 1;
        nextBtn.disabled = pagination.current_page >= pagination.last_page;
        
        infoEl.textContent = `Página ${pagination.current_page} de ${pagination.last_page} (${pagination.total} total)`;
        
        // Remover listeners anteriores
        const newPrevBtn = prevBtn.cloneNode(true);
        const newNextBtn = nextBtn.cloneNode(true);
        prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
        nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
        
        // Agregar nuevos listeners
        newPrevBtn.addEventListener('click', () => {
            if (pagination.current_page > 1) {
                this.loadEventos(this.filters, pagination.current_page - 1);
            }
        });
        
        newNextBtn.addEventListener('click', () => {
            if (pagination.current_page < pagination.last_page) {
                this.loadEventos(this.filters, pagination.current_page + 1);
            }
        });
    }
    
    hidePagination() {
        const paginationEl = document.getElementById('eventos-pagination');
        if (paginationEl) {
            paginationEl.style.display = 'none';
        }
    }
    
    showEventosError(message) {
        const container = document.getElementById('eventos-list-content');
        if (container) {
            container.innerHTML = `
                <div class="eventos-error">
                    <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #ef4444; margin-bottom: 16px;"></i>
                    <p style="color: var(--fb-text-secondary);">${message}</p>
                </div>
            `;
        }
    }
    
    formatFecha(fechaString) {
        try {
            const fecha = new Date(fechaString);
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const año = fecha.getFullYear();
            const horas = String(fecha.getHours()).padStart(2, '0');
            const minutos = String(fecha.getMinutes()).padStart(2, '0');
            return `${dia}/${mes}/${año} ${horas}:${minutos}`;
        } catch (e) {
            return fechaString;
        }
    }
    
    setupControls() {
        // Botón nueva pestaña
        const newTabBtn = document.getElementById('new-tab-btn');
        if (newTabBtn) {
            newTabBtn.addEventListener('click', () => {
                window.open(window.location.href, '_blank');
            });
        }
        
        // Botón pantalla completa
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', () => {
                this.toggleFullscreen();
            });
        }
        
        // Botón expandir mapa
        const expandMapBtn = document.getElementById('expand-map-btn');
        if (expandMapBtn) {
            expandMapBtn.addEventListener('click', () => {
                this.toggleMapFullscreen();
            });
        }
        
        // Botón refresh eventos
        const refreshEventosBtn = document.getElementById('refresh-eventos-btn');
        if (refreshEventosBtn) {
            refreshEventosBtn.addEventListener('click', () => {
                this.loadEventos(this.filters, this.currentEventosPage || 1);
            });
        }
    }
    
    toggleFullscreen() {
        const dashboard = document.querySelector('.operaciones-dashboard');
        const topNav = document.querySelector('.modern-top-nav');
        const sidebar = document.querySelector('.modern-sidebar');
        
        if (!this.isFullscreen) {
            // Entrar en pantalla completa
            if (topNav) topNav.style.display = 'none';
            if (sidebar) sidebar.style.display = 'none';
            if (dashboard) dashboard.classList.add('fullscreen-mode');
            
            document.body.style.padding = '0';
            document.body.style.margin = '0';
            
            this.isFullscreen = true;
            this.updateFullscreenButton();
        } else {
            // Salir de pantalla completa
            if (topNav) topNav.style.display = 'flex';
            if (sidebar) sidebar.style.display = 'block';
            if (dashboard) dashboard.classList.remove('fullscreen-mode');
            
            document.body.style.padding = '';
            document.body.style.margin = '';
            
            this.isFullscreen = false;
            this.updateFullscreenButton();
        }
    }
    
    toggleMapFullscreen() {
        const mapContainer = document.getElementById('operaciones-map-container');
        if (!mapContainer) return;
        
        if (!document.fullscreenElement) {
            mapContainer.requestFullscreen().then(() => {
                if (this.map) {
                    google.maps.event.trigger(this.map, 'resize');
                }
            }).catch(err => {
                console.error('Error entrando en pantalla completa:', err);
            });
        } else {
            document.exitFullscreen();
        }
    }
    
    updateFullscreenButton() {
        const btn = document.getElementById('fullscreen-btn');
        if (!btn) return;
        
        const icon = btn.querySelector('i');
        if (icon) {
            if (this.isFullscreen) {
                icon.className = 'bi bi-fullscreen-exit';
                btn.title = 'Salir de pantalla completa';
            } else {
                icon.className = 'bi bi-arrows-fullscreen';
                btn.title = 'Pantalla completa';
            }
        }
    }
    
    startAutoRefresh() {
        // Refrescar cada 30 segundos
        this.refreshInterval = setInterval(() => {
            this.loadKPIs();
            this.loadMapData(this.filters);
            this.loadEventos(this.filters, this.currentEventosPage || 1);
        }, 30000);
    }
    
    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }
    
    showLoadingState() {
        // Implementar indicador de carga si es necesario
    }
    
    hideLoadingState() {
        // Ocultar indicador de carga
    }
    
    showErrorMessage(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'dashboard-error-message';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('operaciones-map')) {
        window.operacionesDashboard = new OperacionesDashboard();
    }
});

// Actualizar navegación para operaciones
if (typeof ModernNavigation !== 'undefined') {
    const originalSetupTopBarButtons = ModernNavigation.prototype.setupTopBarButtons;
    ModernNavigation.prototype.setupTopBarButtons = function() {
        originalSetupTopBarButtons.call(this);
        
        const operacionesBtn = document.querySelector('[data-dashboard="operaciones"]');
        if (operacionesBtn) {
            operacionesBtn.addEventListener('click', (e) => {
                const route = operacionesBtn.dataset.route;
                if (route && route !== '#') {
                    window.location.href = route;
                }
            });
        }
    };
}

