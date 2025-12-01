<template>
  <div class="heatmap-fixed" :style="{ height: height }">
    <!-- Contenedor del mapa -->
    <div ref="mapContainer" class="w-full h-full rounded-lg bg-gray-800"></div>
    
    <!-- Estado de carga -->
    <div v-if="loading" class="absolute inset-0 bg-gray-900/90 rounded-lg flex items-center justify-center">
      <div class="text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent mx-auto mb-4"></div>
        <p class="text-gray-300">{{ loadingMessage }}</p>
      </div>
    </div>
    
    
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  apiUrl: {
    type: String,
    required: true
  },
  height: {
    type: String,
    default: '400px'
  },
  fechaDesde: {
    type: String,
    default: ''
  },
  fechaHasta: {
    type: String,
    default: ''
  }
});

console.log('🗺️ HeatmapChartFixed iniciado');

const mapContainer = ref(null);
const map = ref(null);
const heatLayer = ref(null);
const loading = ref(true);
const status = ref('Iniciando...');
const loadingMessage = ref('Cargando mapa...');

// Variable global para Leaflet
let L = window.L;

onMounted(async () => {
  console.log('🎯 HeatmapChartFixed montado');
  
  // Esperar a que Leaflet esté disponible
  await waitForLeaflet();
  
  // Inicializar el mapa
  initMap();
  
  // Cargar datos
  await loadHeatmapData();
});

onUnmounted(() => {
  if (map.value) {
    map.value.remove();
  }
});

// Función para esperar a que Leaflet esté disponible
const waitForLeaflet = () => {
  return new Promise((resolve) => {
    const checkLeaflet = () => {
      if (typeof window.L !== 'undefined') {
        L = window.L;
        console.log('✅ Leaflet disponible:', L.version);
        status.value = 'Leaflet cargado';
        resolve();
      } else {
        console.log('⏳ Esperando Leaflet...');
        status.value = 'Esperando Leaflet...';
        loadingMessage.value = 'Cargando biblioteca de mapas...';
        setTimeout(checkLeaflet, 100);
      }
    };
    checkLeaflet();
  });
};

const initMap = () => {
  try {
    status.value = 'Creando mapa...';
    loadingMessage.value = 'Inicializando mapa...';
    
    // Configurar iconos de Leaflet
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
      iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
      iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    });
    
    // Crear mapa centrado en la ubicación de los datos
    const center = [-38.8827, -68.0447]; // Coordenadas de Neuquén según tus datos
    map.value = L.map(mapContainer.value).setView(center, 12);
    
    // Añadir capa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 18
    }).addTo(map.value);
    
    console.log('Mapa creado');
    status.value = 'Mapa listo';
    
  } catch (error) {
    console.error('Error inicializando mapa:', error);
    status.value = 'Error: ' + error.message;
    loading.value = false;
  }
};

const loadHeatmapData = async () => {
  try {
    status.value = 'Cargando datos...';
    loadingMessage.value = 'Obteniendo datos de eventos...';
    
    const params = new URLSearchParams();
    if (props.fechaDesde) params.append('fecha_desde', props.fechaDesde);
    if (props.fechaHasta) params.append('fecha_hasta', props.fechaHasta);
    
    const url = params.toString() ? `${props.apiUrl}?${params.toString()}` : props.apiUrl;
    console.log('📡 Solicitando datos a:', url);
    
    const response = await fetch(url);
    const data = await response.json();
    
    console.log('Datos recibidos:', data);
    
    if (!Array.isArray(data) || data.length === 0) {
      status.value = 'No hay datos de eventos';
      loading.value = false;
      
      // Añadir marcador de ejemplo
      L.marker([-38.8827, -68.0447])
        .addTo(map.value)
        .bindPopup('No hay eventos con ubicación')
        .openPopup();
      
      return;
    }
    
    // Crear puntos para el heatmap
    const heatPoints = data.map(event => [event.lat, event.lng, event.count || 1]);
    
    // Verificar si leaflet.heat está disponible
    if (typeof L.heatLayer === 'function') {
      // Crear heatmap
      heatLayer.value = L.heatLayer(heatPoints, {
        radius: 30,
        blur: 20,
        maxZoom: 17,
        gradient: {
          0.4: 'blue',
          0.6: 'cyan',
          0.7: 'lime',
          0.8: 'yellow',
          1.0: 'red'
        },
        minOpacity: 0.5
      }).addTo(map.value);
      
      console.log('✅ Heatmap creado');
      status.value = 'Heatmap cargado';
    } else {
      console.warn('⚠️ L.heatLayer no disponible, usando marcadores');
      status.value = 'Usando marcadores (heatmap no disponible)';
      
      // Usar marcadores circulares como alternativa
      data.forEach(event => {
        L.circleMarker([event.lat, event.lng], {
          radius: Math.min(event.count * 2, 20),
          fillColor: '#ff4444',
          color: '#fff',
          weight: 1,
          opacity: 0.8,
          fillOpacity: 0.6
        })
        .addTo(map.value)
        .bindPopup(`<b>${event.count} eventos</b><br>Lat: ${event.lat}<br>Lng: ${event.lng}`);
      });
    }
    
    // Ajustar vista para mostrar todos los puntos
    const bounds = L.latLngBounds(heatPoints.map(point => [point[0], point[1]]));
    map.value.fitBounds(bounds, { padding: [50, 50] });
    
    // Añadir marcador principal
    L.marker([data[0].lat, data[0].lng])
      .addTo(map.value)
      .bindPopup(`<b>${data[0].count} eventos</b><br>Lat: ${data[0].lat}<br>Lng: ${data[0].lng}`)
      .openPopup();
    
    loading.value = false;
    status.value = 'Completado';
    
  } catch (error) {
    console.error('❌ Error cargando datos:', error);
    status.value = 'Error cargando datos';
    loading.value = false;
  }
};
</script>

<style scoped>
.heatmap-fixed {
  position: relative;
  width: 100%;
}

.heatmap-fixed >>> .leaflet-container {
  background-color: #1f2937; /* gray-800 */
  border-radius: 0.5rem;
  z-index: 1;
}
</style>