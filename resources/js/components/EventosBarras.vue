<!-- resources/js/Components/EventBarChart.vue -->
<template>
  <div>
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
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

const chartCanvas = ref(null);

onMounted(async () => {
  // 1. Traer datos
  const res = await fetch('/api/eventos/barras', {
    headers: { 'Accept': 'application/json' }
  });
  const raw = await res.json();

  // 2. Preparar labels y datos
  const labels = raw.map(item => item.date);
  const data = raw.map(item => item.count);

  // 3. Configurar el chart
  new Chart(chartCanvas.value, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Eventos por Fecha',
        data,
        backgroundColor: '#3b82f6',
        borderColor: '#1e40af',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: { display: true, text: 'Fecha' }
        },
        y: {
          title: { display: true, text: 'Cantidad de Eventos' },
          beginAtZero: true,
          ticks: { precision: 0 }
        }
      },
      plugins: {
        legend: { position: 'top' },
        title: {
          display: true,
          text: 'Gráfico de Barras de Eventos por Fecha'
        }
      }
    }
  });
});
</script>
