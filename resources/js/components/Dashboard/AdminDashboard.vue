<template>
  <div class="admin-dashboard">
    <!-- Filter Panel -->
    <div class="filter-panel">
      <div class="filter-group">
        <label class="filter-label">Mes</label>
        <select v-model="selectedMonth" @change="fetchMonthlyData" class="filter-select">
          <option v-for="(name, idx) in months" :key="idx" :value="idx + 1">{{ name }}</option>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Año</label>
        <select v-model="selectedYear" @change="fetchMonthlyData" class="filter-select">
          <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
        </select>
      </div>
      <button @click="resetFilters" class="filter-reset-btn">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Hoy
      </button>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
      <div class="kpi-card kpi-blue">
        <div class="kpi-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17l-5.08 3.05a.75.75 0 01-1.08-.8l.97-5.67-4.12-4.01a.75.75 0 01.42-1.28l5.69-.83 2.54-5.16a.75.75 0 011.36 0l2.54 5.16 5.69.83a.75.75 0 01.42 1.28l-4.12 4.01.97 5.67a.75.75 0 01-1.08.8l-5.08-3.05z"/></svg>
        </div>
        <div class="kpi-content">
          <div class="kpi-value">{{ kpis.totalServices }}</div>
          <div class="kpi-label">Services Totales</div>
        </div>
      </div>
      <div class="kpi-card kpi-amber">
        <div class="kpi-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        </div>
        <div class="kpi-content">
          <div class="kpi-value">{{ kpis.totalTurnosMecanicos }}</div>
          <div class="kpi-label">Turnos Mecánicos</div>
        </div>
      </div>
      <div class="kpi-card kpi-emerald">
        <div class="kpi-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="kpi-content">
          <div class="kpi-value">{{ kpis.totalCambiosEquipo }}</div>
          <div class="kpi-label">Cambios de Equipo</div>
        </div>
      </div>
      <div class="kpi-card kpi-purple">
        <div class="kpi-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
        </div>
        <div class="kpi-content">
          <div class="kpi-value">{{ kpis.totalRodados }}</div>
          <div class="kpi-label">Total Vehículos</div>
        </div>
      </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="charts-grid-2">
      <div class="chart-card">
        <h3 class="chart-title">Ingresos vs Egresos Mensuales</h3>
        <canvas ref="ingresosEgresosChart"></canvas>
      </div>
      <div class="chart-card">
        <div class="chart-header">
          <h3 class="chart-title">Evolución Flota vs Ingresos</h3>
          <select v-model="annualYear" @change="fetchAnnualData" class="filter-select filter-select-sm">
            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
          </select>
        </div>
        <canvas ref="flotaIngresosChart"></canvas>
      </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="charts-grid-2">
      <div class="chart-card">
        <h3 class="chart-title">Turnos Mensuales por Vehículo</h3>
        <canvas ref="turnosPorVehiculoChart"></canvas>
      </div>
      <div class="chart-card">
        <h3 class="chart-title">Top 5 Vehículos por Kilometraje</h3>
        <canvas ref="topKmChart"></canvas>
      </div>
    </div>

    <!-- Cards Row: Upcoming -->
    <div class="charts-grid-2">
      <div class="upcoming-card">
        <h3 class="upcoming-title">
          <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Próximos Turnos (7 días)
        </h3>
        <div v-if="proximosTurnos.length" class="upcoming-list">
          <div v-for="turno in proximosTurnos" :key="turno.id" class="upcoming-item">
            <div class="upcoming-item-left">
              <span class="upcoming-patente">{{ turno.patente }}</span>
              <span class="upcoming-detail">{{ turno.taller }} - {{ turno.tipo }}</span>
            </div>
            <span class="upcoming-date text-blue-400">{{ turno.fecha }}</span>
          </div>
        </div>
        <div v-else class="upcoming-empty">No hay turnos próximos.</div>
      </div>
      <div class="upcoming-card">
        <h3 class="upcoming-title">
          <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Próximos Vencimientos (7 días)
        </h3>
        <div v-if="pagosProximos.length" class="upcoming-list">
          <div v-for="pago in pagosProximos" :key="pago.id" class="upcoming-item">
            <div class="upcoming-item-left">
              <span class="upcoming-patente">{{ pago.patente }}</span>
              <span class="upcoming-detail">${{ pago.monto }} {{ pago.moneda }}</span>
            </div>
            <span class="upcoming-date text-red-400">{{ pago.fecha }}</span>
          </div>
        </div>
        <div v-else class="upcoming-empty">No hay pagos próximos a vencer.</div>
      </div>
    </div>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
  name: 'AdminDashboard',
  props: {
    apiBaseUrl: { type: String, required: true },
  },
  data() {
    const now = new Date();
    const currentYear = now.getFullYear();
    return {
      selectedMonth: now.getMonth() + 1,
      selectedYear: currentYear,
      annualYear: currentYear,
      months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
      availableYears: Array.from({length: 5}, (_, i) => currentYear - i),
      kpis: { totalServices: 0, totalTurnosMecanicos: 0, totalCambiosEquipo: 0, totalRodados: 0 },
      proximosTurnos: [],
      pagosProximos: [],
      charts: {},
    };
  },
  mounted() {
    this.fetchMonthlyData();
    this.fetchAnnualData();
    this.fetchUpcoming();
  },
  methods: {
    resetFilters() {
      const now = new Date();
      this.selectedMonth = now.getMonth() + 1;
      this.selectedYear = now.getFullYear();
      this.fetchMonthlyData();
    },
    async fetchMonthlyData() {
      try {
        const params = `?mes=${this.selectedMonth}&anio=${this.selectedYear}`;
        const [kpis, ingEgr, turnos, topKm] = await Promise.all([
          fetch(`${this.apiBaseUrl}/kpis${params}`).then(r => r.json()),
          fetch(`${this.apiBaseUrl}/ingresos-egresos?anio=${this.selectedYear}`).then(r => r.json()),
          fetch(`${this.apiBaseUrl}/turnos-por-vehiculo${params}`).then(r => r.json()),
          fetch(`${this.apiBaseUrl}/top-km${params}`).then(r => r.json()),
        ]);
        this.kpis = kpis;
        this.renderIngresosEgresos(ingEgr);
        this.renderTurnosPorVehiculo(turnos);
        this.renderTopKm(topKm);
      } catch(e) { console.error('Error fetching monthly data:', e); }
    },
    async fetchAnnualData() {
      try {
        const data = await fetch(`${this.apiBaseUrl}/flota-ingresos?anio=${this.annualYear}`).then(r => r.json());
        this.renderFlotaIngresos(data);
      } catch(e) { console.error('Error fetching annual data:', e); }
    },
    async fetchUpcoming() {
      try {
        const data = await fetch(`${this.apiBaseUrl}/upcoming`).then(r => r.json());
        this.proximosTurnos = data.turnos || [];
        this.pagosProximos = data.pagos || [];
      } catch(e) { console.error('Error fetching upcoming:', e); }
    },
    destroyChart(name) {
      if (this.charts[name]) { this.charts[name].destroy(); this.charts[name] = null; }
    },
    chartColors() {
      return {
        grid: '#3f3f46', text: '#a1a1aa',
        blue: 'rgb(59,130,246)', blueAlpha: 'rgba(59,130,246,0.15)',
        red: 'rgb(239,68,68)', redAlpha: 'rgba(239,68,68,0.15)',
        green: 'rgb(34,197,94)', greenAlpha: 'rgba(34,197,94,0.15)',
        amber: 'rgb(245,158,11)', amberAlpha: 'rgba(245,158,11,0.15)',
        purple: 'rgb(168,85,247)',
      };
    },
    defaultScales() {
      const c = this.chartColors();
      return { x: { ticks: { color: c.text }, grid: { color: c.grid } }, y: { ticks: { color: c.text }, grid: { color: c.grid } } };
    },
    renderIngresosEgresos(data) {
      this.destroyChart('ingresosEgresos');
      const c = this.chartColors();
      this.charts.ingresosEgresos = new Chart(this.$refs.ingresosEgresosChart, {
        type: 'line',
        data: {
          labels: data.meses,
          datasets: [
            { label: 'Ingresos (Cobranzas)', data: data.ingresos, borderColor: c.green, backgroundColor: c.greenAlpha, fill: true, tension: 0.3 },
            { label: 'Egresos (Pagos)', data: data.egresos, borderColor: c.red, backgroundColor: c.redAlpha, fill: true, tension: 0.3 },
          ],
        },
        options: { responsive: true, plugins: { legend: { labels: { color: c.text } } }, scales: this.defaultScales() },
      });
    },
    renderFlotaIngresos(data) {
      this.destroyChart('flotaIngresos');
      const c = this.chartColors();
      this.charts.flotaIngresos = new Chart(this.$refs.flotaIngresosChart, {
        type: 'line',
        data: {
          labels: data.meses,
          datasets: [
            { label: 'Vehículos', data: data.rodados, borderColor: c.blue, backgroundColor: c.blueAlpha, fill: false, tension: 0.3, yAxisID: 'y' },
            { label: 'Ingresos ($)', data: data.ingresos, borderColor: c.green, backgroundColor: c.greenAlpha, fill: true, tension: 0.3, yAxisID: 'y1' },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { labels: { color: c.text } } },
          scales: {
            x: { ticks: { color: c.text }, grid: { color: c.grid } },
            y: { type: 'linear', position: 'left', title: { display: true, text: 'Vehículos', color: c.text }, ticks: { color: c.text }, grid: { color: c.grid } },
            y1: { type: 'linear', position: 'right', title: { display: true, text: 'Ingresos ($)', color: c.text }, ticks: { color: c.text }, grid: { drawOnChartArea: false } },
          },
        },
      });
    },
    renderTurnosPorVehiculo(data) {
      this.destroyChart('turnosPorVehiculo');
      const c = this.chartColors();
      this.charts.turnosPorVehiculo = new Chart(this.$refs.turnosPorVehiculoChart, {
        type: 'bar',
        data: {
          labels: data.map(d => d.patente),
          datasets: [{ label: 'Turnos', data: data.map(d => d.total), backgroundColor: c.amberAlpha, borderColor: c.amber, borderWidth: 1 }],
        },
        options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: this.defaultScales() },
      });
    },
    renderTopKm(data) {
      this.destroyChart('topKm');
      const c = this.chartColors();
      this.charts.topKm = new Chart(this.$refs.topKmChart, {
        type: 'bar',
        data: {
          labels: data.map(d => d.patente),
          datasets: [{ label: 'Km', data: data.map(d => d.kilometraje), backgroundColor: 'rgba(168,85,247,0.3)', borderColor: c.purple, borderWidth: 1 }],
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: this.defaultScales() },
      });
    },
  },
};
</script>

