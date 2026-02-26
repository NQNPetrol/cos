<?php

namespace App\Livewire\Rodados;

use App\Models\CambioEquipoRodado;
use App\Models\PagoServiciosRodado;
use App\Models\TurnoRodado;
use Carbon\Carbon;
use Livewire\Component;

class CalendarioRodados extends Component
{
    public $currentDate;

    public $viewMode = 'month'; // 'month', 'week', 'day'

    public $selectedDate = null;

    public $selectedEvent = null;

    public $showModal = false;

    public function mount()
    {
        $this->currentDate = Carbon::now()->format('Y-m-d');
    }

    public function previousPeriod()
    {
        $date = Carbon::parse($this->currentDate);
        if ($this->viewMode === 'month') {
            $date->subMonth();
        } elseif ($this->viewMode === 'week') {
            $date->subWeek();
        } else {
            $date->subDay();
        }
        $this->currentDate = $date->format('Y-m-d');
    }

    public function nextPeriod()
    {
        $date = Carbon::parse($this->currentDate);
        if ($this->viewMode === 'month') {
            $date->addMonth();
        } elseif ($this->viewMode === 'week') {
            $date->addWeek();
        } else {
            $date->addDay();
        }
        $this->currentDate = $date->format('Y-m-d');
    }

    public function goToToday()
    {
        $this->currentDate = Carbon::now()->format('Y-m-d');
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function selectEvent($tipo, $id)
    {
        $this->selectedEvent = ['tipo' => $tipo, 'id' => $id];
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedEvent = null;
    }

    public function getEvents()
    {
        $currentDate = Carbon::parse($this->currentDate);
        $startDate = null;
        $endDate = null;

        if ($this->viewMode === 'month') {
            $startDate = $currentDate->copy()->startOfMonth()->startOfDay();
            $endDate = $currentDate->copy()->endOfMonth()->endOfDay();
        } elseif ($this->viewMode === 'week') {
            $startDate = $currentDate->copy()->startOfWeek()->startOfDay();
            $endDate = $currentDate->copy()->endOfWeek()->endOfDay();
        } else {
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
        }

        $events = collect();

        // Obtener turnos
        $turnos = TurnoRodado::with(['rodado', 'taller'])
            ->whereBetween('fecha_hora', [$startDate, $endDate])
            ->get();

        foreach ($turnos as $turno) {
            $color = '#3b82f6'; // Azul por defecto
            if ($turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE) {
                $color = '#3b82f6'; // Azul
            } elseif ($turno->tipo === TurnoRodado::TIPO_TURNO_MECANICO) {
                $color = '#f97316'; // Naranja
            }

            $events->push([
                'id' => 'turno_'.$turno->id,
                'tipo' => 'turno',
                'tipo_servicio' => $turno->tipo,
                'title' => 'Turno: '.($turno->rodado->patente ?? 'Sin patente'),
                'date' => Carbon::parse($turno->fecha_hora),
                'color' => $color,
                'estado' => $turno->estado,
                'rodado' => $turno->rodado->display_name ?? 'N/A',
                'taller' => $turno->taller->nombre ?? 'N/A',
            ]);
        }

        // Obtener cambios de equipos
        $cambiosEquipos = CambioEquipoRodado::with(['rodado', 'taller'])
            ->whereBetween('fecha_hora_estimada', [$startDate, $endDate])
            ->get();

        foreach ($cambiosEquipos as $cambio) {
            $events->push([
                'id' => 'cambio_'.$cambio->id,
                'tipo' => 'cambio_equipo',
                'title' => 'Cambio Equipo: '.($cambio->rodado->patente ?? 'Sin patente'),
                'date' => Carbon::parse($cambio->fecha_hora_estimada),
                'color' => '#10b981', // Verde
                'rodado' => $cambio->rodado->display_name ?? 'N/A',
                'taller' => $cambio->taller->nombre ?? 'N/A',
            ]);
        }

        // Obtener pagos PENDIENTES (solo los no pagados, mostrados en fecha de vencimiento)
        $pagos = PagoServiciosRodado::with(['rodado', 'proveedor', 'servicioUsuario'])
            ->where('estado', '!=', 'pagado')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        foreach ($pagos as $pago) {
            // Determinar titulo: si tiene servicio mensual usar nombre del servicio, si no usar patente
            if ($pago->servicioUsuario) {
                $title = 'Pago Pendiente: '.$pago->servicioUsuario->nombre;
            } else {
                $title = 'Pago Pendiente: '.($pago->rodado?->patente ?? ucfirst(str_replace('_', ' ', $pago->tipo)));
            }

            $events->push([
                'id' => 'pago_'.$pago->id,
                'tipo' => 'pago',
                'title' => $title,
                'date' => Carbon::parse($pago->fecha_vencimiento),
                'color' => '#ef4444', // Rojo
                'rodado' => $pago->rodado?->display_name ?? null,
                'servicio_nombre' => $pago->servicioUsuario?->nombre ?? null,
                'estado' => 'pendiente',
            ]);
        }

        return $events;
    }

    public function getDaysInMonth()
    {
        $days = [];
        $currentDate = Carbon::parse($this->currentDate);
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startOfWeek = $startOfMonth->copy()->startOfWeek();
        $endOfWeek = $endOfMonth->copy()->endOfWeek();

        $currentDay = $startOfWeek->copy();
        while ($currentDay <= $endOfWeek) {
            $days[] = $currentDay->copy();
            $currentDay->addDay();
        }

        return $days;
    }

    public function getDaysInWeek()
    {
        $days = [];
        $currentDate = Carbon::parse($this->currentDate);
        $startOfWeek = $currentDate->copy()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }

        return $days;
    }

    public function getEventsForDate($date)
    {
        $dateStr = $date->format('Y-m-d');
        $events = $this->getEvents();

        return $events->filter(function ($event) use ($dateStr) {
            $eventDate = $event['date'];
            if ($eventDate instanceof Carbon) {
                return $eventDate->format('Y-m-d') === $dateStr;
            }

            return false;
        });
    }

    public function render()
    {
        return view('livewire.rodados.calendario-rodados');
    }
}
