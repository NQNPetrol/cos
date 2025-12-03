<template>
    <div class="relative" :style="{ height: height }">
        <canvas ref="chartCanvas"></canvas>
        
        <!-- Loading overlay -->
        <div v-if="loading" class="absolute inset-0 bg-gray-800/80 rounded-2xl flex items-center justify-center">
            <div class="flex flex-col items-center gap-3">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-t-transparent" :class="loadingColorClass"></div>
                <span class="text-gray-300 text-sm">Cargando datos...</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import {
    Chart,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Title,
    Tooltip,
    Legend
);

const props = defineProps({
    apiUrl: {
        type: String,
        required: true
    },
    height: {
        type: String,
        default: '350px'
    },
    colorScheme: {
        type: String,
        default: 'blue' // 'blue' o 'violet'
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

const chartCanvas = ref(null);
const loading = ref(false);
let chartInstance = null;

// Paletas de colores
const colorSchemes = {
    blue: {
        backgrounds: [
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(6, 182, 212, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(34, 197, 94, 0.8)',
            'rgba(168, 85, 247, 0.8)',
        ],
        borders: [
            'rgba(59, 130, 246, 1)',
            'rgba(16, 185, 129, 1)',
            'rgba(245, 158, 11, 1)',
            'rgba(239, 68, 68, 1)',
            'rgba(139, 92, 246, 1)',
            'rgba(236, 72, 153, 1)',
            'rgba(6, 182, 212, 1)',
            'rgba(249, 115, 22, 1)',
            'rgba(34, 197, 94, 1)',
            'rgba(168, 85, 247, 1)',
        ],
        loadingClass: 'border-blue-500'
    },
    violet: {
        backgrounds: [
            'rgba(139, 92, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(244, 114, 182, 0.8)',
            'rgba(192, 132, 252, 0.8)',
            'rgba(232, 121, 249, 0.8)',
            'rgba(167, 139, 250, 0.8)',
            'rgba(251, 146, 60, 0.8)',
            'rgba(74, 222, 128, 0.8)',
            'rgba(56, 189, 248, 0.8)',
        ],
        borders: [
            'rgba(139, 92, 246, 1)',
            'rgba(236, 72, 153, 1)',
            'rgba(168, 85, 247, 1)',
            'rgba(244, 114, 182, 1)',
            'rgba(192, 132, 252, 1)',
            'rgba(232, 121, 249, 1)',
            'rgba(167, 139, 250, 1)',
            'rgba(251, 146, 60, 1)',
            'rgba(74, 222, 128, 1)',
            'rgba(56, 189, 248, 1)',
        ],
        loadingClass: 'border-violet-500'
    }
};

const currentColors = computed(() => colorSchemes[props.colorScheme] || colorSchemes.blue);
const loadingColorClass = computed(() => currentColors.value.loadingClass);

const fetchData = async () => {
    loading.value = true;
    
    try {
        const params = new URLSearchParams();
        if (props.fechaDesde) params.append('fecha_desde', props.fechaDesde);
        if (props.fechaHasta) params.append('fecha_hasta', props.fechaHasta);
        
        const url = params.toString() ? `${props.apiUrl}?${params.toString()}` : props.apiUrl;
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        
        return await response.json();
    } catch (error) {
        console.error('Error al cargar datos:', error);
        return [];
    } finally {
        loading.value = false;
    }
};

const createChart = (data) => {
    const colors = currentColors.value;
    
    const config = {
        type: 'bar',
        data: {
            labels: data.map(item => item.nombre),
            datasets: [{
                label: 'Eventos',
                data: data.map(item => item.total),
                backgroundColor: data.map((_, i) => colors.backgrounds[i % colors.backgrounds.length]),
                borderColor: data.map((_, i) => colors.borders[i % colors.borders.length]),
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#9ca3af',
                    borderColor: 'rgba(75, 85, 99, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                            return `${context.raw} eventos (${percentage}%)`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 11 },
                        stepSize: 1
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    };

    if (chartInstance) {
        chartInstance.destroy();
    }

    chartInstance = new Chart(chartCanvas.value, config);
};

const updateChart = async () => {
    const data = await fetchData();
    
    if (chartInstance) {
        const colors = currentColors.value;
        chartInstance.data.labels = data.map(item => item.nombre);
        chartInstance.data.datasets[0].data = data.map(item => item.total);
        chartInstance.data.datasets[0].backgroundColor = data.map((_, i) => colors.backgrounds[i % colors.backgrounds.length]);
        chartInstance.data.datasets[0].borderColor = data.map((_, i) => colors.borders[i % colors.borders.length]);
        chartInstance.update('active');
    }
};

// Watchers para actualizar cuando cambien las fechas
watch(() => props.fechaDesde, updateChart);
watch(() => props.fechaHasta, updateChart);

onMounted(async () => {
    const data = await fetchData();
    createChart(data);
});

// Exponer método para actualizar desde el padre
defineExpose({ updateChart });
</script>






