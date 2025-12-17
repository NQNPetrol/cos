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
        this.clusterMarkers = {
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
        
        // Ajustar comportamiento al cambiar tamaño, zoom o al mover el mapa
        google.maps.event.addListener(this.map, 'resize', () => {
            this.fitMapToMarkers();
            this.updateMarkerClusters();
        });

        google.maps.event.addListener(this.map, 'zoom_changed', () => {
            this.updateMarkerClusters();
        });

        google.maps.event.addListener(this.map, 'dragend', () => {
            this.updateMarkerClusters();
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

            console.groupCollapsed('[OperacionesDashboard] loadMapData');
            console.log('Filtros aplicados:', {
                cliente_id: filters.cliente_id || this.filters.cliente_id || null,
                estado_evento: filters.estado_evento || this.filters.estado_evento || [],
            });
            console.log('Conteos recibidos:', {
                eventos: data.eventos ? data.eventos.length : 0,
                vehiculos: data.vehiculos ? data.vehiculos.length : 0,
                docks: data.docks ? data.docks.length : 0,
                camaras: data.camaras ? data.camaras.length : 0,
            });
            if (data.camaras && data.camaras.length) {
                console.log('Primeras cámaras recibidas (máx 5):', data.camaras.slice(0, 5));
            }
            console.groupEnd();
            
            this.renderMapData(data);
            this.hideLoadingState();
            
        } catch (error) {
            console.error('Error cargando datos del mapa:', error);
            this.showErrorMessage('Error al cargar datos del mapa. Por favor, recarga la página.');
            this.hideLoadingState();
        }
    }
    
    renderMapData(data) {
        console.groupCollapsed('[OperacionesDashboard] renderMapData');
        console.log('Datos de entrada para renderizado:', {
            eventos: data.eventos ? data.eventos.length : 0,
            vehiculos: data.vehiculos ? data.vehiculos.length : 0,
            docks: data.docks ? data.docks.length : 0,
            camaras: data.camaras ? data.camaras.length : 0,
        });

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
        
        console.log('Markers creados en memoria:', {
            eventos: this.markers.eventos.length,
            vehiculos: this.markers.vehiculos.length,
            docks: this.markers.docks.length,
            camaras: this.markers.camaras.length,
        });
        console.groupEnd();

        // Actualizar clusters de markers (eventos, vehículos, docks y cámaras)
        this.updateMarkerClusters();

        this.fitMapToMarkers();
    }
    
    clearMarkers() {
        // Limpiar todos los markers individuales
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
        
        // Limpiar cluster markers (burbujas negras por tipo)
        Object.values(this.clusterMarkers).forEach(clusterArray => {
            clusterArray.forEach(marker => {
                marker.setMap(null);
            });
        });

        this.clusterMarkers = {
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

    updateMarkerClusters() {
        if (!this.map) return;

        const bounds = this.map.getBounds();
        const zoom = this.map.getZoom() || 12;
        const projection = this.map.getProjection();

        console.groupCollapsed('[OperacionesDashboard] updateMarkerClusters');
        console.log('Estado inicial clustering:', {
            zoom,
            hasBounds: !!bounds,
            hasProjection: !!projection,
        });

        // Si aún no hay proyección disponible, no intentamos clusterizar
        if (!projection) {
            console.warn('[OperacionesDashboard] Proyección no disponible aún, se omite clustering en esta llamada.');
            console.groupEnd();
            return;
        }

        // Limpiar cluster markers anteriores
        Object.values(this.clusterMarkers).forEach(clusterArray => {
            clusterArray.forEach(marker => marker.setMap(null));
        });
        this.clusterMarkers = {
            eventos: [],
            vehiculos: [],
            docks: [],
            camaras: []
        };

        // A partir de cierto nivel de zoom, no usamos clusters:
        // se muestran todos los markers individuales y NO se dibujan burbujas.
        const clusterOffZoom = 16;
        if (zoom >= clusterOffZoom) {
            console.log('[OperacionesDashboard] Zoom >= clusterOffZoom, solo markers individuales visibles', {
                zoom,
                clusterOffZoom,
            });

            ['eventos', 'vehiculos', 'docks', 'camaras'].forEach(tipo => {
                const arr = this.markers[tipo] || [];
                arr.forEach(marker => {
                    if (marker && !marker.getMap()) {
                        marker.setMap(this.map);
                    }
                });
            });
            console.groupEnd();
            return;
        }

        // Umbral de distancia en píxeles según nivel de zoom
        const thresholdPx = this.getClusterThresholdPixels(zoom);
        console.log('[OperacionesDashboard] Parámetros de clustering:', {
            zoom,
            thresholdPx,
        });

        ['eventos', 'vehiculos', 'docks', 'camaras'].forEach(tipo => {
            const sourceMarkers = this.markers[tipo] || [];
            if (!sourceMarkers.length) return;

            // Tomar solo markers visibles en el viewport para el recuento
            const visibleMarkers = bounds
                ? sourceMarkers.filter(m => bounds.contains(m.getPosition()))
                : sourceMarkers;

            console.groupCollapsed(`[OperacionesDashboard] Tipo ${tipo}`);
            console.log('Markers por tipo:', {
                total: sourceMarkers.length,
                visiblesEnViewport: visibleMarkers.length,
            });

            if (!visibleMarkers.length) {
                console.log('No hay markers visibles para este tipo en el viewport actual.');
                console.groupEnd();
                return;
            }

            // En modo "cluster": ocultamos todos los markers de este tipo y luego
            // dejamos visibles solo los anclas de cada cluster.
            sourceMarkers.forEach(marker => {
                if (marker && marker.getMap()) {
                    marker.setMap(null);
                }
            });

            const clusters = this.buildClustersForMarkers(visibleMarkers, thresholdPx, projection, zoom);
            console.log('Clusters calculados para tipo', tipo, {
                clustersCount: clusters.length,
                detalleClusters: clusters.map((cluster, idx) => {
                    return {
                        index: idx,
                        count: cluster.count,
                        anchorsLatLng: {
                            lat: cluster.markers[0].getPosition().lat(),
                            lng: cluster.markers[0].getPosition().lng(),
                        },
                    };
                }),
            });

            clusters.forEach(cluster => {
                const anchorMarker = cluster.markers[0];
                const pos = anchorMarker.getPosition();

                // Mostrar solo el marker ancla de este cluster (1 marker por zona y tipo)
                if (!anchorMarker.getMap()) {
                    anchorMarker.setMap(this.map);
                }

                // Desplazar la burbuja unos metros hacia el norte para que quede "pegada" arriba
                const bubblePos = this.offsetLatLng(pos, 30); // 30 m al norte
                const svgIcon = this.createClusterBubbleIcon(cluster.count);

                const clusterMarker = new google.maps.Marker({
                    position: bubblePos,
                    map: this.map,
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                        scaledSize: new google.maps.Size(22, 22),
                        anchor: new google.maps.Point(11, 22),
                        origin: new google.maps.Point(0, 0),
                    },
                    clickable: false,
                    zIndex: google.maps.Marker.MAX_ZINDEX + 10
                });

                this.clusterMarkers[tipo].push(clusterMarker);
            });

            console.groupEnd();
        });

        console.groupEnd();
    }

    /**
     * Devuelve el umbral de distancia en píxeles para considerar dos markers dentro del mismo cluster,
     * en función del nivel de zoom actual.
     */
    getClusterThresholdPixels(zoom) {
        // A más zoom, menor umbral en píxeles (en pantalla se ven más separados)
        // En zooms muy lejanos queremos pocos clusters grandes por zona.
        if (zoom >= 16) return 40;
        if (zoom >= 14) return 60;
        if (zoom >= 12) return 80;
        if (zoom >= 10) return 110;
        if (zoom >= 8)  return 150;
        if (zoom >= 6)  return 200;
        return 260; // zoom muy lejano (continente / mundo)
    }

    /**
     * Agrupa markers por proximidad en PÍXELES (clusterización simple, O(n^2)).
     */
    buildClustersForMarkers(markers, thresholdPx, projection, zoom) {
        const clusters = [];
        const used = new Set();

        // Precalcular posiciones en píxeles para cada marker
        const pixels = markers.map(marker => {
            const latLng = marker.getPosition();
            return this.latLngToPixel(latLng, projection, zoom);
        });

        const distancePx = (p1, p2) => {
            const dx = p1.x - p2.x;
            const dy = p1.y - p2.y;
            return Math.sqrt(dx * dx + dy * dy);
        };

        for (let i = 0; i < markers.length; i++) {
            if (used.has(i)) continue;

            const cluster = {
                markers: [markers[i]],
                count: 1
            };
            used.add(i);

            for (let j = i + 1; j < markers.length; j++) {
                if (used.has(j)) continue;

                const dist = distancePx(pixels[i], pixels[j]);
                if (dist <= thresholdPx) {
                    cluster.markers.push(markers[j]);
                    cluster.count++;
                    used.add(j);
                }
            }

            clusters.push(cluster);
        }

        return clusters;
    }

    /**
     * Convierte un LatLng en coordenadas de píxel para el zoom actual.
     */
    latLngToPixel(latLng, projection, zoom) {
        const worldPoint = projection.fromLatLngToPoint(latLng);
        const scale = Math.pow(2, zoom);
        return {
            x: worldPoint.x * scale * 256,
            y: worldPoint.y * scale * 256,
        };
    }

    /**
     * Desplaza un LatLng una cierta cantidad de metros hacia el norte (positivo) o sur (negativo).
     */
    offsetLatLng(latLng, metersNorth) {
        const R = 6378137; // radio aproximado de la Tierra
        const dLat = (metersNorth / R) * (180 / Math.PI);
        return new google.maps.LatLng(latLng.lat() + dLat, latLng.lng());
    }

    /**
     * Crea el SVG de la burbuja negra con el número de cluster.
     */
    createClusterBubbleIcon(count) {
        const display = count > 99 ? '99+' : String(count);

        return `
            <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                <circle cx="11" cy="11" r="9" fill="#111111" stroke="#ffffff" stroke-width="2" />
                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="central"
                      font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif"
                      font-size="10" font-weight="600" fill="#ffffff">
                    ${display}
                </text>
            </svg>
        `;
    }
    
    createEventoMarker(evento) {
        // Crear SVG personalizado para el marker de eventos
        const svgIcon = this.createEventoMarkerIcon();
        
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(evento.latitud), lng: parseFloat(evento.longitud) },
            map: this.map,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 20), // Centro del círculo
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
        // Icono SVG de campana (bi bi-bell), adaptado al marker
        
        return `
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <!-- Círculo de fondo azul con borde blanco -->
                <circle cx="20" cy="20" r="17" fill="#1877f2" stroke="#ffffff" stroke-width="2"/>
                <!-- Icono SVG blanco centrado (viewBox original 0 0 16 16, escalado a ~16x16) -->
                <g transform="translate(10, 10) scale(1)">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"
                          fill="none"
                          stroke="#ffffff"
                          stroke-width="1"
                          stroke-linecap="round"
                          stroke-linejoin="round"/>
                </g>
            </svg>
        `;
    }
    
    createVehiculoMarker(vehiculo) {
        // Icono SVG personalizado para vehículos (círculo y auto blanco)
        const svgIcon = this.createVehiculoMarkerIcon();

        const marker = new google.maps.Marker({
            position: { lat: parseFloat(vehiculo.latitude), lng: parseFloat(vehiculo.longitude) },
            map: this.map,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 20),
                origin: new google.maps.Point(0, 0),
            },
            title: vehiculo.vehicle_name,
            zIndex: google.maps.Marker.MAX_ZINDEX,
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

    /**
     * Icono SVG para vehículos:
     * - Mismo tamaño que otros markers (32x32, círculo r=14)
     * - Color de fondo #d56434
     * - Borde blanco mismo grosor
     * - Icono de vehículo blanco, tamaño similar al de eventos/docks
     */
    createVehiculoMarkerIcon() {
        return `
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <!-- Fondo circular color #d56434 con borde blanco -->
                <circle cx="20" cy="20" r="17" fill="#d56434" stroke="#ffffff" stroke-width="2" />
                <!-- Icono de vehículo centrado (bi bi-car-front-fill) -->
                <g transform="translate(10, 10)" fill="#ffffff">
                    <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
                </g>
            </svg>
        `;
    }
    
    createDockMarker(dock) {
        // Icono SVG personalizado para docks (caja violeta)
        const svgIcon = this.createDockMarkerIcon();

        const marker = new google.maps.Marker({
            position: { lat: parseFloat(dock.latitud), lng: parseFloat(dock.longitud) },
            map: this.map,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 20),
                origin: new google.maps.Point(0, 0),
            },
            title: dock.nombre,
            zIndex: google.maps.Marker.MAX_ZINDEX, // debajo de eventos, pero sobre otros
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

    /**
     * Icono SVG para docks:
     * - Círculo violeta oscuro
     * - Borde blanco
     * - Icono de caja (bi bi-box2) en blanco, tamaño similar al icono de eventos
     */
    createDockMarkerIcon() {
        return `
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <!-- Fondo circular violeta oscuro con borde blanco -->
                <circle cx="20" cy="20" r="17" fill="#4c1d95" stroke="#ffffff" stroke-width="2" />
                <!-- Icono bi bi-box2 centrado -->
                <g transform="translate(10, 10) scale(1)">
                    <path d="M2.95.4a1 1 0 0 1 .8-.4h8.5a1 1 0 0 1 .8.4l2.85 3.8a.5.5 0 0 1 .1.3V15a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4.5a.5.5 0 0 1 .1-.3zM7.5 1H3.75L1.5 4h6zm1 0v3h6l-2.25-3zM15 5H1v10h14z"
                          fill="#ffffff" />
                </g>
            </svg>
        `;
    }
    
    createCamaraMarker(camara) {
        // Icono SVG personalizado para cámaras (círculo y cámara blanca)
        const svgIcon = this.createCamaraMarkerIcon();

        const lat = parseFloat(camara.latitud);
        const lng = parseFloat(camara.longitud);

        if (isNaN(lat) || isNaN(lng)) {
            console.warn('[OperacionesDashboard] Cámara sin coordenadas válidas. Se omitirá el marker.', {
                id: camara.id,
                camera_name: camara.camera_name,
                latitud: camara.latitud,
                longitud: camara.longitud,
            });
            return;
        }

        console.debug('[OperacionesDashboard] Creando marker de cámara', {
            id: camara.id,
            camera_name: camara.camera_name,
            lat: lat,
            lng: lng,
            dispositivo_id: camara.dispositivo_id,
            dispositivo_nombre: camara.dispositivo_nombre,
        });

        const marker = new google.maps.Marker({
            position: { lat, lng },
            map: this.map,
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svgIcon),
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 20),
                origin: new google.maps.Point(0, 0),
            },
            title: camara.camera_name,
            zIndex: google.maps.Marker.MAX_ZINDEX,
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
        console.debug('[OperacionesDashboard] Marker de cámara agregado al mapa', {
            id: camara.id,
            camera_name: camara.camera_name,
        });
    }

    /**
     * Icono SVG para cámaras:
     * - Mismo tamaño que otros markers (32x32, círculo r=14)
     * - Borde blanco mismo grosor
     * - Icono de cámara blanco, basado en SVG provisto (simplificado)
     */
    createCamaraMarkerIcon() {
        return `
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <!-- Fondo circular color #426760 con borde blanco -->
                <circle cx="20" cy="20" r="17" fill="#426760" stroke="#ffffff" stroke-width="2" />
                <!-- Icono de cámara sencillo centrado, basado en SVG (viewBox 0 0 24 24) -->
                <g transform="translate(5,5)" fill="none" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12.5 16h-7.5a2 2 0 0 1 -2 -2v-7a2 2 0 0 1 2 -2h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v2" />
                    <path d="M14.933 8.366a3.001 3.001 0 1 0 -2.933 3.634" />
                    <path d="M21.121 16.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                    <path d="M19 14v.01" />
                </g>
            </svg>
        `;
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
                        <span class="tooltip-label">Registrado por:</span>
                        <span class="tooltip-value">${this.escapeHtml(evento.registrado_por || 'N/A')}</span>
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
                    ${vehiculo.occur_time ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">Última posición:</span>
                        <span class="tooltip-value">${this.escapeHtml(vehiculo.occur_time)}</span>
                    </div>
                    ` : ''}
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
                    ${dock.drone ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">Drone asignado:</span>
                        <span class="tooltip-value">${this.escapeHtml(dock.drone)}</span>
                    </div>
                    ` : ''}
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
                    ${camara.observaciones ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">Observaciones:</span>
                        <span class="tooltip-value">${this.escapeHtml(String(camara.observaciones).substring(0, 100))}</span>
                    </div>
                    ` : ''}
                    ${camara.fecha_instalacion_formatted ? `
                    <div class="tooltip-row">
                        <span class="tooltip-label">Instalada el:</span>
                        <span class="tooltip-value">${this.escapeHtml(camara.fecha_instalacion_formatted)}</span>
                    </div>
                    ` : ''}
                    <div class="tooltip-row">
                        <span class="tooltip-label">Coordenadas:</span>
                        <span class="tooltip-value">${camara.latitud.toFixed(6)}, ${camara.longitud.toFixed(6)}</span>
                    </div>
                </div>
                <div class="tooltip-footer">
                    <a href="/cameras/stream/${camara.camera_index_code}" class="tooltip-btn" target="_blank">
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