<style scoped>
.admin-dashboard { display: flex; flex-direction: column; gap: 1.5rem; }
.filter-panel { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: #18181b; border: 1px solid #3f3f46; border-radius: 1rem; }
.filter-group { display: flex; flex-direction: column; gap: 0.25rem; }
.filter-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #a1a1aa; }
.filter-select { background: #27272a; border: 1px solid #3f3f46; border-radius: 0.5rem; color: #e4e4e7; padding: 0.4rem 0.75rem; font-size: 0.875rem; outline: none; }
.filter-select:focus { border-color: #3b82f6; }
.filter-select-sm { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
.filter-reset-btn { display: flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; background: #27272a; border: 1px solid #3f3f46; border-radius: 0.5rem; color: #a1a1aa; font-size: 0.8rem; cursor: pointer; transition: all 0.15s; margin-left: auto; }
.filter-reset-btn:hover { background: #3f3f46; color: #e4e4e7; }
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
.kpi-card { display: flex; align-items: center; gap: 1rem; padding: 1.25rem; border-radius: 1rem; border: 1px solid; }
.kpi-blue { background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.2); }
.kpi-amber { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.2); }
.kpi-emerald { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); }
.kpi-purple { background: rgba(168,85,247,0.08); border-color: rgba(168,85,247,0.2); }
.kpi-icon { width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center; border-radius: 0.75rem; flex-shrink: 0; }
.kpi-blue .kpi-icon { background: rgba(59,130,246,0.15); color: #3b82f6; }
.kpi-amber .kpi-icon { background: rgba(245,158,11,0.15); color: #f59e0b; }
.kpi-emerald .kpi-icon { background: rgba(16,185,129,0.15); color: #10b981; }
.kpi-purple .kpi-icon { background: rgba(168,85,247,0.15); color: #a855f7; }
.kpi-icon svg { width: 1.25rem; height: 1.25rem; }
.kpi-value { font-size: 1.75rem; font-weight: 700; color: #f4f4f5; }
.kpi-label { font-size: 0.75rem; color: #a1a1aa; margin-top: 0.125rem; }
.charts-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
.chart-card { background: #18181b; border: 1px solid #3f3f46; border-radius: 1rem; padding: 1.25rem; }
.chart-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; }
.chart-title { font-size: 0.95rem; font-weight: 600; color: #e4e4e7; margin-bottom: 0.75rem; }
.chart-header .chart-title { margin-bottom: 0; }
.upcoming-card { background: #18181b; border: 1px solid #3f3f46; border-radius: 1rem; padding: 1.25rem; }
.upcoming-title { display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; font-weight: 600; color: #e4e4e7; margin-bottom: 1rem; }
.upcoming-list { display: flex; flex-direction: column; gap: 0.5rem; }
.upcoming-item { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #09090b; border-radius: 0.75rem; }
.upcoming-item-left { display: flex; flex-direction: column; }
.upcoming-patente { font-weight: 600; color: #e4e4e7; font-size: 0.875rem; }
.upcoming-detail { font-size: 0.75rem; color: #71717a; margin-top: 0.125rem; }
.upcoming-date { font-size: 0.8rem; font-weight: 500; }
.upcoming-empty { color: #71717a; font-size: 0.85rem; text-align: center; padding: 1rem; }
@media (max-width: 1024px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } .charts-grid-2 { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .kpi-grid { grid-template-columns: 1fr; } .filter-panel { flex-wrap: wrap; } }
</style>
