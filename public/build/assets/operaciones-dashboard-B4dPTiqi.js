class y{constructor(){this.map=null,this.markers={eventos:[],vehiculos:[],docks:[],camaras:[]},this.clusterMarkers={eventos:[],vehiculos:[],docks:[],camaras:[]},this.filters={cliente_id:null,estado_evento:[],map_type:"satellite"},this.isClient=document.body.classList.contains("client-layout")||window.location.pathname.includes("/client"),this.refreshInterval=null,this.filterDebounceTimer=null,this.isFullscreen=!1,this.isMapFullscreen=!1,this.currentInfoWindow=null,this.currentEventosPage=1,this.currentEventosPagination=null}init(){console.log("[OperacionesDashboard] init - método de instancia")}setup(){console.log("[OperacionesDashboard] setup - inicializando mapa"),this.initMap();const e=document.querySelector("[data-kpis-loaded]"),t=document.querySelector("[data-eventos-loaded]");e||this.loadKPIs(),this.loadMapData(),t?this.setupEventosListeners():this.loadEventos(),this.setupFilters(),this.setupControls(),this.startAutoRefresh()}setupEventosListeners(){const e=document.getElementById("eventos-list-content");e&&e.querySelectorAll(".evento-card").forEach(t=>{t.addEventListener("click",o=>{if(o.target.closest(".evento-footer-btn"))return;const s=parseFloat(t.dataset.lat),a=parseFloat(t.dataset.lng);s&&a&&!isNaN(s)&&!isNaN(a)&&this.map&&(this.map.setCenter({lat:s,lng:a}),this.map.setZoom(15))})})}initMap(){const e=document.getElementById("operaciones-map");if(!e)return;const t={lat:-34.6037,lng:-58.3816};this.map=new google.maps.Map(e,{center:t,zoom:12,mapTypeId:google.maps.MapTypeId.SATELLITE,mapTypeControl:!1,zoomControl:!0,streetViewControl:!1,fullscreenControl:!1,styles:[]}),google.maps.event.addListener(this.map,"resize",()=>{this.fitMapToMarkers(),this.updateMarkerClusters()}),google.maps.event.addListener(this.map,"zoom_changed",()=>{this.updateMarkerClusters()}),google.maps.event.addListener(this.map,"dragend",()=>{this.updateMarkerClusters()})}async loadKPIs(){try{const e=new URLSearchParams;this.filters.cliente_id&&e.append("cliente_id",this.filters.cliente_id);const o=await(await fetch(`/api/operaciones/kpis?${e}`)).json();o.success&&this.renderKPIs(o.data)}catch(e){console.error("Error cargando KPIs:",e),this.showErrorMessage("Error al cargar KPIs")}}renderKPIs(e){var u,m,b,w,E,k;const t=document.getElementById("kpi-camaras-total");t&&(t.textContent=e.camaras_total||0);const o=document.getElementById("kpi-vehiculos-total"),s=document.getElementById("kpi-vehiculos-equipados");o&&(o.textContent=e.vehiculos_total||0),s&&(s.textContent=e.vehiculos_equipados||0);const a=document.getElementById("kpi-trending-porcentaje"),n=document.getElementById("kpi-trending-icon");if(a){const f=((u=e.eventos_trending)==null?void 0:u.porcentaje_cambio)||0,h=((m=e.eventos_trending)==null?void 0:m.tendencia)||"aumento";a.textContent=f.toFixed(1),a.parentElement.style.color=h==="aumento"?"#10b981":"#ef4444",n&&(n.className="trending-icon "+(h==="aumento"?"up":"down"),n.textContent=h==="aumento"?"↑":"↓")}const i=document.getElementById("kpi-eventos-abiertos");i&&(i.textContent=e.eventos_abiertos||0);const r=document.getElementById("kpi-tiempo-dias"),c=document.getElementById("kpi-tiempo-horas");r&&(r.textContent=((b=e.tiempo_promedio_cierre)==null?void 0:b.dias)||0),c&&(c.textContent=((w=e.tiempo_promedio_cierre)==null?void 0:w.horas)||0);const l=document.getElementById("kpi-vuelos-total"),d=document.getElementById("kpi-vuelos-incompletos");l&&(l.textContent=e.vuelos_total||0),d&&(d.textContent=e.vuelos_incompletos||0);const p=document.getElementById("kpi-triggers-total"),g=document.getElementById("kpi-triggers-detail");if(p&&(p.textContent=((E=e.triggers_misiones)==null?void 0:E.total)||0),g&&((k=e.triggers_misiones)!=null&&k.por_dron)){const f=e.triggers_misiones.por_dron.slice(0,3).map(h=>`${h.drone_name}: ${h.count}`).join(", ");g.textContent=f||""}}async loadMapData(e={}){try{this.showLoadingState();const t=new URLSearchParams;(e.cliente_id||this.filters.cliente_id)&&t.append("cliente_id",e.cliente_id||this.filters.cliente_id),(e.estado_evento||this.filters.estado_evento.length>0)&&(e.estado_evento||this.filters.estado_evento).forEach(n=>{t.append("estado_evento[]",n)});const o=await fetch(`/api/operaciones/map-data?${t}`);if(!o.ok)throw new Error(`HTTP error! status: ${o.status}`);const s=await o.json();if(!s.success)throw new Error(s.message||"Error al cargar datos");console.groupCollapsed("[OperacionesDashboard] loadMapData"),console.log("Filtros aplicados:",{cliente_id:e.cliente_id||this.filters.cliente_id||null,estado_evento:e.estado_evento||this.filters.estado_evento||[]}),console.log("Conteos recibidos:",{eventos:s.eventos?s.eventos.length:0,vehiculos:s.vehiculos?s.vehiculos.length:0,docks:s.docks?s.docks.length:0,camaras:s.camaras?s.camaras.length:0}),s.camaras&&s.camaras.length&&console.log("Primeras cámaras recibidas (máx 5):",s.camaras.slice(0,5)),console.groupEnd(),this.renderMapData(s),this.hideLoadingState()}catch(t){console.error("Error cargando datos del mapa:",t),this.showErrorMessage("Error al cargar datos del mapa. Por favor, recarga la página."),this.hideLoadingState()}}renderMapData(e){console.groupCollapsed("[OperacionesDashboard] renderMapData"),console.log("Datos de entrada para renderizado:",{eventos:e.eventos?e.eventos.length:0,vehiculos:e.vehiculos?e.vehiculos.length:0,docks:e.docks?e.docks.length:0,camaras:e.camaras?e.camaras.length:0}),this.clearMarkers(),e.eventos&&e.eventos.forEach(t=>{this.createEventoMarker(t)}),e.vehiculos&&e.vehiculos.forEach(t=>{this.createVehiculoMarker(t)}),e.docks&&e.docks.forEach(t=>{this.createDockMarker(t)}),e.camaras&&e.camaras.forEach(t=>{this.createCamaraMarker(t)}),console.log("Markers creados en memoria:",{eventos:this.markers.eventos.length,vehiculos:this.markers.vehiculos.length,docks:this.markers.docks.length,camaras:this.markers.camaras.length}),console.groupEnd(),this.updateMarkerClusters(),this.fitMapToMarkers()}clearMarkers(){Object.values(this.markers).forEach(e=>{e.forEach(t=>{t.setMap(null)})}),this.markers={eventos:[],vehiculos:[],docks:[],camaras:[]},Object.values(this.clusterMarkers).forEach(e=>{e.forEach(t=>{t.setMap(null)})}),this.clusterMarkers={eventos:[],vehiculos:[],docks:[],camaras:[]},this.currentInfoWindow&&(this.currentInfoWindow.close(),this.currentInfoWindow=null)}updateMarkerClusters(){if(!this.map)return;const e=this.map.getBounds(),t=this.map.getZoom()||12,o=this.map.getProjection();if(console.groupCollapsed("[OperacionesDashboard] updateMarkerClusters"),console.log("Estado inicial clustering:",{zoom:t,hasBounds:!!e,hasProjection:!!o}),!o){console.warn("[OperacionesDashboard] Proyección no disponible aún, se omite clustering en esta llamada."),console.groupEnd();return}Object.values(this.clusterMarkers).forEach(n=>{n.forEach(i=>i.setMap(null))}),this.clusterMarkers={eventos:[],vehiculos:[],docks:[],camaras:[]};const s=16;if(t>=s){console.log("[OperacionesDashboard] Zoom >= clusterOffZoom, solo markers individuales visibles",{zoom:t,clusterOffZoom:s}),["eventos","vehiculos","docks","camaras"].forEach(n=>{(this.markers[n]||[]).forEach(r=>{r&&!r.getMap()&&r.setMap(this.map)})}),console.groupEnd();return}const a=this.getClusterThresholdPixels(t);console.log("[OperacionesDashboard] Parámetros de clustering:",{zoom:t,thresholdPx:a}),["eventos","vehiculos","docks","camaras"].forEach(n=>{const i=this.markers[n]||[];if(!i.length)return;const r=e?i.filter(l=>e.contains(l.getPosition())):i;if(console.groupCollapsed(`[OperacionesDashboard] Tipo ${n}`),console.log("Markers por tipo:",{total:i.length,visiblesEnViewport:r.length}),!r.length){console.log("No hay markers visibles para este tipo en el viewport actual."),console.groupEnd();return}i.forEach(l=>{l&&l.getMap()&&l.setMap(null)});const c=this.buildClustersForMarkers(r,a,o,t);console.log("Clusters calculados para tipo",n,{clustersCount:c.length,detalleClusters:c.map((l,d)=>({index:d,count:l.count,anchorsLatLng:{lat:l.markers[0].getPosition().lat(),lng:l.markers[0].getPosition().lng()}}))}),c.forEach(l=>{const d=l.markers[0],p=d.getPosition();d.getMap()||d.setMap(this.map);const g=this.offsetLatLng(p,30),u=this.createClusterBubbleIcon(l.count),m=new google.maps.Marker({position:g,map:this.map,icon:{url:"data:image/svg+xml;charset=UTF-8,"+encodeURIComponent(u),scaledSize:new google.maps.Size(22,22),anchor:new google.maps.Point(11,22),origin:new google.maps.Point(0,0)},clickable:!1,zIndex:google.maps.Marker.MAX_ZINDEX+10});this.clusterMarkers[n].push(m)}),console.groupEnd()}),console.groupEnd()}getClusterThresholdPixels(e){return e>=16?40:e>=14?60:e>=12?80:e>=10?110:e>=8?150:e>=6?200:260}buildClustersForMarkers(e,t,o,s){const a=[],n=new Set,i=e.map(c=>{const l=c.getPosition();return this.latLngToPixel(l,o,s)}),r=(c,l)=>{const d=c.x-l.x,p=c.y-l.y;return Math.sqrt(d*d+p*p)};for(let c=0;c<e.length;c++){if(n.has(c))continue;const l={markers:[e[c]],count:1};n.add(c);for(let d=c+1;d<e.length;d++){if(n.has(d))continue;r(i[c],i[d])<=t&&(l.markers.push(e[d]),l.count++,n.add(d))}a.push(l)}return a}latLngToPixel(e,t,o){const s=t.fromLatLngToPoint(e),a=Math.pow(2,o);return{x:s.x*a*256,y:s.y*a*256}}offsetLatLng(e,t){const s=t/6378137*(180/Math.PI);return new google.maps.LatLng(e.lat()+s,e.lng())}createClusterBubbleIcon(e){return`
            <svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
                <circle cx="11" cy="11" r="9" fill="#111111" stroke="#ffffff" stroke-width="2" />
                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="central"
                      font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif"
                      font-size="10" font-weight="600" fill="#ffffff">
                    ${e>99?"99+":String(e)}
                </text>
            </svg>
        `}createEventoMarker(e){const t=this.createEventoMarkerIcon(),o=new google.maps.Marker({position:{lat:parseFloat(e.latitud),lng:parseFloat(e.longitud)},map:this.map,icon:{url:"data:image/svg+xml;charset=UTF-8,"+encodeURIComponent(t),scaledSize:new google.maps.Size(40,40),anchor:new google.maps.Point(20,20),origin:new google.maps.Point(0,0)},title:`Evento #${e.id}`,zIndex:google.maps.Marker.MAX_ZINDEX+1});o.eventoData=e;const s=new google.maps.InfoWindow({content:this.generateEventoTooltip(e)});o.addListener("click",()=>{this.currentInfoWindow&&this.currentInfoWindow.close(),s.open(this.map,o),this.currentInfoWindow=s}),this.markers.eventos.push(o)}createEventoMarkerIcon(){return`
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
        `}createVehiculoMarker(e){const t=this.createVehiculoMarkerIcon(),o=new google.maps.Marker({position:{lat:parseFloat(e.latitude),lng:parseFloat(e.longitude)},map:this.map,icon:{url:"data:image/svg+xml;charset=UTF-8,"+encodeURIComponent(t),scaledSize:new google.maps.Size(40,40),anchor:new google.maps.Point(20,20),origin:new google.maps.Point(0,0)},title:e.vehicle_name,zIndex:google.maps.Marker.MAX_ZINDEX}),s=new google.maps.InfoWindow({content:this.generateVehiculoTooltip(e)});o.addListener("click",()=>{this.currentInfoWindow&&this.currentInfoWindow.close(),s.open(this.map,o),this.currentInfoWindow=s}),this.markers.vehiculos.push(o)}createVehiculoMarkerIcon(){return`
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <!-- Fondo circular color #d56434 con borde blanco -->
                <circle cx="20" cy="20" r="17" fill="#d56434" stroke="#ffffff" stroke-width="2" />
                <!-- Icono de vehículo centrado (bi bi-car-front-fill) -->
                <g transform="translate(10, 10)" fill="#ffffff">
                    <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
                </g>
            </svg>
        `}createDockMarker(e){const t=this.createDockMarkerIcon(),o=new google.maps.Marker({position:{lat:parseFloat(e.latitud),lng:parseFloat(e.longitud)},map:this.map,icon:{url:"data:image/svg+xml;charset=UTF-8,"+encodeURIComponent(t),scaledSize:new google.maps.Size(40,40),anchor:new google.maps.Point(20,20),origin:new google.maps.Point(0,0)},title:e.nombre,zIndex:google.maps.Marker.MAX_ZINDEX}),s=new google.maps.InfoWindow({content:this.generateDockTooltip(e)});o.addListener("click",()=>{this.currentInfoWindow&&this.currentInfoWindow.close(),s.open(this.map,o),this.currentInfoWindow=s}),this.markers.docks.push(o)}createDockMarkerIcon(){return`
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <!-- Fondo circular violeta oscuro con borde blanco -->
                <circle cx="20" cy="20" r="17" fill="#4c1d95" stroke="#ffffff" stroke-width="2" />
                <!-- Icono bi bi-box2 centrado -->
                <g transform="translate(10, 10) scale(1)">
                    <path d="M2.95.4a1 1 0 0 1 .8-.4h8.5a1 1 0 0 1 .8.4l2.85 3.8a.5.5 0 0 1 .1.3V15a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4.5a.5.5 0 0 1 .1-.3zM7.5 1H3.75L1.5 4h6zm1 0v3h6l-2.25-3zM15 5H1v10h14z"
                          fill="#ffffff" />
                </g>
            </svg>
        `}createCamaraMarker(e){const t=this.createCamaraMarkerIcon(),o=parseFloat(e.latitud),s=parseFloat(e.longitud);if(isNaN(o)||isNaN(s)){console.warn("[OperacionesDashboard] Cámara sin coordenadas válidas. Se omitirá el marker.",{id:e.id,camera_name:e.camera_name,latitud:e.latitud,longitud:e.longitud});return}console.debug("[OperacionesDashboard] Creando marker de cámara",{id:e.id,camera_name:e.camera_name,lat:o,lng:s,dispositivo_id:e.dispositivo_id,dispositivo_nombre:e.dispositivo_nombre});const a=new google.maps.Marker({position:{lat:o,lng:s},map:this.map,icon:{url:"data:image/svg+xml;charset=UTF-8,"+encodeURIComponent(t),scaledSize:new google.maps.Size(40,40),anchor:new google.maps.Point(20,20),origin:new google.maps.Point(0,0)},title:e.camera_name,zIndex:google.maps.Marker.MAX_ZINDEX}),n=new google.maps.InfoWindow({content:this.generateCamaraTooltip(e)});a.addListener("click",()=>{this.currentInfoWindow&&this.currentInfoWindow.close(),n.open(this.map,a),this.currentInfoWindow=n}),this.markers.camaras.push(a),console.debug("[OperacionesDashboard] Marker de cámara agregado al mapa",{id:e.id,camera_name:e.camera_name})}createCamaraMarkerIcon(){return`
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
        `}generateEventoTooltip(e){const t=e.estado.toLowerCase().replace(" ","-");return`
            <div class="map-tooltip evento-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-bell-fill tooltip-icon"></i>
                    <span class="tooltip-title">Evento #${e.id}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Cliente:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.cliente)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Registrado por:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.registrado_por||"N/A")}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Estado:</span>
                        <span class="tooltip-badge estado-${t}">${this.escapeHtml(e.estado)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Fecha:</span>
                        <span class="tooltip-value">${e.fecha_hora_formatted||e.fecha_hora}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Categoría:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.categoria)}</span>
                    </div>
                </div>
                <div class="tooltip-footer">
                    <a href="${this.isClient?`/client/eventos/${e.id}/reporte`:`/eventos/${e.id}/reporte`}" class="tooltip-btn" target="_blank">
                        Ver Detalles
                    </a>
                </div>
            </div>
        `}generateVehiculoTooltip(e){return`
            <div class="map-tooltip vehiculo-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-taxi-front-fill tooltip-icon"></i>
                    <span class="tooltip-title">${this.escapeHtml(e.vehicle_name)}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Patente:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.plate_no)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Velocidad:</span>
                        <span class="tooltip-value">${e.speed||0} km/h</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Dirección:</span>
                        <span class="tooltip-value">${e.direction||0}°</span>
                    </div>
                    ${e.occur_time?`
                    <div class="tooltip-row">
                        <span class="tooltip-label">Última posición:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.occur_time)}</span>
                    </div>
                    `:""}
                    <div class="tooltip-row">
                        <span class="tooltip-label">Cliente:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.cliente||"N/A")}</span>
                    </div>
                </div>
                <div class="tooltip-footer">
                    <a href="/client/patrullas/mapa" class="tooltip-btn" target="_blank">
                        Ver mapa detallado
                    </a>
                </div>
            </div>
        `}generateDockTooltip(e){return`
            <div class="map-tooltip dock-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-airplane-fill tooltip-icon"></i>
                    <span class="tooltip-title">${this.escapeHtml(e.nombre)}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Site:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.site)}</span>
                    </div>
                    ${e.drone?`
                    <div class="tooltip-row">
                        <span class="tooltip-label">Drone asignado:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.drone)}</span>
                    </div>
                    `:""}
                    <div class="tooltip-row">
                        <span class="tooltip-label">Estado:</span>
                        <span class="tooltip-value">${e.active?"Activo":"Inactivo"}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Coordenadas:</span>
                        <span class="tooltip-value">${e.latitud.toFixed(6)}, ${e.longitud.toFixed(6)}</span>
                    </div>
                    ${e.altitude?`
                    <div class="tooltip-row">
                        <span class="tooltip-label">Altitud:</span>
                        <span class="tooltip-value">${e.altitude} m</span>
                    </div>
                    `:""}
                </div>
            </div>
        `}generateCamaraTooltip(e){return`
            <div class="map-tooltip camara-tooltip">
                <div class="tooltip-header">
                    <i class="bi bi-camera-video-fill tooltip-icon"></i>
                    <span class="tooltip-title">${this.escapeHtml(e.camera_name)}</span>
                </div>
                <div class="tooltip-body">
                    <div class="tooltip-row">
                        <span class="tooltip-label">Dispositivo:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.dispositivo_nombre)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Cliente:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.cliente)}</span>
                    </div>
                    <div class="tooltip-row">
                        <span class="tooltip-label">Estado:</span>
                        <span class="tooltip-value">${e.status===1?"Activo":"Inactivo"}</span>
                    </div>
                    ${e.direccion_ip?`
                    <div class="tooltip-row">
                        <span class="tooltip-label">IP:</span>
                        <span class="tooltip-value">${e.direccion_ip}</span>
                    </div>
                    `:""}
                    ${e.observaciones?`
                    <div class="tooltip-row">
                        <span class="tooltip-label">Observaciones:</span>
                        <span class="tooltip-value">${this.escapeHtml(String(e.observaciones).substring(0,100))}</span>
                    </div>
                    `:""}
                    ${e.fecha_instalacion_formatted?`
                    <div class="tooltip-row">
                        <span class="tooltip-label">Instalada el:</span>
                        <span class="tooltip-value">${this.escapeHtml(e.fecha_instalacion_formatted)}</span>
                    </div>
                    `:""}
                    <div class="tooltip-row">
                        <span class="tooltip-label">Coordenadas:</span>
                        <span class="tooltip-value">${e.latitud.toFixed(6)}, ${e.longitud.toFixed(6)}</span>
                    </div>
                </div>
                <div class="tooltip-footer">
                    <a href="/cameras/stream/${e.camera_index_code}" class="tooltip-btn" target="_blank">
                        Ver Liveview
                    </a>
                </div>
            </div>
        `}fitMapToMarkers(){if(!this.map)return;const e=new google.maps.LatLngBounds;let t=!1;Object.values(this.markers).forEach(o=>{o.forEach(s=>{e.extend(s.getPosition()),t=!0})}),t&&(this.map.fitBounds(e),google.maps.event.addListenerOnce(this.map,"bounds_changed",()=>{this.map.getZoom()>15&&this.map.setZoom(15)}))}setupFilters(){const e=document.getElementById("map-type-satellite"),t=document.getElementById("map-type-roadmap");if(e&&e.addEventListener("click",()=>{this.changeMapType("satellite"),e.classList.add("active"),t&&t.classList.remove("active")}),t&&t.addEventListener("click",()=>{this.changeMapType("roadmap"),t.classList.add("active"),e&&e.classList.remove("active")}),e&&e.classList.add("active"),document.querySelectorAll("[data-estado]").forEach(s=>{s.addEventListener("click",()=>{s.dataset.estado,s.classList.toggle("active");const a=Array.from(document.querySelectorAll("[data-estado].active")).map(n=>n.dataset.estado);this.applyFilters({estado_evento:a})})}),!this.isClient){const s=document.getElementById("filter-cliente");s&&s.addEventListener("change",a=>{const n=a.target.value||null;this.applyFilters({cliente_id:n})})}}changeMapType(e){this.map&&(this.filters.map_type=e,this.map.setMapTypeId(e==="satellite"?google.maps.MapTypeId.SATELLITE:google.maps.MapTypeId.ROADMAP))}applyFilters(e){clearTimeout(this.filterDebounceTimer),this.filterDebounceTimer=setTimeout(()=>{this.filters={...this.filters,...e},this.loadMapData(this.filters),this.currentEventosPage=1,this.loadEventos(this.filters,1),this.loadKPIs()},300)}async loadEventos(e={},t=1){try{const o=new URLSearchParams;(e.cliente_id||this.filters.cliente_id)&&o.append("cliente_id",e.cliente_id||this.filters.cliente_id),(e.estado_evento||this.filters.estado_evento.length>0)&&(e.estado_evento||this.filters.estado_evento).forEach(r=>{o.append("estado_evento[]",r)}),o.append("page",t);const s=document.getElementById("eventos-list-content");s&&(s.innerHTML='<div class="eventos-loading"><div class="loading-spinner"></div><p>Cargando eventos...</p></div>');const n=await(await fetch(`/api/operaciones/eventos?${o}`)).json();n.success?(this.renderEventos(n.data,n.pagination),this.currentEventosPage=t,this.currentEventosPagination=n.pagination):this.showEventosError("Error al cargar eventos")}catch(o){console.error("Error cargando eventos:",o),this.showEventosError("Error al cargar eventos. Por favor, recarga la página.")}}renderEventos(e,t=null){const o=document.getElementById("eventos-list-content");if(!o)return;if(!e||e.length===0){o.innerHTML=`
                <div class="eventos-empty">
                    <i class="bi bi-inbox" style="font-size: 48px; color: var(--fb-text-secondary); margin-bottom: 16px;"></i>
                    <p style="color: var(--fb-text-secondary);">No hay eventos disponibles</p>
                    <p style="color: var(--fb-text-secondary); font-size: 12px; margin-top: 8px;">
                        ${this.filters.estado_evento.length>0?"Intenta cambiar los filtros de estado":"No se encontraron eventos con los filtros aplicados"}
                    </p>
                </div>
            `,this.hidePagination();return}const s=[...e].sort((a,n)=>{const i=new Date(a.fecha_hora);return new Date(n.fecha_hora)-i});o.innerHTML=s.map(a=>{const n=a.estado.toLowerCase().replace(/\s+/g,"-"),i=a.descripcion||"Sin descripción",r=i.length>100?i.substring(0,100)+"...":i;return`
                <div class="evento-card" data-event-id="${a.id}" data-lat="${a.latitud||""}" data-lng="${a.longitud||""}">
                    <div class="evento-header">
                        <span class="evento-cliente-tag">${this.escapeHtml(a.cliente)}</span>
                        <span class="evento-estado-badge ${n}">${this.escapeHtml(a.estado)}</span>
                    </div>
                    <div class="evento-body">
                        <div class="evento-categoria">
                            <i class="bi bi-tag-fill" style="font-size: 12px; margin-right: 4px;"></i>
                            ${this.escapeHtml(a.categoria)}
                        </div>
                        <div class="evento-descripcion">${this.escapeHtml(r)}</div>
                        <div class="evento-fecha">
                            <i class="bi bi-clock" style="font-size: 11px; margin-right: 4px;"></i>
                            ${a.fecha_hora_formatted||this.formatFecha(a.fecha_hora)}
                        </div>
                        ${a.latitud&&a.longitud?`
                        <div class="evento-ubicacion" style="font-size: 11px; color: var(--fb-text-secondary); margin-top: 4px;">
                            <i class="bi bi-geo-alt-fill" style="font-size: 10px; margin-right: 4px;"></i>
                            Con ubicación
                        </div>
                        `:""}
                    </div>
                    <div class="evento-footer">
                        <button class="evento-footer-btn" onclick="event.stopPropagation(); window.open('${this.isClient?`/client/eventos/${a.id}/reporte`:`/eventos/${a.id}/reporte`}', '_blank')">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            `}).join(""),o.querySelectorAll(".evento-card").forEach(a=>{a.addEventListener("click",n=>{if(n.target.closest(".evento-footer-btn"))return;const i=parseFloat(a.dataset.lat),r=parseFloat(a.dataset.lng);if(i&&r&&!isNaN(i)&&!isNaN(r)&&this.map){this.map.setCenter({lat:i,lng:r}),this.map.setZoom(15);const c=parseInt(a.dataset.eventId),l=this.markers.eventos.find(d=>{const p=d.eventoData;return p&&p.id===c});l&&google.maps.event.trigger(l,"click")}})}),t&&t.last_page>1?this.showPagination(t):this.hidePagination()}showPagination(e){const t=document.getElementById("eventos-pagination"),o=document.getElementById("prev-page-btn"),s=document.getElementById("next-page-btn"),a=document.getElementById("pagination-info");if(!t||!o||!s||!a)return;t.style.display="flex",o.disabled=e.current_page<=1,s.disabled=e.current_page>=e.last_page,a.textContent=`Página ${e.current_page} de ${e.last_page} (${e.total} total)`;const n=o.cloneNode(!0),i=s.cloneNode(!0);o.parentNode.replaceChild(n,o),s.parentNode.replaceChild(i,s),n.addEventListener("click",()=>{e.current_page>1&&this.loadEventos(this.filters,e.current_page-1)}),i.addEventListener("click",()=>{e.current_page<e.last_page&&this.loadEventos(this.filters,e.current_page+1)})}hidePagination(){const e=document.getElementById("eventos-pagination");e&&(e.style.display="none")}showEventosError(e){const t=document.getElementById("eventos-list-content");t&&(t.innerHTML=`
                <div class="eventos-error">
                    <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #ef4444; margin-bottom: 16px;"></i>
                    <p style="color: var(--fb-text-secondary);">${e}</p>
                </div>
            `)}formatFecha(e){try{const t=new Date(e),o=String(t.getDate()).padStart(2,"0"),s=String(t.getMonth()+1).padStart(2,"0"),a=t.getFullYear(),n=String(t.getHours()).padStart(2,"0"),i=String(t.getMinutes()).padStart(2,"0");return`${o}/${s}/${a} ${n}:${i}`}catch{return e}}setupControls(){const e=document.getElementById("new-tab-btn");e&&e.addEventListener("click",()=>{window.open(window.location.href,"_blank")});const t=document.getElementById("fullscreen-btn");t&&t.addEventListener("click",()=>{this.toggleFullscreen()});const o=document.getElementById("expand-map-btn");o&&o.addEventListener("click",()=>{this.toggleMapFullscreen()});const s=document.getElementById("refresh-eventos-btn");s&&s.addEventListener("click",()=>{this.loadEventos(this.filters,this.currentEventosPage||1)})}toggleFullscreen(){const e=document.querySelector(".operaciones-dashboard"),t=document.querySelector(".modern-top-nav"),o=document.querySelector(".modern-sidebar");this.isFullscreen?(t&&(t.style.display="flex"),o&&(o.style.display="block"),e&&e.classList.remove("fullscreen-mode"),document.body.style.padding="",document.body.style.margin="",this.isFullscreen=!1,this.updateFullscreenButton()):(t&&(t.style.display="none"),o&&(o.style.display="none"),e&&e.classList.add("fullscreen-mode"),document.body.style.padding="0",document.body.style.margin="0",this.isFullscreen=!0,this.updateFullscreenButton())}toggleMapFullscreen(){const e=document.getElementById("operaciones-map-container");e&&(document.fullscreenElement?document.exitFullscreen():e.requestFullscreen().then(()=>{this.map&&google.maps.event.trigger(this.map,"resize")}).catch(t=>{console.error("Error entrando en pantalla completa:",t)}))}updateFullscreenButton(){const e=document.getElementById("fullscreen-btn");if(!e)return;const t=e.querySelector("i");t&&(this.isFullscreen?(t.className="bi bi-fullscreen-exit",e.title="Salir de pantalla completa"):(t.className="bi bi-arrows-fullscreen",e.title="Pantalla completa"))}startAutoRefresh(){this.refreshInterval=setInterval(()=>{this.loadKPIs(),this.loadMapData(this.filters),this.loadEventos(this.filters,this.currentEventosPage||1)},3e4)}stopAutoRefresh(){this.refreshInterval&&(clearInterval(this.refreshInterval),this.refreshInterval=null)}showLoadingState(){}hideLoadingState(){}showErrorMessage(e){const t=document.createElement("div");t.className="dashboard-error-message",t.textContent=e,document.body.appendChild(t),setTimeout(()=>{t.remove()},5e3)}escapeHtml(e){const t=document.createElement("div");return t.textContent=e,t.innerHTML}}window.initOperacionesMap=function(){if(console.log("[OperacionesDashboard] Callback de Google Maps ejecutado"),window.operacionesDashboard)console.log("[OperacionesDashboard] Inicializando dashboard con Google Maps listo"),window.operacionesDashboard.setup();else{console.warn("[OperacionesDashboard] Dashboard no inicializado, creando nueva instancia");const v=new y;window.operacionesDashboard=v,v.setup()}};
